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