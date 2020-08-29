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

require "../include/Bootstrap.php";

require ABSPATH . INC . "DSJAS.php";

require_once ABSPATH . INC . "vendor/hooks/src/gburtini/Hooks/Hooks.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Theme.php";
require_once ABSPATH . INC . "Module.php";


if (!isset($_GET["code"]) || $_GET["code"] == "") {
    $code = "404";
} else {
    $code = (int)$_GET["code"];
}

http_response_code($code);


// Jump to main DSJAS load code
dsjas(
    __FILE__,
    "/",
    function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    },
    "all",
    ["error"]
);
