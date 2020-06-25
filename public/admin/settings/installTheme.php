<?php

/**
 * Welcome to Dave-Smith Johnson & Son family bank!
 * 
 * This is a tool to assist with scam baiting, especially with scammers attempting to
 * obtain bank information or to attempt to scam you into giving money.
 * 
 * This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
 * is free to use and change for all users. Scam bait as much as you want!
 * 
 * This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
 * I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!
 * 
 * Please, waste these people's time as much as possible. It's fun and it does good for everyone.
 */

require "../AdminBootstrap.php";

require ABSPATH . INC . "csrf.php";
require ABSPATH . INC . "Administration.php";

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

    if (!themeExists($_GET["doUninstallTheme"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Error</strong> You are attempting to uninstall a theme which does not exist. It may already have been uninstalled or another administrator may have uninstalled it.</p>
            <a href="/admin/settings/mod.php">Go back to themes settings</a>
        </div>
    <?php
        die();
    }

    if ($_GET["doUninstallTheme"] == "default") { ?>
        <div class="alert alert-danger">
            <p><strong>Protected theme</strong> You are attempting to uninstall the default theme. This is not possible and the operation was cancelled</p>
            <a href="/admin/settings/mod.php">Go back to themes settings</a>
        </div>
<?php
        die();
    }

    uninstallTheme($_GET["doUninstallTheme"]);
    redirect("/admin/settings/mod.php?uninstalledTheme");
} else {
    header("Location: /admin/settings/mod.php");
}


ob_end_flush(); // Send any remaining content to the browser and disable buffering
