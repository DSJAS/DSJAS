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
require THEME_API . "Accounts.php";

updateStatistic("Applications rejected", 0, STATISTICS_TYPE_COUNTER, "Accounts and logins");

// Theme entry point
function getTheme()
{
?>

    <body>
        <?php
        if (shouldAppearLoggedIn()) {
            include ABSPATH . getRawThemeContent("DashboardNav.php", "components/");
        } else {
            include ABSPATH . getRawThemeContent("Nav.php", "components/"); ?> </nav>
        <?php } ?>


        <div class="container">
            <h1>Technical fault: Online applications unavailable</h1>

            <div class="alert alert-danger">
                <p><strong>Due to an ongoing issue, online applications have been disabled</strong>
                    We're sorry, but due to a technical fault, online applications have been temporarily disabled.
                    If you really need to apply, please contact us on our helpline or support.

                    You can still apply in a branch! Please do that if you need an account.

                    <i>Service should be restored within two business days</i>
                </p>
            </div>

            <a href="/">Return home</a>
        </div>
    </body>
<?php }
