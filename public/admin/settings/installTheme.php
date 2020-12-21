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

require_once ABSPATH . INC . "Util.php";

ignore_user_abort(true); // Don't allow the user to cancel this install by closing the loading browser
set_time_limit(0); // Don't stop the script if it takes too long
ob_start(); // Enable output buffering


if (isset($_POST["installTheme"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission());
    if (!$csrf) {
        die(getCSRFFailedError());
    }

    echo ("Received your file! Validating now...\n");
    ob_flush();

    $validateUpload = validateThemeUpload();

    if ($validateUpload[0] === false) {
        header("Location: " . getInstallErrorPage($validateUpload[1]));
        die();
    }

    echo ("Theme validated! Your archive looks OK\n");
    echo ("Unpacking your theme... (this part my take a while)\n");
    ob_flush();

    $newThemeName = unpackAndInstallTheme($_FILES['themeFile']['tmp_name']);

    if ($newThemeName[0] === false) {
        header("Location: " . getInstallErrorPage($newThemeName[1]));
        die();
    }

    echo ("Theme unpacked and installed to themes directory. Your theme is now installed!\n");
    echo ("Success: Your theme has been installed! Performing final clean-up and redirecting...");
    ob_flush();

    if (isset($_POST["enabled"])) {
        enableTheme($newThemeName[1]);
        resetValidatorState();

        header("Location: /admin/settings/mod.php?themeInstalledEnabled");
        die();
    }

    header("Location: /admin/settings/mod.php?themeInstalled");
} elseif (isset($_POST["installThemeURL"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission());
    if (!$csrf) {
        die(getCSRFFailedError());
    }

    echo ("Downloading file...\n");
    ob_flush();

    $fileName = downloadThemeFromURL($_POST["themeURL"]);

    if ($fileName === false) {
        header("Location: /admin/settings/mod.php?themeDownloadFailed");
        die();
    }

    echo ("Your theme has been downloaded successfully! Unpacking now...\n");
    ob_flush();

    $status = unpackAndInstallTheme($fileName, false);

    echo ("Theme unpacked!\n");
    ob_flush();

    if (!$status[0]) {
        header("Location: " . getInstallErrorPage($status[1]));
        die();
    }

    header("Location: /admin/settings/mod.php?themeInstalled");
} elseif (isset($_GET["enableTheme"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission("GET"));
    if (!$csrf) {
        die(getCSRFFailedError());
    }

    if ($_GET["enableTheme"] == "default") {
        enableDefaultTheme();

        header("Location: /admin/settings/mod.php?activatedTheme");
        die();
    }

    if (!themeExists($_GET["enableTheme"])) {
        redirect("/admin/settings/mod.php?noSuchTheme");
        die();
    }

    enableTheme($_GET["enableTheme"]);
    resetValidatorState();

    header("Location: /admin/settings/mod.php?activatedTheme");
} elseif (isset($_GET["uninstallTheme"])) { ?>
    <div class="text-center">
        <h1 style="color: red">Warning</h1>
        <p class="lead">Uninstalling this theme will make it unavailable unless you re-upload it.
            Unless you don't plan to use this theme again, consider disabling the theme without uninstalling.
        </p>
        <p><strong>Are you sure you wish to continue?</strong></p>
        <hr>
        <a class="btn btn-danger" href="/admin/settings/installTheme.php?doUninstallTheme=<?php echo ($_GET["uninstallTheme"]); ?>&csrf=<?php echo ($_GET["csrf"]); ?>">Confirm</a>
        <a class="btn btn-secondary" href="/admin/settings/mod.php">Cancel</a>
    </div>
    <?php
} elseif (isset($_GET["doUninstallTheme"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission("GET"));
    if (!$csrf) {
        die(getCSRFFailedError());
    }

    if (!themeExists($_GET["doUninstallTheme"])) {
        dsjas_alert("Error", "The theme requested for deletion no longer exists. Was it deleted by another administrator?" .
                    "<br> <a href=\"/admin/settings/mod.php\">Return to theme settings</a>", "danger", false);
        die();
    }

    if ($_GET["doUninstallTheme"] == "default") {
        dsjas_alert("Protected theme", "You are attempting to uninstall the default theme. This is not possible and the operation was cancelled" .
                    "<br> <a href=\"/admin/settings/mod.php\">Return to theme settings</a>", "danger", false);
        die();
    }

    uninstallTheme($_GET["doUninstallTheme"]);
    redirect("/admin/settings/mod.php?uninstalledTheme");
} else {
    header("Location: /admin/settings/mod.php");
}


ob_end_flush(); // Send any remaining content to the browser and disable buffering
