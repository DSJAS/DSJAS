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
require_once THEME_API . "Accounts.php";

// Theme entry point
function getTheme()
{
    ?>

    <body class="container-fluid" style="text-align: center">

        <?php
        // If we want the success page, display that now
        if (shouldProvideLogoutFeedback() && getLogoutFeedback() == LOGOUT_API_SUCCESS) {
            header("Location: /user/Login.php?signedout");
            die();
        } ?>

            <p style="text-align: left">One moment, you're being signed out...</p>

            <script>
                console.log("You will be signed out in around 5 seconds, please wait...");

                setTimeout(function() {
                    document.clear();
                    document.writeln("Signing out now...");
                    document.location = "/user/Logout.php?logout=true"
                }, 750)
            </script>

    </body>
<?php }