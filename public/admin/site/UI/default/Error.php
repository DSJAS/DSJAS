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
require_once THEME_API . "Error.php";


function getTheme()
{
    ?>

    <body>

        <?php include "components/Nav.php"; ?>

        </nav>

        <div class="container-fluid text-center">
            <?php addModuleDescriptor("alert_area");  ?>

            <img src="/assets/warning.png" style="height: 170; width: 200">
            <h1>Code <?php echo (getErrorCode()); ?></h1>
            <h3 class="text-dark">That's an error</h3>
            <br>
            <p>There was a problem while attempting to navigate to that page. Please try again and make sure you got the URL correct</p>

            <?php addModuleDescriptor("error_content");  ?>

            <span>
                <a class="btn btn-primary" href="/">Go to the Homepage</a>
            </span>

            <?php addModuleDescriptor("error_footer");  ?>
        </div>
    </body>
<?php }
