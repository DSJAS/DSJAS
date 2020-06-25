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
                            <a href="/">Home</a>
                        </li>
                        <li class="nav-link">
                            <a href="about">About <?php echo getBankName(); ?></a>
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

            <!-- Service List -->
            <!-- The circle icons use Font Awesome's stacked icon classes. For more information, visit http://fontawesome.io/examples/ -->
            <div class="row p-5 boxcol rounded ">
                <div class="col-lg-12">
                    <h2 class="page-header">Where we invest your money</h2>
                    <hr>
                </div>

                <div class="col-md-4">
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-car fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>Automotives</h4>
                            <p>Everyone has a car so it's a no brainer to invest here.</p>
                        </div>
                    </div>
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>FaceBook</h4>
                            <p>We own basically all outstanding shares of FaceBook.</p>
                        </div>
                    </div>
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-bank fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>Banking</h4>
                            <p>We like to gamble your money with our friends at other banks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-wheelchair fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>Handicapped</h4>
                            <p>Handicapped, often referred to as technical support scammers.</p>
                        </div>
                    </div>
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-tree fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>Environment</h4>
                            <p>The environment keeps us alive, so invest in yourself.</p>
                        </div>
                    </div>
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-apple fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>Apple</h4>
                            <p>We helped Steve Jobs fund Apple with your money.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-paper-plane fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>Paper Planes</h4>
                            <p>Paper planes are often an undervalued sector.</p>
                        </div>
                    </div>
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-space-shuttle fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>NASA</h4>
                            <p>We funded the first ever trip to the moon in 1969.</p>
                        </div>
                    </div>
                    <div class="media">
                        <div class="pull-left">
                            <span class="fa-stack fa-2x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-bitcoin fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h4>Bitcoin</h4>
                            <p>How else are you going to get yourself a lamborghini?</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <br>
        <footer>
            <p>Deposit products offered by <?php echo getBankName(); ?>, N.A. Member FDIC.</p>
            <p>Copyright &copy; 1969 - 2019 <?php echo getBankName() ?>. All rights reserved. NMLSR ID 420024</p>
        </footer>
    </body>
<?php }
