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
