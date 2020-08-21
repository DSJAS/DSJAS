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

require "include/Bootstrap.php";

require ABSPATH . INC . "DSJAS.php";

require_once ABSPATH . INC . "vendor/hooks/src/gburtini/Hooks/Hooks.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Theme.php";
require_once ABSPATH . INC . "Module.php";


$url = $_SERVER["REQUEST_URI"];

if (shouldRedirectToReal($url)) {
    redirectToReal($url);
    die();
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
    $page,
    $dir,
    function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    }
);
