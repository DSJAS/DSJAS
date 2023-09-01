<?php

const requiredTables = array("users", "siteusers", "accounts", "transactions", "statistics");

const tableColumns = array(
    "`user_id` BIGINT NOT NULL AUTO_INCREMENT , `username` TINYTEXT NOT NULL , `real_name` TEXT NOT NULL , `password_hash` LONGTEXT NOT NULL , `password_hint` TEXT NOT NULL , `email` TEXT NOT NULL , `date_of_registration` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`user_id`)",
    "`user_id` BIGINT NOT NULL AUTO_INCREMENT , `username` TINYTEXT NOT NULL , `real_name` TEXT NOT NULL , `password_hash` LONGTEXT NOT NULL , `password_hint` TEXT NOT NULL , `email` TEXT NOT NULL , `date_of_registration` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, `account_enabled` BOOLEAN NOT NULL DEFAULT TRUE, `new_account` BOOLEAN NOT NULL , PRIMARY KEY (`user_id`)",
    "`account_identifier` INT NOT NULL AUTO_INCREMENT , `account_number` INT(11) NOT NULL, `account_name` TEXT NOT NULL , `account_type` ENUM('current','savings','shared','misc') NOT NULL DEFAULT 'current' , `account_balance` DECIMAL(11,2) NOT NULL DEFAULT '0' , `holder_name` TEXT NOT NULL , `associated_online_account_id` INT NOT NULL , `account_disabled` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`account_identifier`)",
    "`transaction_id` BIGINT NOT NULL AUTO_INCREMENT , `transaction_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, `origin_account_id` INT NOT NULL , `dest_account_id` INT NOT NULL , `transaction_description` TEXT NOT NULL, `transaction_type` ENUM('transfer','withdrawal','purchase','misc') NOT NULL DEFAULT 'transfer', `transaction_amount` DECIMAL(11,2) NOT NULL , PRIMARY KEY (`transaction_id`)",
    "`stat_name` VARCHAR(255) NOT NULL , `stat_type` INT NOT NULL , `stat_value` INT NOT NULL , `stat_label` TEXT NOT NULL , `stat_category` TEXT NOT NULL , `sys_data` BOOLEAN NOT NULL DEFAULT FALSE , `theme_def` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`stat_name`)"
);

/* Is databsae fully installed? */
function databaseInstalled()
{
    foreach (requiredTables as $tbl) {
        if (!tableExists($tbl)) {
            return false;
        }
    }

    return true;
}

/* Is database partially installed? */
function someDatabaseInstalled()
{
    foreach (requiredTables as $tbl) {
        if (tableExists($tbl)) {
            return true;
        }
    }

    return false;
}

/*
 * WARNING: dangerous function ahead!
 * Make sure that you have prompted the user before use!
 */
function resetDatabase()
{
    foreach (requiredTables as $tbl) {
        if (tableExists($tbl)) {
            dropTable($tbl);
        }
    }
}

function dropTable($tbl)
{
    $sql = "DROP TABLE `" . $tbl . "`;";
    $conn = connectToDatabase();

    mysqli_query($conn, $sql);
}

function tableExists($tbl)
{
    $sql = "SELECT * FROM `" . $tbl . "` LIMIT 1;";

    try {
        $conn = connectToDatabase();
        $resp = mysqli_query($conn, $sql);
    } catch (Exception) {
        return false;
    }

    mysqli_close($conn);

    return $resp !== false;
}

function setupDatabaseServer()
{
    for ($i = 0; $i < count(requiredTables); $i++) {
        if (!tableExists(requiredTables[$i])) {
            createDatabaseTable(requiredTables[$i], tableColumns[$i]);
        }
    }

    setupDefaultAccounts();
}

function createDatabaseTable($tableName, $cols)
{
    $sql = "CREATE TABLE `" . $tableName . "` (" . $cols . ")";

    $connection = connectToDatabase();

    $resp = mysqli_query($connection, $sql);

    mysqli_close($connection);
}

function setupDefaultAccounts()
{
    $configuration = parse_ini_file("../../Config.ini");

    $hostname = $configuration["server_hostname"];
    $dbname = $configuration["database_name"];
    $uname = $configuration["username"];
    $pw = $configuration["password"];

    $sql = new mysqli($hostname, $uname, $pw, $dbname);

    $passHash = password_hash("hunter2", PASSWORD_DEFAULT);

    $sql->query("INSERT INTO `users` (`username`, `real_name`, `password_hash`, `password_hint`, `email`) VALUES ('edna', 'Edna Gooseberry', '$passHash', 'Hunter2', 'edna.g@aol.com')");

    $sql->query("INSERT INTO `accounts` (`account_identifier`, `account_number`, `account_name`, `account_type`, `account_balance`, `holder_name`, `associated_online_account_id`, `account_disabled`) VALUES
        (1, 909111628, 'Checking Account', 'current', '1200.00', 'Edna Gooseberry', 1, 0),
        (2, 285058645, 'Savings Account', 'savings', '5420.00', 'Edna Gooseberry', 1, 0),
        (3, 786878148, 'War bond', 'misc', '3600.00', 'Edna Gooseberry', 1, 0),
        (4, 869033308, 'Money Market', 'misc', '1500250.42', 'Edna Gooseberry', 1, 0);
    ");

}

function connectToDatabase()
{
    $configuration = parse_ini_file("../../Config.ini");

    $hostname = $configuration["server_hostname"];
    $dbname = $configuration["database_name"];
    $uname = $configuration["username"];
    $pw = $configuration["password"];

    $conn = mysqli_connect($hostname, $uname, $pw, $dbname);

    return $conn;
}

function safeInsertUser($username, $password_hash, $password_hint, $email, $userid, $realName = "user")
{
    $configuration = parse_ini_file("../../Config.ini");

    $hostname = $configuration["server_hostname"];
    $dbname = $configuration["database_name"];
    $uname = $configuration["username"];
    $pw = $configuration["password"];

    $new = 1;

    $sql = new mysqli($hostname, $uname, $pw, $dbname);
    $statement = $sql->prepare("INSERT INTO `siteusers` (`username`, `email`, `password_hash`, `password_hint`, `new_account`, `user_id`, `real_name`) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $username = sanitizeUserCredentials($username, $sql);
    $email = sanitizeUserCredentials($email, $sql);
    $password_hint = sanitizeUserCredentials($password_hint, $sql);

    $statement->bind_param("ssssiis", $username, $email, $password_hash, $password_hint, $new, $userid, $realName);
    $statement->execute();

    $statement->close();
    $sql->close();
}

function sanitizeUserCredentials($value, $sql)
{
    $safeValue = htmlentities($value);
    $safeValue = $sql->escape_string($value);

    return $safeValue;
}

function handleDBVerification()
{
    try {
        $conn = connectToDatabase();
    } catch (Exception) {
        return false;
    }

    if ($conn === false) {
        return false;
    }

    return databaseInstalled();
}