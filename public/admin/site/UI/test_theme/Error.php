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

// Theme entry point
function getTheme()
{
    ?>
    <div class="alert alert-danger">
        <strong>An error occurred, apparently</strong> An error has ocurred, either because you went to a page that doesn't exist or this theme just doesn't implement it. As I said, this theme is less than functional.
    </div>

    <div class="jumbotron">
        <h1 class="display-4">Welcome to the development version of DSJAS!</h1>
        <p class="lead">This theme is only shipped with the development version of DSJAS. So, because you have it, you must have a development version!</p>
        <hr class="my-4">
        <p>This theme doesn't actually contain any content and is just a template for showing that your install is working.
            Have fun contributing, writing a theme, writing a module or just taking a look at the code!
        </p>
        <a class="btn btn-primary btn-lg" href="https://github.com/OverEngineeredCode/DSJAS" role="button">Developer documentation</a>

        <br>
        <br>
        <small class="text-muted">You might want to re-enable the default theme now. This one is less than functional ;-)</small>
    </div>
<?php }
