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

/*
    THEMING API
    ===========

    This file contains the functions and APIs required to write a theme
    for DSJAS.

    It does nothing on its own, but does provide useful utility functions
    for theming scripts and provides a way for a theme to be consistent
    in behaviour to the rest of the site.

    For more information on the theming API, please refer to the API
    documentation.

*/

require_once THEME_API . "General.php";
require_once THEME_API . "Appearance.php";
require THEME_API . "Accounts.php";

// Theme entry point
function getTheme()
{
    ?>

    <body class="body-signin bg-img-login">

        <form class="form-signin rounded" id="loginForm" method="post" action="/user/Login.php">

            <?php
            if (shouldProvideLoginFeedback()) { ?>
                <div class="alert alert-danger" role="alert">
                    <p><strong><?php echo (getLoginErrorTitle()); ?></strong> <?php echo (getLoginErrorMsg()); ?></p>
                </div>
                <?php
            } elseif (shouldProvideLogoutFeedback() && getLogoutFeedback() == LOGOUT_API_FAILURE) { ?>
                <div class="alert alert-info" role="alert">
                    <p><strong>Failed to sign out</strong> Please sign in before you sign out</p>
                </div>
            <?php }

            addModuleDescriptor("alert_area");  ?>

            <img class="mb-4" src="/assets/logo.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Welcome back!</h1>

            <?php addModuleDescriptor("login_box_content");  ?>

            <label for="inputEmail" class="sr-only">Username</label>
            <input name="username" type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus>

            <label for="inputPassword" class="sr-only">Password</label>
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>

            <p class="mb-3 text-muted">Not yet a member? <a href="/user/Apply">Apply now</a></p>

            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

            <?php addModuleDescriptor("login_box_post_content");  ?>

            <p class="mt-5 mb-3 text-muted">Be secure: Never give out your login details <strong>to anybody</strong></p>

            <?php addModuleDescriptor("login_footer");  ?>
        </form>

    </body>
<?php }
