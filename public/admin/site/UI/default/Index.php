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
require_once THEME_API . "Accounts.php";
require_once THEME_API . "Appearance.php";

setTitle("Welcome to " . getBankName());

updateStatistic("Homepage hits", 0, STATISTICS_TYPE_COUNTER, "Page hits");

// Theme entry point
function getTheme()
{
?>

    <body>

        <?php include "components/Nav.php"; ?>

        <?php
        addModuleDescriptor("nav_bar");

        if (shouldAppearLoggedIn()) { ?>
            <div class="justify-right form-inline">
                <a class="btn btn-outline-primary" href="/user/Dashboard.php" style="margin-right: 25px">Go to my dashboard</a>
                <a class="btn btn-outline-secondary" href="/user/Logout.php">Logout</a>
            </div>
        <?php } else { ?>
            <div class="justify-right form-inline">
                <a class="btn btn-outline-primary" href="/user/Login.php" style="margin-right: 25px">Login</a>
                <a class="btn btn-outline-success" href="/user/Apply.php">Apply now</a>
            </div>
        <?php } ?>
        </nav>


        <div class="container-fluid main-page-teaser">

            <?php addModuleDescriptor("alert_area");  ?>

            <div class="main-page-teaser-overlay rounded">
                <br>
                <br>
                <h3><strong>Your family is as close to us as they are to you</strong></h3>

                <h5>In celebration of all those happy families out there, we're making you even happier, with discounts and deals for families like you</h5>
                <br>
                <p>We're teaming up with Chade Bank to help out all you families out there, putting discounts and deals on some of our best products</p>
                <p>For a limited time only, we're giving families loans at 0% APR and completely free shared accounts. Apply today and don't miss out. Go on, you deserve it mum and dad!</p>

                <br>
                <a href="/user/Apply.php" class="btn btn-primary">Apply today</a>
                <pre class="lead small" style="margin-top: 10; color: white">Terms and conditions apply. While event lasts</pre>
            </div>
        </div>

        <div class="container-fluid mt-2">
            <div class="card-deck services-panels">
                <div class="card text-light bg-dark">
                    <img class="card-img-top mx-auto d-block" src="/assets/account-icon.jpg">
                    <h5 class="card-header text-center">Current accounts</h5>
                    <div class="card-body">
                        <p class="card-title"><strong>Award winning current accounts with you at the center of our interests</strong></p>
                        <div class="card-text">
                            <p>Many banks put their own gain at the center of your account. Not here. Our philosophy is that your gain is ours, and stick to that. Put your money where you want it and not where you think you need it.</p>
                        </div>
                        <a class="btn btn-primary" href="/user/Apply">Get started</a>
                    </div>
                </div>
                <div class="card text-light bg-dark">
                    <img class="card-img-top mx-auto d-block" src="/assets/savings-icon.jpg">
                    <h5 class="card-header text-center">Savings Accounts</h5>
                    <div class="card-body">
                        <p class="card-title"><strong>Savings accounts that won't let you down, with our savings guarantee</strong></p>
                        <div class="card-text">
                            <p>Think of us as a piggy bank, just a lot larger. And safer. And less pink. We're committed to keeping your money just as safe as if it was back there, in your little piggy bank.</p>
                        </div>
                        <a class="btn btn-primary" href="/user/Apply">Get started</a>
                    </div>
                </div>
                <div class="card text-light bg-dark">
                    <img class="card-img-top mx-auto d-block" src="/assets/loan-icon.jpg">
                    <h5 class="card-header text-center">Loans and Leases</h5>
                    <div class="card-body">
                        <p class="card-title"><strong>Sometimes, everyone needs a bail-out, pick-me-up or something to support them. We're here to provide for you</strong></p>
                        <div class="card-text">
                            <p>With decades of lending experience, we're here to get you through that difficult repair bill or emergency breakdown until payday.</p>
                        </div>
                        <a class="btn btn-primary" href="/user/Apply">Get started</a>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="jumbotron-fluid text-center m-3">
            <div class="row">
                <div class="col border-left p-3">
                    <h1><span class="badge badge-primary">98%</span></h1>
                    <h3><span class="badge badge-secondary">of customers recommend our services</span></h3>
                </div>
                <div class="col border-left p-3">
                    <h1><span class="badge badge-primary">$10 billion</strong></h1>
                    <h3><span class="badge badge-secondary">of funds in our care</span></h3>
                </div>
                <div class="col border-left p-3">
                    <h1><span class="badge badge-primary">100+</span></h1>
                    <h3><span class="badge badge-secondary">branches worldwide</span></h3>
                </div>
            </div>
        </div>

        <hr>

        <div class="container-fluid bg-dark">
            <div class="row">
                <div class="col text-light int-investment">
                    <h3>International investment opportunities <span class="badge badge-primary">New</span></h3>
                    <p class="lead">Invest now and get in on markets never seen before to the regular investor with our new international investment plan</p>
                    <a class="btn btn-primary" href="/services/transfer">Get started</a>
                    <pre class="lead small" style="margin-top: 20px; color: white">Terms and conditions apply. External markets not guaranteed to be profitable</pre>
                </div>
                <div class="col text-light online-banking">
                    <h3>Industry leading online banking services <span class="badge badge-primary">New</span></h3>
                    <p class="lead">Productivity at its max with industry leading online banking services. Try them out and see why other banks are getting left behind</p>
                    <a class="btn btn-primary" href="/services/current">Get started</a>
                    <pre class="lead small" style="margin-top: 20px; color: white">Terms and conditions apply. Selected accounts only</pre>
                </div>
            </div>
        </div>

        <div class="container-fluid bg-dark corporation">
            <div class="row">
                <div class="col text-light">
                    <h3>Banking for corporations <span class="badge badge-success">Exclusive</span></h3>
                    <p class="lead">Special perks, such as dedicated account managers and special offers on products, can be obtained with our exclusive business account for corporations. Switch to us today for a free stock-market trading advisory</p>
                    <a class="btn btn-success" href="/services/shared">Tell me more</a>
                </div>
            </div>
            <hr class="bg-dark">
            <div class="row">
                <div class="col text-light">
                    <h3>Banking for small businesses</h3>
                    <p class="lead">It's a hard world for small businesses: getting picked on by the big guy, getting ripped off by big banks. No more, join us and get 50% off most financial products and a dedicated account manager</p>
                    <a class="btn btn-success" href="/services/shared">Tell me more</a>
                </div>
            </div>
            <hr class="bg-dark">
            <div class="row">
                <div class="col text-light">
                    <h3>Banking for start-ups</h3>
                    <p class="lead">When we work together, everyone succeeds. Get exclusive benefits for start-ups and self-employed businesses. We're helping the next generation of businesses one step at a time.</p>
                    <a class="btn btn-success" href="/CPRewards">Tell me more</a>
                </div>
            </div>

            <div class="row text-light">
                <pre class="lead small" style="margin-top: 40px; color: white">Terms and conditions apply</pre>
            </div>
        </div>

        <?php require ABSPATH . getRawThemeContent("Footer.php", "components/"); ?>

    </body>

<?php }
