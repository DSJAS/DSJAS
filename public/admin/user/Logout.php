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

require "../../include/Bootstrap.php";

require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Util.php";


if (!shouldAttemptLogout(true)) {
    if (!isset($_GET["success"])) {
        redirect("/admin/user/SignIn.php?signout_fail=1");
        die();
    }
}

if (isset($_GET["success"]) && $_GET["success"]) {
    redirect("/admin/user/SignIn.php?logout_success=1");
    die();
}

// If we've been told to log out immediately, do it now
if (isset($_GET["logout"]) && $_GET["logout"]) {
    logout(true);
    redirect("/admin/user/Logout.php?success=true");
    die();
}

?>

<body>
    <p style="text-align: left">One moment, you're being signed out...</p>

    <script>
        console.log("You will be signed out in around 5 seconds, please wait...");

        setTimeout(function() {
            document.clear();
            document.writeln("Signing out now...");
            document.location = "/admin/user/Logout.php?logout=true"
        }, 750)
    </script>
</body>