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

require "../AdminBootstrap.php";

require ABSPATH . INC . "csrf.php";
require ABSPATH . INC . "Administration.php";

require_once ABSPATH . INC . "Customization.php";

ignore_user_abort(true); // Don't allow the user to cancel this install by closing the loading browser
set_time_limit(0); // Don't stop the script if it takes too long
ob_start(); // Enable output buffering


if (isset($_POST["changeModuleStates"])) {
    $csrf = getCSRFSubmission();

    if (!verifyCSRFToken($csrf)) {
        getCSRFFailedError();
        die();
    }

    $moduleConfiguration = new Configuration(false, false, false, true);

    $modules = array_keys($_POST);
    foreach ($modules as $module) {
        if ($module == "csrf" or $module == "changeModuleStates") {
            continue;
        }

        $moduleConfiguration->setKey(ID_MODULE_CONFIG, "active_modules", $module, $_POST[$module]);
    }
} elseif (isset($_POST["installModule"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission());
    if (!$csrf) {
        die(getCSRFFailedError());
    }

    var_dump($_FILES["moduleFile"]);

    echo ("Received your file! Validating now...\n");
    ob_flush();

    $validateUpload = validateModuleUpload();

    if ($validateUpload[0] === false) {
        header("Location: " . getInstallErrorPage($validateUpload[1]));
        die();
    }

    echo ("Module validated! Your archive looks OK\n");
    echo ("Unpacking your module... (this part my take a while)\n");
    ob_flush();

    $newModName = unpackAndInstallModule($_FILES['moduleFile']['tmp_name']);

    if ($newModName[0] === false) {
        header("Location: " . getInstallErrorPage($newModName[1]));
        die();
    }

    echo ("Module handled and installed to the required location. Your module is now installed!\n");
    echo ("Success: Your module has been installed! Performing final clean-up and redirecting...");
    ob_flush();

    $configuration = new Configuration(false, false, false, true);
    $configuration->setKey(ID_MODULE_CONFIG, "active_modules", $newModName[1], "0");

    if (isset($_POST["enabled"])) {
        enableModule($newModName[1]);

        header("Location: /admin/settings/mod.php?moduleInstalledEnabled");
        die();
    }

    header("Location: /admin/settings/mod.php?moduleInstalled");
} elseif (isset($_GET["uninstallModule"])) {
    $csrf = getCSRFSubmission("GET");

    if (!verifyCSRFToken($csrf)) {
        getCSRFFailedError();
        die();
    }

    uninstallModule($_GET["uninstallModule"]);

    header("Location: /admin/settings/mod.php?moduleUninstalled");
}

ob_end_flush();
