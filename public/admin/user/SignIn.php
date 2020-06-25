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

require "../../include/Bootstrap.php";

require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Util.php";

if (isLoggedIn(true)) {
    redirectToLoggedIn(true);
}

if (shouldAttemptLogin()) {
    if (canLogin(true)) {
        $result = handleLogin($_POST["username"], $_POST["password"], true);
        if ($result[0]) {
            redirect("/admin/dashboard.php");
        } else {
            redirect("/admin/user/SignIn.php?error=1");
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

        if (isset($_GET["error"])) { ?>
            <div class="alert alert-danger">
                <p><strong>Login failure:</strong> Your credentials were incorrect</p>
            </div>
        <?php }

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