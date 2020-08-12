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


$randomMinute = rand(1, 59);
$randomHour = rand(0, 23);
$randomDay = rand(0, 364);

define("timeString", "$randomDay days, $randomHour hours and $randomMinute minutes");

// Theme entry point
function getTheme()
{
    ?>

    <body>
        <?php include ABSPATH . getRawThemeContent("DashboardNav.php", "components/"); ?>

        <div class="container">
            <h1>Oh dear!</h1>

            <div class="alert alert-info">
                <p><strong>Offer expired</strong> This offer ended <?php echo (timeString); ?> ago.
                    We're sorry about that, but you might find the next offer just around the corner!</p>
            </div>

            <a href="/">Return home, feeling very sad</a>
        </div>
    </body>

<?php }
