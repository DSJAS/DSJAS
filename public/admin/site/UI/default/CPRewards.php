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

require_once(THEME_API . "General.php");
require_once(THEME_API . "Accounts.php");


$randomMinute = rand(1, 59);
$randomHour = rand(0, 23);
$randomDay = rand(0, 364);

define("timeString", "$randomDay days, $randomHour hours and $randomMinute minutes");

// Theme entry point
function getTheme()
{ ?>

    <body>
        <?php require(ABSPATH . getRawThemeContent("Nav.php", "Components/")); ?> </nav>

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
