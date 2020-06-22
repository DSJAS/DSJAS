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
require_once(THEME_API . "Appearance.php");

// Theme entry point
function getTheme()
{ ?>

    <body>
        <?php require(ABSPATH . getRawThemeContent("SupportNav.php", "components/")); ?>

        <div class="container mt-4">

            <div class="col-md-4 p-5 boxcol rounded float-right ml-4">
                <div class="row bg-primary rounded text-light mb-4 p-3">
                    <h3>Contact Details</h3>
                    <p>
                        4200 West Fifth Street<br>New Mexico, NM 90071<br>
                    </p>
                    <p><i class="fa fa-phone"></i>
                        <abbr title="Phone">Phone</abbr>: 1 (800) 420-990</p>
                    <p><i class="fa fa-envelope-o"></i>
                        <abbr title="Email">E-mail</abbr>: Contact@<?= getBankURL() ?>
                    </p>
                    <p><i class="fa fa-clock-o"></i>
                        <abbr title="Hours">Hours</abbr>: Mon - Fri: 7:00 AM to 5:00 PM</p>
                </div>

                <div class="row bg-success rounded text-light p-3">
                    <h3>Get premium support</h3>
                    <p class="">Get our premium support package for an increased support duration of half an hour and an hour of technical support</p>

                    <a href="/services/transfer" class="btn btn-primary">Learn more</a>
                </div>

            </div>

            <div class="header mb-4 text-center">
                <svg class="bi bi-check-circle-fill" width="10em" height="10em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>

                <h1 class="display-4">
                    Message submitted
                </h1>

                <h3 class="text-success">Thanks for contacting us!</h3>

                <p class="lead">Our support representatives will be with you shortly. We'll send an email to the provided email with a ticket number. We can then call you or email you again for further messages</p>
            </div>
        </div>
    </body>
<?php }
