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

require ABSPATH . "/include/install/Database.php";
require ABSPATH . "/include/install/Utils.php";
require ABSPATH . "/include/install/TrackState.php";
require_once ABSPATH . INC . "Customization.php";

/* Shared config */
$sharedInstallConfig = new Configuration(true, false, false, false);

/* Early session init */
session_start();

$configuration = parse_ini_file(ABSPATH . "/Config.ini");

if (!installRequired($configuration)) {
    header("Location: /");
} elseif (findRedirectLocation($configuration) != STEP_URL) {
    redirectToInstall($configuration);
}

if (defined("STEP_PROTECT") && STEP_PROTECT)
{

    if (!verifySetupAuth()) { ?>
        <div class="alert alert-danger" role="alert">
            <p><strong>Security error</strong> You are not authorized to run the setup process. Your authentication token is not authorized to continue the process.</p>
        </div>
    <?php
        die();
    }
}


?>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta title="Installation - D.S Johnson & Son">
    <meta description="This page will allow you to install D.S Johnson & Son on your server">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/include/styles/install.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="/include/js/install.js"></script>
</head>