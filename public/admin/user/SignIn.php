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

require ABSPATH . INC . "Stats.php";

require_once ABSPATH . INC . "Users.php";
require_once ABSPATH . INC . "Util.php";


/* Log statistics of page load */
$stats = new Statistics();
$stats->incrementCounterStat("total_page_hits");
$stats->incrementCounterStat("admin_page_hits");

if (isLoggedIn(true)) {
    redirectToLoggedIn(true);
    die();
}


if (shouldAttemptLogin()) {
    $stats->incrementCounterStat("total_signins");
    $stats->incrementCounterStat("admin_signins");

    $stats->stampTimestampStat("last_admin_signin");

    if (canLogin(true)) {
        $result = handleLogin($_POST["username"], $_POST["password"], true);
        if ($result[0]) {
            redirect("/admin/dashboard.php");
        } else {
            // We need to only send proper feedback if it's to
            // show a misc error
            // Otherwise, we are disclosing sensitive info
            if (!$result[0] && $result[1] == -3) {
                redirect("/admin/user/SignIn.php?error=" . $result[1]);
            } else {
                redirect("/admin/user/SignIn.php?error=-1");
            }
        }
        die();
    } else {
        die("The administrator login has been disabled in the site settings");
    }
}

?>

<link rel="stylesheet" href="/include/styles/admin-login.css">

<body class="container-fluid text-center">

    <form class="card form-signin" method="post" , action="/admin/user/SignIn.php">

        <?php if (isset($_GET["logout_success"])) { ?>
            <div class="alert alert-info">
                <p>You have been signed out of your account</p>
            </div>
        <?php }

        if (isset($_GET["post_install"])) { ?>
            <div class="alert alert-info">
                <p>
                    <strong>Thanks for installing</strong> To sign in, please use the credentials you set up in the install process
                </p>
            </div>
            <?php }

        if (isset($_GET["error"])) {
            if ($_GET["error"] == -3) { ?>
                <div class="alert alert-danger">
                    <p><strong>Account disabled</strong> Your account has been disabled by the site administrator and is not accessible</p>
                </div>
            <?php } else { ?>
                <div class="alert alert-danger">
                    <p><strong>Login failure:</strong> Your credentials were incorrect</p>
                </div>
            <?php }
        }

        if (!canLogin(true)) { ?>
            <div class="alert alert-danger">
                <p><strong>Access denied:</strong> The administrator login has been disabled in the site settings</p>
            </div>
        <?php } ?>

        <img class="mb-4 mx-auto rounded-circle" src="/assets/scammer-logo.jpg" alt="" width="100" height="100">
        <h1 class="h3 mb-3 font-weight-normal">Login to DSJAS</h1>

        <label for="inputEmail" class="sr-only">Username</label>
        <input name="username" type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <?php if (canLogin(true)) { ?>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <?php } else { ?>
            <button class="btn btn-lg btn-primary btn-block disabled" type="submit" disabled>Sign in</button>
        <?php } ?>
        <a class="back-home" href="/">Back to home</a>
    </form>
</body>