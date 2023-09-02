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

require_once "Database.php";
require_once ABSPATH . INC . "Customization.php";

function verifySubmodules()
{
    return file_exists(ABSPATH . INC . "/vendor/hooks/src/gburtini/Hooks/Hooks.php") &&
        file_exists(ABSPATH . INC . "/vendor/requests/library/Requests.php");
}

function installRequired($configuration)
{
    if (!$configuration["installed"] || !$configuration["database_installed"] || !$configuration["owner_verified"] || !$configuration["install_finalized"]) {
        return true;
    } else {
        return false;
    }
}

function redirectToInstall($configuration)
{
    header("Location: " . findRedirectLocation($configuration));
    die();
}

function findRedirectLocation($configuration)
{
    if (!$configuration["installed"]) {
        return "/admin/install/install.php";
    } elseif (!$configuration["owner_verified"]) {
        return "/admin/install/verification.php";
    } elseif (!$configuration["database_installed"]) {
        return "/admin/install/db_config.php";
    } elseif (!$configuration["install_finalized"]) {
        return "/admin/install/final.php";
    } else {
        return "/Error.php";
    }
}

function generateRandomString($length = 30)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function generateVerificationToken($path)
{
    $token = generateRandomString();

    $myfile = fopen($path, "w") or die("Unable to open file!");
    fwrite($myfile, $token);
}

function verificationCodeSent()
{
    return isset($_POST["code"]);
}

function handleVerificationCode($path)
{
    global $sharedInstallConfig;

    $myfile = fopen($path, "r") or die("Error: Unable to reopen token file! Did you delete or move it?");
    $code = fread($myfile, filesize($path));
    fclose($myfile);

    $input_token = $_POST["code"];

    if ($input_token === $code) {
        $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "setup", "owner_verified", "1");

        $_SESSION["setup_authorised"] = true;

        header("Location: /admin/install/db_config.php?feedback_success");
        unlink($path);
        die();
    } else {
        header("Location: /admin/install/verification.php?failure");
        die();
    }
}

function verifySetupAuth()
{
    return $_SESSION["setup_authorised"];
}

function handleNoDBConfirmation()
{
?>
    <div class="text-center">
        <h1 style="color: red">Warning</h1>
        <p class="lead">Using DSJAS without a database can lead to buggy behaviour, reduced features and a broken site</p>
        <p><strong>Are you sure you wish to continue?</strong></p>
        <hr>
        <a class="btn btn-danger" href="/admin/install/db_config.php?nodb&confirm=1">Yes, I am sure I don't want to setup a database</a>
        <a class="btn btn-secondary" href="/admin/install/db_config.php">No, I would like to setup a database</a>
    </div>

<?php }

function saveDatabaseInformation()
{
    global $sharedInstallConfig;

    if (verifyFieldsPresent()) {

        $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "database", "running_without_database", "0");

        $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "database", "server_hostname", $_POST["servername"]);
        $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "database", "database_name", $_POST["dbname"]);
        $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "database", "username", $_POST["username"]);
        $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "database", "password", $_POST["password"]);
    } else {
        die("Error: One or more required fields were not given");
    }
}

function verifyFieldsPresent()
{
    return isset($_POST["servername"]) && isset($_POST["dbname"]) && isset($_POST["username"]) && isset($_POST["password"]);
}

function setupPrimaryAdministrator()
{
    $uname = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hint = $_POST["passwordHint"];

    if (!verifyFinalFieldsPresent($uname, $email, $password, $hint)) {
        die("ERROR: MISSING");
    }
    if (!verifyPasswordStrength($password)) {
        die("ERROR: WEAKPASS");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    safeInsertUser($uname, $hash, $hint, $email, 0);
}

function setupPrimarySettings()
{
    global $sharedInstallConfig;

    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "customization", "bank_name", $_POST["bankName"]);
    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "customization", "bank_domain", $_POST["url"]);
    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "settings", "disable_admin", $_POST["admin"]);
}

function handleSkipFinal()
{
    // TODO: Do something here?
}

function verifyRequiredConfig()
{
    $correct = isset($_POST["usernameInput"]) && isset($_POST["passwordInput"]) && isset($_POST["administrativeCheck"]);

    if (!$correct) {
        echo ("Error");
        die();
    }
}

function verifyPasswordStrength($pass)
{
    if (strlen($pass) < 5) {
        return false;
    }

    if (!preg_match('/[A-Za-z]/', $pass) || !preg_match('/[0-9]/', $pass)) {
        return false;
    }

    return true;
}

function verifyFinalFieldsPresent($username, $email, $password, $hint)
{
    if (!isset($username) || $username == "" || !isset($email) || $email == "" || !isset($password) || $password == "" || !isset($hint) || $hint == "") {
        return false;
    }

    return true;
}