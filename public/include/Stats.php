<?php

require_once ABSPATH . INC . "DB.php";
require_once ABSPATH . INC . "Customization.php";


define("STATISTICS_TABLE", "statistics");


class Statistics
{
    private DB $database;
    private Configuration $config;
    private bool $ownDatabaseInstance = false;
    private bool $ownConfigInstance = false;

    private bool $statsAvailable = true;

    private string $db_host;
    private string $db_name;
    private string $db_username;
    private string $db_password;


    function __construct(Configuration $configInstance=null, DB $databaseInstance=null) {
        if ($configInstance == null) {
            $this->config = new Configuration(true, false, false, false);
            $this->ownConfigInstance = true;
        }
        else {
            $this->config = $configInstance;
        }

        $this->db_host = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "server_hostname");
        $this->db_name = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "database_name");
        $this->db_username = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "username");
        $this->db_password = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "password");


        if ($databaseInstance == null) {
            $this->database = new DB($this->db_host, $this->db_name, $this->db_username, $this->db_password);
            $this->ownDatabaseInstance = true;

            if ($this->database->validateConnection() != null) {
                $this->statsAvailable = false;
                return;
            }
        }
        else {
            $this->database = $databaseInstance;
        }
    }

    function __destruct() {
        if ($this->ownDatabaseInstance) {
            $this->database->safeDisconnect();
        }
    }

    function statisticsAvailable() {
        return $this->statsAvailable;
    }

    function getStatsResources() {
        return [$this->ownConfigInstance, $this->ownDatabaseInstance];
    }
}
