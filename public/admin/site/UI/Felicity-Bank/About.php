<?php

/*
FELICITY BANK PORT - DSJAS
==========================

Welcome to the DSJAS theme port of Felicity Bank!

Felicity bank is an open source and free template for making
fake banking sites. This port is an exact replica of the site
ported to the DSJAS theme API, allowing for dynamically changing
the name, branding etc.

Almost nothing has been changed about the original, but some features
had to be removed due to their conflict with existing modules or
other plugins.

Please check out the Felicity Bank GitHub page here: https://github.com/0xB9/Felicity-Bank-Inc.


For more information of theming and creating your own themes, please refer to the
API documentation for themes and plugins.
*/

require_once THEME_API . "General.php";
require THEME_API . "Accounts.php";

function getTheme()
{
    ?>

    <body>
        <!--[if lt IE 7]>
      <p class="browsehappy">
        You are using an <strong>outdated</strong> browser. Please
        <a href="#">upgrade your browser</a> to improve your experience.
      </p>
    <![endif]-->

        <?php include ABSPATH . getRawThemeContent("Nav.php", "components/"); ?>

        <!-- in between the nav and login box -->

        <div class="container">
            <div class="page-header"></div>

            <center>
                <div class="shadow-sm p-3 mb-5 boxcol rounded col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <h2>About <?php echo getBankName() ?></h2>

                    <hr>

                    <p style="font-size: 22px;">
                        Our enduring vision is to help our customers succeed financially.
                    </p>

                    <br>

                    <p style="font-size: 18px;">
                        We regularly monitor our customers accounts of any suspicious activity to catch it before hand.
                    </p>

                    <hr />

                </div>
            </center>
        </div>
        <footer>
            <p>Deposit products offered by <?php echo getBankName() ?>, N.A. Member FDIC.</p>
            <p>Copyright &copy; 1969 - 2019 <?php echo getBankName() ?>. All rights reserved. NMLSR ID 420024</p>
        </footer>
    </body>
<?php }
