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
