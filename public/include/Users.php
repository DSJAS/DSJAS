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

require_once "DB.php";

define("LOGIN_SESSION_STR", "loggedin");
define("LOGIN_USERNAME_STR", "username");
define("LOGIN_USERID_STR", "userid");

define("LOGIN_SESSION_STR_SITEUSER", "loggedin_su");
define("LOGIN_USERNAME_STR_SITEUSER", "username_su");
define("LOGIN_USERID_STR_SITEUSER", "userid_su");


// Cached database information for created databases
static $db_hostname = null;
static $db_username = null;
static $db_password = null;
static $db_dbname = null;

function canLogin($siteuser = false)
{
    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    if ($siteuser) {
        return $configuration["allow_access_to_admin"];
    }

    if ($configuration["running_without_database"]) {
        return $configuration["running_without_database"];
    }
}

function shouldAttemptLogin()
{
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        return true;
    } else {
        return false;
    }
}

function shouldAttemptLogout($siteuser = false)
{
    if ($siteuser) {
        if (isset($_SESSION[LOGIN_SESSION_STR_SITEUSER]) && $_SESSION[LOGIN_SESSION_STR_SITEUSER] == true) {
            return true;
        } else {
            return false;
        }
    } else {
        if (isset($_SESSION[LOGIN_SESSION_STR]) && $_SESSION[LOGIN_SESSION_STR] == true) {
            return true;
        } else {
            return false;
        }
    }
}


function logout($siteuser = false)
{
    fillSessionDetails(false, "", 0, $siteuser);
}

function isLoggedIn($siteuser = false)
{
    if ($siteuser) {
        if (isset($_SESSION[LOGIN_SESSION_STR_SITEUSER])) {
            return $_SESSION[LOGIN_SESSION_STR_SITEUSER];
        }
    } else {
        if (isset($_SESSION[LOGIN_SESSION_STR])) {
            return $_SESSION[LOGIN_SESSION_STR];
        }
    }
}

function handleLogin($username, $password, $siteuser = false)
{
    $result = verifyLoginDetails($username, $password, $siteuser);

    if ($result[0]) {
        $userid = getUserIDFromName($username, $siteuser);

        if ($siteuser) {
            $enabled = getInfoFromUserID($userid, "account_enabled", true);
            if (!$enabled) {
                return array(false, -3);
            }
        }

        fillSessionDetails(true, $username, $userid, $siteuser);
    }

    return $result;
}

function redirectToLoggedIn($siteuser = false)
{
    if ($siteuser) {
        header("Location: /admin/dashboard.php");
    } else {
        header("Location: /user/Dashboard.php");
    }
}

function getCurrentUsername($siteuser = false)
{
    if ($siteuser) {
        return $_SESSION[LOGIN_USERNAME_STR_SITEUSER];
    } else {
        return $_SESSION[LOGIN_USERNAME_STR];
    }
}

function getCurrentUserId($siteuser = false)
{
    if ($siteuser) {
        return $_SESSION[LOGIN_USERID_STR_SITEUSER];
    } else {
        return $_SESSION[LOGIN_USERID_STR];
    }
}

function currentUserIsNew($siteuser = false)
{
    if ($siteuser) {
        $configuration = loadDatabaseInformation();

        $database = new DB(
            $configuration["server_hostname"],
            $configuration["database_name"],
            $configuration["username"],
            $configuration["password"]
        );

        $query = new PreparedStatement("SELECT `new_account` FROM `siteusers` WHERE `username` = ?", [getCurrentUsername(true)], "s");

        $database->prepareQuery($query);
        $database->query();

        $database->disconnect();

        return $query->result[0]["new_account"];
    } else {
        return false;
    }
}

function makeCurrentUserUsed($siteuser = false)
{
    if ($siteuser) {
        $configuration = loadDatabaseInformation();

        $database = new DB(
            $configuration["server_hostname"],
            $configuration["database_name"],
            $configuration["username"],
            $configuration["password"]
        );

        $query = new PreparedStatement("UPDATE `siteusers` SET `new_account` = 0 WHERE `user_id` = ?", [getCurrentUserId(true)], "s");

        $database->prepareQuery($query);
        $database->query();

        $database->disconnect();

        return $query->affectedRows;
    } else {
        return false;
    }
}

function getUsersArray($siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $table = "siteusers";
    } else {
        $table = "users";
    }

    $query = new SimpleStatement("SELECT * FROM `" . $table . "`");
    $database->unsafeQuery($query);

    $database->disconnect();

    return $query->result;
}

function getUserIDFromName($username, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $table = "siteusers";
    } else {
        $table = "users";
    }

    $query = new PreparedStatement("SELECT `user_id` FROM `" . $table . "` WHERE `username` = ?", [$username], "s");

    $database->prepareQuery($query);
    $database->query();

    $database->disconnect();

    return $query->result[0]["user_id"];
}

function getInfoFromUserID($id, $colName, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $query = new PreparedStatement("SELECT `" . $colName . "` FROM " . $tableName . " WHERE `user_id` = ?", [$id], "i");

    $database->prepareQuery($query);
    $database->query();

    $database->disconnect();

    return $query->result[0][$colName];
}

