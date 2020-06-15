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

require("../include/Bootstrap.php");

require(ABSPATH . INC . "DSJAS.php");

require(ABSPATH . INC . "Banking.php");
require(ABSPATH . INC . "csrf.php");

require_once(ABSPATH . INC . "Customization.php");
require_once(ABSPATH . INC . "Users.php");
require_once(ABSPATH . INC . "Util.php");

require_once(ABSPATH . INC . "Theme.php");
require_once(ABSPATH . INC . "Module.php");


if (isset($_GET["performTransfer"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission($method = "get"));
    if (!$csrf) {
        die(getCSRFFailedError());
    }

    if (!isset($_GET["amount"]) || !isset($_GET["originAccount"]) || !isset($_GET["destinationAccount"])) {
        header("Location: /user/transfer.php?transferError=1");
        die();
    }

    if ($_GET["amount"] == null || $_GET["originAccount"] == null || $_GET["destinationAccount"] == null) {
        header("Location: /user/transfer.php?transferError=1");
        die();
    }

    if (!userOwnsAccount($_GET["originAccount"], getCurrentUserId())) {
        header("Location: /user/transfer.php?transferError=1");
        die();
    }

    if ($_GET["amount"] < 0) {
        header("Location: /user/transfer.php?transferError=1");
        die();
    }

    if (isset($_GET["description"]) && $_GET["description"] != null) {
        echo ("Description provided!");
        performTransaction($_GET["originAccount"], $_GET["destinationAccount"], $_GET["amount"], $_GET["description"]);
    } else {
        performTransaction($_GET["originAccount"], $_GET["destinationAccount"], $_GET["amount"]);
    }

    header("Location: /user/transfer.php?transferSuccess=1&originAccount=" . $_GET["originAccount"] . "&amount=" . $_GET["amount"]);
    die();
} else {
    regenerateCSRF();
}


if (!isLoggedIn()) {
    redirect("/user/Login.php");
    die();
}


// Jump to main DSJAS load code
dsjas(__FILE__, "user/", function (string $callbackName, ModuleManager $moduleManager) {
    $moduleManager->getAllByCallback($callbackName);
}, "all", ["user"]);
