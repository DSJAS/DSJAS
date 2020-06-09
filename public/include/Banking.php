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

define("RANDOM_ACCOUNT_NAMES", ["Checking account", "Savings account", "Free basic account", "War bond", "Credit plus"]);
define("ACCOUNT_TYPES", ["current", "savings", "shared", "misc"]);

define("TRANSACTION_TYPES", ["transfer", "withdrawal", "purchase", "misc"]);


function performTransaction($sourceAccount, $destAccount, $amount, $description = "Account transfer", $type = "transfer")
{
    // Insert transaction into the transactions table
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $db_hostname = $configuration["server_hostname"];
    $db_dbname = $configuration["database_name"];
    $db_username = $configuration["username"];
    $db_password = $configuration["password"];

    $database = new DB($db_hostname, $db_dbname, $db_username, $db_password);

    $query = new PreparedStatement(
        "INSERT INTO `transactions` (`origin_account_id`, `dest_account_id`, `transaction_amount`, `transaction_description`, `transaction_type`) VALUES (?, ?, ?, ?, ?)",
        [$sourceAccount, $destAccount, $amount, $description, $type],
        "iidss"
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

function drainAccount($accountID)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `account_balance` = 0.00 WHERE `account_identifier` = ?",
        [$accountID],
        "i"
    );

    $database->prepareQuery($query);
    $database->query();

    return $query->result;
}

function disableAccount($accountID)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `account_disabled` = 1 WHERE `account_identifier` = ?",
        [$accountID],
        "i"
    );

    $database->prepareQuery($query);
    $database->query();

    return $query->result;
}

function enableAccount($accountID)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `account_disabled` = 0 WHERE `account_identifier` = ?",
        [$accountID],
        "i"
    );

    $database->prepareQuery($query);
    $database->query();

    return $query->result;
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

function getAllAccountsForUser($userID)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "SELECT * FROM `accounts` WHERE `associated_online_account_id` = ?",
        [$userID],
        "i"
    );

    $database->prepareQuery($query);
    $database->query();

    return $query->result;
}

function getAllTransactions()
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new SimpleStatement("SELECT * FROM `transactions`");

    $database->unsafeQuery($query);

    return $query->result;
}

function getAllTransactionsForUser($userID)
{
    $config = dashboardLoadDatabaseInformation();
    $userId = getCurrentUserId();

    $database = new DB(
        $config["server_hostname"],
        $config["database_name"],
        $config["username"],
        $config["password"]
    );

    $accountsQuery = new SimpleStatement("SELECT * FROM `accounts` WHERE `associated_online_account_id` = $userID");
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

    $query = new SimpleStatement("SELECT * FROM `transactions` WHERE $whereText ORDER BY `transaction_id`");
    $database->unsafeQuery($query);

    if ($query->result === false) {
        return [];
    }

    return $query->result;
}

function createAccount($accountName, $associatedID, $type, $holderName = "John Doe", $disabled = false, $initialBalance = 125.50)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "INSERT INTO `accounts` (`account_name`, `account_type`, `account_balance`, `holder_name`, `associated_online_account_id`, `account_disabled`) VALUES (?, ?, ?, ?, ?, ?)",
        [$accountName, $type, $initialBalance, $holderName, $associatedID, $disabled],
        "ssdsii"
    );

    $database->prepareQuery($query);
    $database->query();

    return $query->result;
}

function getRandomAccountName()
{
    return RANDOM_ACCOUNT_NAMES[array_rand(RANDOM_ACCOUNT_NAMES)];
}

function getRandomBalance()
{
    $randomBalanceWhole = rand(-50, 2000);
    $randomBalanceDec = rand(0, 99);

    return $randomBalanceWhole + ($randomBalanceDec / 100);
}

function getRandomAccountType()
{
    return ACCOUNT_TYPES[array_rand(ACCOUNT_TYPES)];
}

function genRandomBankAccounts($userID, $amount = 3)
{
    for ($i = 0; $i < $amount; $i++) {
        $randomAccountName = getRandomAccountName();
        $randomBalance = getRandomBalance();
        $randomType = getRandomAccountType();

        createAccount($randomAccountName, $userID, $randomType, getInfoFromUserID($userID, "username"), true, $randomBalance);
    }
}

function associateAccountWithUser($userID, $accountID)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `associated_online_account_id` = ? WHERE `account_identifier` = ?",
        [$userID, $accountID],
        "ii"
    );

    $database->prepareQuery($query);
    $database->query();

    return $query->result;
}

function closeAccount($accountID)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "DELETE FROM `accounts` WHERE `account_identifier` = $accountID",
        [$accountID],
        "i"
    );

    $database->prepareQuery($query);
    $database->query();
}

function reverseTransaction($id, $refund = true)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($refund) {
        $query = new PreparedStatement(
            "SELECT * FROM `transactions` WHERE `transaction_id` = ?",
            [$id],
            "i"
        );

        $database->prepareQuery($query);
        $database->query();

        $refunded = $query->result[0]["origin_account_id"];
        $deducted = $query->result[0]["dest_account_id"];
        $amount = $query->result[0]["transaction_amount"];

        $refund = new SimpleStatement("UPDATE `accounts` SET `account_balance` = (`account_balance` + $amount) WHERE `account_identifier` = $refunded");
        $database->query($refund);

        $deduction = new SimpleStatement("UPDATE `accounts` SET `account_balance` = (`account_balance` - $amount) WHERE `account_identifier` = $deducted");
        $database->query($deduction);
    }

    $query = new PreparedStatement(
        "DELETE FROM `transactions` WHERE `transaction_id` = ?",
        [$id],
        "i"
    );

    $database->prepareQuery($query);
    $database->query();
}
