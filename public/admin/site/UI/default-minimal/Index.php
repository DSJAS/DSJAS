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
require_once THEME_API . "Appearance.php";

// Theme entry point
function getTheme()
{
    ?>

    <body class="body-signin bg-img-login">

        <div class="form-signin rounded">

            <?php if (shouldAppearLoggedIn()) { ?>
                <div class="alert alert-success" role="alert">
                    <p><strong>You're already logged in!</strong> You can access your banking dashboard <a href="/user/Dashboard.php">here</a></p>
                </div>
            <?php }

            addModuleDescriptor("alert_area");  ?>

            <img class="mb-4" src="/assets/logo.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Welcome to <?php echo (getBankName()); ?> online</h1>

            <p><strong>What do you wish to do today?</strong> Please select one of the options below</p>

            <a href="/user/Login.php" class="btn btn-primary action-buttons">Access online banking</a>
            <a href="/user/Apply.php" class="btn btn-success action-buttons">Apply for one of our services</a>
        </div>

    </body>

<?php }
