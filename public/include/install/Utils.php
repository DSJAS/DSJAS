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

function update_config($section, $key, $value)
{
    $config_data = parse_ini_file("../../Config.ini", true);
    $config_data[$section][$key] = $value;
    $new_content = '';
    foreach ($config_data as $section => $section_content) {
        $section_content = array_map(
            function ($value, $key) {
                return "$key=$value";
            },
            array_values($section_content),
            array_keys($section_content)
        );
        $section_content = implode("\n", $section_content);
        $new_content .= "[$section]\n$section_content\n";
    }
    file_put_contents("../../Config.ini", $new_content);
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
        return "/error/Error.php";
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
    $myfile = fopen($path, "r") or die("Error: Unable to reopen token file! Did you delete or move it?");
    $code = fread($myfile, filesize($path));
    fclose($myfile);

    $input_token = $_POST["code"];

    if ($input_token === $code) {
        update_config("setup", "owner_verified", "1");

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
        <p class="lead">Using DSJ&S without a database can lead to buggy behaviour, reduced features and a broken site</p>
        <p><strong>Are you sure you wish to continue?</strong></p>
        <hr>
        <a class="btn btn-danger" href="/admin/install/db_config.php?nodb&confirm=1">Yes, I am sure I don't want to setup a database</a>
        <a class="btn btn-secondary" href="/admin/install/db_config.php">No, I would like to setup a database</a>
    </div>

<?php }

function saveDatabaseInformation()
{
    if (verifyFieldsPresent()) {

        update_config("database", "running_without_database", "0");

        update_config("database", "server_hostname", $_POST["servername"]);
        update_config("database", "database_name", $_POST["dbname"]);
        update_config("database", "username", $_POST["username"]);
        update_config("database", "password", $_POST["password"]);
    } else {
        die("Error: One or more required fields were not given");
    }
}

function verifyFieldsPresent()
{
    return isset($_POST["servername"]) && isset($_POST["dbname"]) && isset($_POST["username"]) && isset($_POST["password"]);
}

function handleDBVerification()
{
    $config_path = $_SERVER["DOCUMENT_ROOT"] . "/Config.ini";

    $configuration = parse_ini_file($config_path);

    $link = mysqli_connect($configuration["server_hostname"], $configuration["username"], $configuration["password"]);
    if (!$link) {
        return false;
    }


    mysqli_select_db($link, $configuration["database_name"]);
    if (mysqli_error($link) != null) {
        return false;
    }

    mysqli_close($link);
    return true;
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
    update_config("customization", "bank_name", $_POST["bankName"]);
    update_config("customization", "bank_domain", $_POST["url"]);
    update_config("settings", "allow_access_to_admin", $_POST["admin"]);
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
