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

require(ABSPATH . INC . "Theme.php");
require_once(ABSPATH . INC . "Customization.php");

require(ABSPATH . INC . "Users.php");
require(ABSPATH . INC . "Banking.php");

require_once(ABSPATH . INC . "Util.php");

require(ABSPATH . INC . "csrf.php");

require(ABSPATH . INC . "Module.php");


$moduleManager = new ModuleManager("transfer");

$moduleCallbackFunction = function (string $callbackName) {
    global $moduleManager;
    $moduleManager->getAllByCallback($callbackName);
};

$moduleManager->processModules($moduleCallbackFunction);

\gburtini\Hooks\Hooks::run("module_hook_event", ["all"]);
\gburtini\Hooks\Hooks::run("module_hook_event", ["user"]);


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

$config = new Configuration(false, true, false, false);

if (!$config->getKey(ID_THEME_CONFIG, "config", "use_default")) {
    $currentTheme = $config->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
} else {
    $currentTheme = "default";
}

$theme = new Theme(__FILE__, "user/", $currentTheme);
$theme->loadTheme();
$theme->displayTheme();
