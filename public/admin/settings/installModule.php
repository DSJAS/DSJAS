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

require("../AdminBootstrap.php");

require(ABSPATH . INC . "csrf.php");
require(ABSPATH . INC . "Administration.php");

require_once(ABSPATH . INC . "Customization.php");

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
}
