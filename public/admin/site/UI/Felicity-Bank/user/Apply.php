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
        <nav class="navbar navbar-expand-sm navbar-light bg-light border" style="height: 8vh;">
            <div class="container">
                <a href="#" class="navbar-brand"><?php echo getBankName() ?></a>

                <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMenu">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-link">
                            <a href="about">About <?php echo getBankName() ?></a>
                        </li>
                        <li class="nav-link">
                            <a href="invest">Investing</a>
                        </li>
                        <li class="nav-link">
                            <a href="https://en.wikipedia.org/wiki/Technical_support_scam" target="_blank">Report Fraud</a>
                        </li>
                        <li class="nav-link">
                            <a href="contact">Contact</a>
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
                    <h2>Apply for <?php echo getBankName() ?></h2>
                    <p style="font-size: 22px;">
                        Thank you for choosing us!
                    </p>
                    <p style="font-size: 18px;">
                        To get started using our award-winning online banking system please simply
                        sign up and create your account below:
                    </p>
                    <hr />

                    <form>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="name" class="form-control" id="exampleInputEmail1" aria-describedby="nameHelp" placeholder="Full Name" style="width: 48%;" />
                            <label>Email Address</label>
                            <input type="Email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email Address" style="width: 48%;" />
                            <label>Username</label>
                            <input type="username" class="form-control" id="exampleInputuser1" aria-describedby="userHelp" placeholder="Username" style="width: 48%;" />
                            <label>Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" style="width: 48%;" />
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Confirm Password" style="width: 48%;" />
                        </div>

                        <a href="/user/Complete" class="btn btn-primary">Complete</a>
                    </form>

                    <div style="height: 3vh;"></div>

                    <a href="/user/Login">Already have an account?</a>
                    <div style="height: 1vh;"></div>
                </div>
        </div>

        <footer>
            <p>Deposit products offered by <?php echo getBankName(); ?>, N.A. Member FDIC.</p>
            <p>Copyright &copy; 1969 - 2019 <?php echo getBankName(); ?>. All rights reserved. NMLSR ID 420024</p>
        </footer>
<?php }
