<?php

/**
 * This file is part of DSJAS
 * Written and maintained by the DSJAS project.
 *
 * Copyright (C) 2020 - Ethan Marshall
 *
 * DSJAS is free software which is licensed and distributed under
 * the terms of the MIT software licence.
 * Exact terms can be found in the LICENCE file.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * above mentioned licence for specific details.
 */

class SimpleStatement
{
    private $queryString;

    public $result = array();
    public $affectedRows;

    function __construct($statement)
    {
        $this->queryString = $statement;
    }

    function __destruct()
    {
    }

    function getStatement()
    {
        return $this->queryString;
    }
}

class PreparedStatement
{
    private $template;
    private $values = array();
    private $types;

    public $result;
    public $affectedRows;

    public $success;

    function __construct($template, $boundValues, $types)
    {
        $this->template = $template;
        $this->values = $boundValues;
        $this->types = $types;
    }

    function __destruct()
    {
    }

    function getTemplate()
    {
        return $this->template;
    }

    function getBoundValue($index)
    {
        return $this->values[$index];
    }

    function getBoundValues()
    {
        return $this->values;
    }

    function getTypes()
    {
        return $this->types;
    }

    function rebindValues($types, $values)
    {
        $this->types = $types;
        $this->values = $values;
    }

    function rebindValue($index, $type, $value)
    {
        $types[$index] = $type;
        $values[$index] = $value;
    }
}

class DB
{

    private $sql;
    private $statement;

    private $host;
    private $database;
    private $username;
    private $password;
    private $port;

    private $config;
    private $dbEnabled = true;

    private $autocommit = true;
    private $uncommittedChanges = false;

    private $statementPrepared = false;
    private $preparedObject;

    function __construct($hostname, $dbname, $username, $password, $port = 3306)
    {
        $this->host = $hostname;
        $this->port = $port;
        $this->database = $dbname;
        $this->username = $username;
        $this->password = $password;

        $this->sql = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

        $this->config = parse_ini_file(ABSPATH . "/Config.ini", true);
        $this->dbEnabled = !$this->config["database"]["running_without_database"];
    }

    function __destruct()
    {
    }

    function validateConnection()
    {
        if (!$this->dbEnabled) {
            return false;
        }
        if ($this->sql->connect_errno) {
            return false;
        }

        return true;
    }

    function getConnectionErrors()
    {
        if (!$this->dbEnabled) {
            return "Refused to connect: Database disabled via site configuration";
        }
        else if ($this->sql->connect_errno) {
            return $this->sql->connect_error;
        }
        else {
            return "No errors reported";
        }
    }

    function validateAction()
    {
        if (!$this->dbEnabled) {
            return false;
        }
        if ($this->sql->errno) {
            return false;
        }

        return true;
    }

    function getActionErrors()
    {
        if (!$this->dbEnabled) {
            return "Action refused: Database disabled via site configuration";
        }
        else if ($this->sql->errno) {
            return $this->sql->error;
        }
        else {
            return "No errors reported";
        }

    }

    function configureAutoCommit($commit = true)
    {
        $this->sql->autocommit($commit);
        $this->autocommit = $commit;
    }

    function commit()
    {
        if (!$this->autocommit) {
            $success = $this->sql->commit();
        }

        $this->uncommittedChanges = $success;
        return $success;
    }

    function disconnect()
    {
        if ($this->statementPrepared) {
            $this->statement->close();
        }
        $this->sql->close();
    }

    function safeDisconnect()
    {
        if ($this->autocommit = false && $this->uncommittedChanges) {
            $this->commit();
        }


        if ($this->statementPrepared) {
            $this->statement->close();
        }
        $this->disconnect();
    }

    function unsafeQuery(SimpleStatement $queryObject)
    {
        $this->uncommittedChanges = true;

        $statement = $queryObject->getStatement();

        $result = $this->sql->query($statement);

        if (!is_bool($result)) {
            while ($row = $result->fetch_assoc()) {
                array_push($queryObject->result, $row);
            }

            $queryObject->affectedRows = $this->sql->affected_rows;
        } else {
            $queryObject->result = $result;
            $queryObject->affectedRows = 0;
        }
    }

    function prepareQuery(PreparedStatement $queryObject)
    {
        $this->preparedObject = $queryObject;

        $this->statement = $this->sql->prepare($queryObject->getTemplate());

        if (!$this->statement) {
            return;
        }

        $safeBinds = $this->sanitizeInputs($queryObject->getBoundValues());
        $this->statement->bind_param($queryObject->getTypes(), ...$safeBinds);

        $this->statementPrepared = true;
    }

    function rebindQuery(PreparedStatement $queryObject)
    {
        $safeBinds = $this->sanitizeInputs($queryObject->getBoundValues());

        $this->statement->bind_param($queryObject->getTypes(), ...$safeBinds);
    }

    function clearQuery()
    {
        $this->statement->close();
        $this->statement = null;

        $this->statementPrepared = false;
    }

    function query()
    {
        if ($this->statementPrepared) {
            $result = $this->statement->execute();

            if ($result) {
                $this->preparedObject->result = $this->get_prepared_result($this->statement);
                $this->preparedObject->affectedRows = $this->statement->affected_rows;
            } else {
                return;
            }

            $this->preparedObject->success = $result;

            $this->uncommittedChanges = true;
        }
    }


    private function get_prepared_result(\mysqli_stmt $statement)
    {
        $result = array();
        $statement->store_result();
        for ($i = 0; $i < $statement->num_rows; $i++) {
            $metadata = $statement->result_metadata();
            $params = array();
            while ($field = $metadata->fetch_field()) {
                $params[] = &$result[$i][$field->name];
            }
            call_user_func_array(array($statement, 'bind_result'), $params);
            $statement->fetch();
        }
        return $result;
    }

    private function sanitizeInputs(array $inputs)
    {
        $converted = $inputs;

        foreach ($converted as &$value) {
            $value = htmlentities($value);
            $value = $this->sql->escape_string($value);
        }

        return $converted;
    }
}
