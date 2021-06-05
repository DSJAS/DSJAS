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

updateStatistic("Contact support visited", 0, STATISTICS_TYPE_COUNTER, "Support");

// Theme entry point
function getTheme()
{
?>

    <body>
        <?php include ABSPATH . getRawThemeContent("SupportNav.php", "components/"); ?>

        <div class="container mt-4">

            <div class="col-md-4 p-5 boxcol rounded float-right ml-4">
                <div class="row bg-primary rounded text-light mb-4 p-3">
                    <h3>Contact Details</h3>
                    <p>
                        4200 West Fifth Street<br>New Mexico, NM 90071<br>
                    </p>
                    <p><i class="fa fa-phone"></i>
                        <abbr title="Phone">Phone</abbr>: 1 (800) 420-990
                    </p>
                    <p><i class="fa fa-envelope-o"></i>
                        <abbr title="Email">E-mail</abbr>: Contact@<?php echo getBankURL() ?>
                    </p>
                    <p><i class="fa fa-clock-o"></i>
                        <abbr title="Hours">Hours</abbr>: Mon - Fri: 7:00 AM to 5:00 PM
                    </p>
                </div>

                <div class="row bg-success rounded text-light p-3">
                    <h3>Get premium support</h3>
                    <p class="">Get our premium support package for an increased support duration of half an hour and an hour of technical support</p>

                    <a href="/services/transfer" class="btn btn-primary">Learn more</a>
                </div>

            </div>

            <div class="header mb-4">
                <h1 class="display-4">
                    Contact <?php echo getBankName() ?>
                </h1>

                <p class="lead">We're always standing by for communications. Please fill in the details below, along with your message, and we'll get back to you as soon as possible</p>
            </div>

            <div class="row">
                <div class="col-md-18">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="control-group form-group">
                            <div class="controls">
                                <label>Full Name:</label>
                                <input type="text" class="form-control" id="name" required data-validation-required-message="Please enter your name.">
                                <p class="help-block"></p>
                            </div>
                        </div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <label>Phone Number:</label>
                                <input type="tel" class="form-control" id="phone" required data-validation-required-message="Please enter your phone number.">
                            </div>
                        </div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <label>Email Address:</label>
                                <input type="email" class="form-control" id="email" required data-validation-required-message="Please enter your email address.">
                            </div>
                        </div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <label>Message:</label>
                                <textarea rows="10" cols="100" class="form-control" id="message" required data-validation-required-message="Please enter your message" maxlength="999" style="resize:none"></textarea>
                            </div>
                        </div>
                        <div id="success"></div>
                        <a href="/support/ContactReceived" type="button" class="btn btn-success">Submit message</a>

                        <br>
                        <small class="text-muted">Your data is completely safe and secure. We will only share limited data with Facebook, Google, Amazon, Western Union and Twitter as outlined in <a href="/Mission">our policies</a></small>
                    </form>
                </div>
            </div>
        </div>
    </body>
<?php }
