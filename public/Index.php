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

require "include/Bootstrap.php";

require ABSPATH . INC . "DSJAS.php";

require_once ABSPATH . INC . "vendor/hooks/src/gburtini/Hooks/Hooks.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Theme.php";
require_once ABSPATH . INC . "Module.php";


$url = $_SERVER["REQUEST_URI"];

if (shouldRedirectToReal($url)) {
    redirectToReal($url);
}

$splitUrl = explode("?", $url);

if (count($splitUrl) > 1) {
    fixGetHeaders($splitUrl[1]);
}

$usableUrl = stripGetHeaders($url);


if (shouldProcessPermalink()) {
    $info = processPermalink($usableUrl);

    $page = $info[0];
    $dir = $info[1];
} else {
    $dir = "/";
    $page = __FILE__;
}

// Jump to main DSJAS load code
dsjas(
    $page, $dir, function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    }
);
