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

require_once ABSPATH . INC . "Customization.php";

require ABSPATH . INC . "Users.php";
require_once ABSPATH . INC . "Util.php";

require_once ABSPATH . INC . "Theme.php";
require_once ABSPATH . INC . "Module.php";


if (isLoggedIn()) {
    redirectToLoggedIn();
    die();
}

if (isset($_GET["error"])) {
}

if (shouldAttemptLogin()) {
    $success = handleLogin($_POST["username"], $_POST["password"]);
    if ($success[0]) {
        redirect("/user/Dashboard.php");
    } else {
        redirect("/user/Login.php?error=" . $success[1]);
    }
    die();
}

// Jump to main DSJAS load code
dsjas(
    __FILE__,
    "user/",
    function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    },
    "all",
    ["user"]
);
