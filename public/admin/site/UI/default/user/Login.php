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

/*
    DEFAULT THEME - DSJAS
    =====================

    This is the theming files included in the default installation of DSJAS.
    It contains HTML and PHP files required to load and display the default theme.

    This file should never be accessed directly, and instead should only be
    required by a file which has already bootstrapped the site.
    This means that your script must have defined the ABSPATH constants
    and preformed other required bootstrapping tasks before the page
    can be displayed.


    For more information of theming and creating your own themes, please refer to the
    API documentation for themes and plugins.
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
            <input name="username" type="text" autocomplete="off" id="inputUsername" class="form-control" placeholder="Username" required autofocus>

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
