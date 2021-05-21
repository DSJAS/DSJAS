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

define("ABSPATH", $_SERVER["DOCUMENT_ROOT"]);
define("INC", "/include/");

define("API", ABSPATH . INC . "api/");
define("EXTENSION_API", API . "plugin/");


require ABSPATH . INC . "install/Utils.php";
require ABSPATH . INC . "Util.php";

session_start();


$configuration = parse_ini_file(ABSPATH . "/Config.ini");


if (installRequired($configuration)) {
    redirectToInstall($configuration);
}

if (!isset($_SESSION["loggedin_su"]) || !$_SESSION["loggedin_su"]) {
    if ($configuration["disable_admin"]) {
        adminAccessDeniedMessage();
        header('HTTP/1.0 403 Forbidden');
        die();
    }

    if ($configuration["simulate_missing_nolog_admin"]) {
	header("Location: /error/Error.php");
	die();
    }
    header("Location: /admin/user/SignIn.php");
    die();
}

if (!defined("NOLOAD_BOOTSTRAP_HEAD")) {
?>
<head>
    <title>DSJAS Administration Panel</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta title="Secure banking site">
    <meta description="Welcome to our family bank, if you're a new customer or old, please enjoy your visit!">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="/include/styles/admin.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js" crossorigin="anonymous"></script>
    <script src="/include/js/site.js"></script>
    <script src="/include/js/admin.js"></script>


</head>

<?php }
