<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

*/

require_once(ABSPATH . INC . "Users.php");
require_once(ABSPATH . INC . "DB.php");


function performTransaction($sourceAccount, $destAccount, $amount, $description = "Account transfer")
{
    // Insert transaction into the transactions table
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $db_hostname = $configuration["server_hostname"];
    $db_dbname = $configuration["database_name"];
    $db_username = $configuration["username"];
    $db_password = $configuration["password"];

    $database = new DB($db_hostname, $db_dbname, $db_username, $db_password);

    $query = new PreparedStatement(
        "INSERT INTO `transactions` (`origin_account_id`, `dest_account_id`, `transaction_amount`, `transaction_description`, `transaction_type`) VALUES (?, ?, ?, ?, 'transfer')",
        [$sourceAccount, $destAccount, $amount, $description],
        "iids"
    );

    $database->prepareQuery($query);
    $database->query();

    // Subtract the balance from origin account
    $database->clearQuery();

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `account_balance` = (`account_balance` - ?) WHERE `account_identifier` = ?",
        [$amount, $sourceAccount],
        "di"
    );

    $database->prepareQuery($query);
    $database->query();

    // Add the balance to destination account
    $database->clearQuery();

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `account_balance` = (`account_balance` + ?) WHERE `account_identifier` = ?",
        [$amount, $destAccount],
        "di"
    );

    $database->prepareQuery($query);
    $database->query();
}

function userOwnsAccount($accountID, $userID)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $db_hostname = $configuration["server_hostname"];
    $db_dbname = $configuration["database_name"];
    $db_username = $configuration["username"];
    $db_password = $configuration["password"];

    $database = new DB($db_hostname, $db_dbname, $db_username, $db_password);

    $query = new SimpleStatement("SELECT `associated_online_account_id` FROM `accounts` WHERE `account_identifier` = $accountID");
    $database->unsafeQuery($query);

    if (count($query->result) == 0) {
        return false;
    } elseif (count($query->result) > 1) {
        return false;
    }

    return ($query->result[0]["associated_online_account_id"] == $userID);
}

function getNumberOfAccounts()
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new SimpleStatement("SELECT * FROM `accounts`");

    $database->unsafeQuery($query);

    return $query->affectedRows;
}

function getAllAccounts()
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new SimpleStatement("SELECT * FROM `accounts`");

    $database->unsafeQuery($query);

    return $query->result;
}
