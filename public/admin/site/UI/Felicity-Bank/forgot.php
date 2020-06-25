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
        <nav class="navbar navbar-expand-sm navbar-light bg-light border" style="height: 8vh;">
            <div class="container">
                <a href="#" class="navbar-brand"><?php echo getBankName(); ?></a>

                <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMenu">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-link">
                            <a href="/">Home</a>
                        </li>
                        <li class="nav-link">
                            <a href="/about">About <?php echo getBankName(); ?></a>
                        </li>
                        <li class="nav-link">
                            <a href="/invest">Investing</a>
                        </li>
                        <li class="nav-link">
                            <a href="https://en.wikipedia.org/wiki/Technical_support_scam" target="_blank">Report Fraud</a>
                        </li>
                        <li class="nav-link">
                            <a href="/contact">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div style="height: 8vh;"></div>

        <!-- in between the nav and login box -->

        <div class="container">
            <div class="page-header"></div>

            <center>
                <div class="shadow-sm p-3 mb-5 boxcol rounded col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h2>Account Reset for <?php echo getBankName(); ?></h2>
                    <p style="font-size: 22px;">
                        Forgot your username or password?
                    </p>
                    <p style="font-size: 18px;">
                        Enter your email below to receive your account username and create a new password:
                    </p>
                    <hr />

                    <form>
                        <div class="form-group">
                            <input type="Email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email Address" style="width: 48%;" />
                        </div>

                        <a href="/sent" class="btn btn-primary">Reset</a>
                    </form>

                    <div style="height: 3vh;"></div>

                    <div style="height: 1vh;"></div>
                </div>
        </div>

        <footer>
            <p>Deposit products offered by <?php echo getBankName(); ?>, N.A. Member FDIC.</p>
            <p>Copyright &copy; 1969 - 2019 <?php echo getBankName(); ?>. All rights reserved. NMLSR ID 420024</p>
        </footer>
    </body>
<?php }
