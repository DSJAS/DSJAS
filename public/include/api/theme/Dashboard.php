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

/*
    THEMING API
    ===========

    This file contains the functions and APIs required to write a theme
    for DSJAS.

    It does nothing on its own, but does provide useful utility functions
    for theming scripts and provides a way for a theme to be consistent
    in behaviour to the rest of the site.

    For more information on the theming API, please refer to the API
    documentation.

*/

require_once ABSPATH . INC . "DB.php";

require_once ABSPATH . INC . "Users.php";


// Cached database information for created databases
static $db_hostname = null;
static $db_username = null;
static $db_password = null;
static $db_dbname = null;

function getAccountsArray()
{
    $config = dashboardLoadDatabaseInformation();
    $userId = getCurrentUserId();

    $database = new DB(
        $config["server_hostname"],
        $config["database_name"],
        $config["username"],
        $config["password"]
    );

    $query = new SimpleStatement("SELECT * FROM `accounts` WHERE `associated_online_account_id` = $userId");
    $database->unsafeQuery($query);

    return $query->result;
}

function getRecentTransactionsArray($loadAmount)
{
    $config = dashboardLoadDatabaseInformation();
    $userId = getCurrentUserId();

    $database = new DB(
        $config["server_hostname"],
        $config["database_name"],
        $config["username"],
        $config["password"]
    );

    $accountsQuery = new SimpleStatement("SELECT * FROM `accounts` WHERE `associated_online_account_id` = $userId");
    $database->unsafeQuery($accountsQuery);

    $whereText = "";

    $iteration = 0;
    foreach ($accountsQuery->result as $account) {
        $whereText .= "`origin_account_id` = ";
        $whereText .= $account["account_identifier"];

        $iteration++;
        if ($iteration < count($accountsQuery->result)) {
            $whereText .= " OR ";
        };
    }

    $query = new SimpleStatement("SELECT * FROM `transactions` WHERE $whereText ORDER BY `transaction_id` DESC LIMIT $loadAmount");
    $database->unsafeQuery($query);

    if ($query->result === false) {
        return [];
    }

    return $query->result;
}

function censorAccountNumber($number, $stripChars = 5, $censorChar = "*", $pad = true)
{
    if ($pad && strlen($number) < $stripChars) {
        $useNumber = str_pad($number, $stripChars * 2, "0", STR_PAD_LEFT);
    } else {
        $useNumber = $number;
    }

    $array = str_split($useNumber);

    $counter = 0;
    foreach ($array as &$digit) {
        if ($counter < $stripChars) {
            $digit = $censorChar;
        }

        $counter++;
    }

    return implode("", $array);
}

function isPricePositive($priceString, $regionalCurrencySymbol = "$")
{
    if ($priceString[0] == $regionalCurrencySymbol) {
        return $priceString[1] != "-";
    } else {
        return $priceString[0] != "-";
    }
}

function getDisplayBalance($accountID)
{
    $config = dashboardLoadDatabaseInformation();

    $database = new DB(
        $config["server_hostname"],
        $config["database_name"],
        $config["username"],
        $config["password"]
    );

    $query = new PreparedStatement("SELECT `account_balance` FROM `accounts` WHERE `account_identifier` = ?", [$accountID], "i");
    $database->prepareQuery($query);
    $database->query();

    $balanceValue = $query->result[0]["account_balance"];

    return "$" . $balanceValue;
}


function dashboardLoadDatabaseInformation()
{
    global $db_hostname;
    global $db_dbname;
    global $db_username;
    global $db_password;

    if ($db_hostname == null || $db_username == null || $db_password == null || $db_dbname == null) {
        $configuration = parse_ini_file(ABSPATH . "/Config.ini");

        $db_hostname = $configuration["server_hostname"];
        $db_dbname = $configuration["database_name"];
        $db_username = $configuration["username"];
        $db_password = $configuration["password"];

        return $configuration;
    } else {
        $details = array();
        $details["server_hostname"] = $db_hostname;
        $details["database_name"] = $db_dbname;
        $details["username"] = $db_username;
        $details["password"] = $db_password;

        return $details;
    }
}