function verifyLoginDetails($username, $pass, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $result_array = array(false, -1);

    if (verifyUsername($database, $username, $siteuser)) {
        if (verifyPassword($database, $pass, $username, $siteuser)) {
            $result_array[0] = true;
            $result_array[1] = 0;
        } else {
            $result_array[0] = false;
            $result_array[1] = -2;
        }
    } else {
        $result_array[0] = false;
        $result_array[1] = -1;
    }

    return $result_array;
}

function userExists($username = null, $email = null, $realname = null, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    if ($username != null) {
        $query = new PreparedStatement("SELECT `user_id` FROM " . $tableName . " WHERE `username` = ?", [$username], "s");

        $database->prepareQuery($query);
        $database->query();
    } elseif ($email != null) {
        $query = new PreparedStatement("SELECT `user_id` FROM " . $tableName . " WHERE `email` = ?", [$email], "s");

        $database->prepareQuery($query);
        $database->query();
    } elseif ($realname != null) {
        $query = new PreparedStatement("SELECT `user_id` FROM " . $tableName . " WHERE `email` = ?", [$email], "s");

        $database->prepareQuery($query);
        $database->query();
    }

    var_dump($query->affectedRows);
    var_dump($query->result);

    return $query->affectedRows > 0;
}

function changeUserPassword($userID, $newPassword, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $query = new PreparedStatement("UPDATE " . $tableName . " SET `password_hash` = ?, `password_hint` = 'Your password was reset' WHERE `user_id` = ?", [$newHash, $userID], "si");

    $database->prepareQuery($query);
    $database->query();
}

function createUser($username, $email, $password, $passwordHint, $enabled = true, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($siteuser) {
        $query = new PreparedStatement(
            "INSERT INTO " . $tableName . " (`username`, `email`, `password_hash`, `password_hint`, `account_enabled`, `new_account`) VALUES (?, ?, ?, ?, ?, 1)",
            [$username, $email, $passwordHash, $passwordHint, $enabled],
            "ssssii"
        );
    } else {
        $query = new PreparedStatement(
            "INSERT INTO " . $tableName . " (`username`, `email`, `password_hash`, `password_hint`) VALUES (?, ?, ?, ?)",
            [$username, $email, $passwordHash, $passwordHint],
            "ssss"
        );
    }



    $database->prepareQuery($query);
    $database->query();
}

function eraseUser($userID, $siteuser = false, $closeBanks = true)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    // Delete associated bank accounts (bank users only)
    if (!$siteuser && $closeBanks) {
        $query = new SimpleStatement("DELETE FROM `accounts` WHERE `associated_online_account_id` = $userID");

        $database->unsafeQuery($query);
    }

    // Delete actual user account
    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $query = new SimpleStatement("DELETE FROM " . $tableName . " WHERE `user_id` = $userID");

    $database->unsafeQuery($query);
}

function disableUser($userID, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $query = new SimpleStatement("UPDATE " . $tableName . "SET `account_enabled` = 0 WHERE `user_id` = $userID");

    $database->unsafeQuery($query);
}

function enableUser($userID, $siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $query = new SimpleStatement("UPDATE " . $tableName . "SET `account_enabled` = 1 WHERE `user_id` = $userID");

    $database->unsafeQuery($query);
}

function getNumberOfUsers($siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $query = new SimpleStatement("SELECT * FROM $tableName");

    $database->unsafeQuery($query);

    return $query->affectedRows;
}

function getAllUsers($siteuser = false)
{
    $configuration = loadDatabaseInformation();

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if ($siteuser) {
        $tableName = "`siteusers`";
    } else {
        $tableName = "`users`";
    }

    $query = new SimpleStatement("SELECT * FROM $tableName");

    $database->unsafeQuery($query);

    return $query->result;
}

function loadDatabaseInformation()
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

function fillSessionDetails($loggedin, $username, $userid, $siteuser = false)
{
    if ($siteuser) {
        $_SESSION[LOGIN_SESSION_STR_SITEUSER] = $loggedin;
        $_SESSION[LOGIN_USERNAME_STR_SITEUSER] = $username;
        $_SESSION[LOGIN_USERID_STR_SITEUSER] = $userid;
    } else {
        $_SESSION[LOGIN_SESSION_STR] = $loggedin;
        $_SESSION[LOGIN_USERNAME_STR] = $username;
        $_SESSION[LOGIN_USERID_STR] = $userid;
    }
}

function verifyUsername($db, $username, $siteuser = false)
{
    if ($siteuser) {
        $table = "siteusers";
    } else {
        $table = "users";
    }

    $query = new PreparedStatement("SELECT * FROM `" . $table . "` WHERE `username` = ?", [$username], "s");

    $db->prepareQuery($query);
    $db->query();

    return count($query->result) > 0;
}

function verifyPassword($db, $password, $username, $siteuser = false)
{
    if ($siteuser) {
        $table = "siteusers";
    } else {
        $table = "users";
    }

    $query = new PreparedStatement("SELECT * FROM `" . $table . "` WHERE `username` = ?", [$username], "s");

    $db->prepareQuery($query);
    $db->query();

    $array = $query->result[0];
    $db_pass_hash = $array["password_hash"];

    return password_verify($password, $db_pass_hash);
}
