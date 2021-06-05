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

updateStatistic("Visits to support", 0, STATISTICS_TYPE_COUNTER, "Support");

// Theme entry point
function getTheme()
{
?>

    <body class="bg-light">
        <?php include ABSPATH . getRawThemeContent("SupportNav.php", "components/"); ?>
    </body>

    <div class="container-fluid support-page-teaser">
        <div class="support-page-teaser-overlay rounded">
            <h1>Help and Support</h1>
            <br>
            <h2>We're here to support you, no matter what</h2>

            <small class="text-small">Excludes events such as global pandemics, mass extinctions and meteor strikes</small>
        </div>

        <div class="card-deck mt-3 text-dark">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Online Banking</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Online banking doesn't have to be hard with <?php echo (getBankName()); ?>. Get help with your online banking and more with us</h6>
                    <p class="card-text">We know that online banking can be confusing. Those damn millennials made it so! So, it's our job to stop the confusion and make online banking great again!</p>
                    <a href="/support/Online" class="card-link">Get help</a>
                    <a href="/support/Contact" class="card-link">Speak to support</a>
                </div>
            </div>
            <div class="card" style="width: 18rem">
                <div class="card-body">
                    <h5 class="card-title">Financial support</h5>
                    <h6 class="card-subtitle mb-2 text-muted">We understand that you're probably not an economist. Unless you are. That's awkward...</h6>
                    <p class="card-text">Finances are hard. We get it. But we also have an advanced degree in economics and business - and you don't. Do take it from us, we can help you out.</p>
                    <a href="/support/Finances" class="card-link">Get help</a>
                    <a href="/support/Contact" class="card-link">Speak to support</a>
                </div>
            </div>
        </div>

        <div class="card-deck mt-3 text-dark">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Security and fraud</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Fraud is everywhere. Get right and spot it now with our easy and quick guides and resources</h6>
                    <p class="card-text">We provide several resources in order to crack down on fraud and scams. It's time we beat these guys for good.</p>
                    <a href="https://www.actionfraud.police.uk/" class="card-link">More info</a>
                    <a href="/support/Fraud" class="card-link">Report fraud</a>
                </div>
            </div>
            <div class="card" style="width: 18rem">
                <div class="card-body">
                    <h5 class="card-title">Debt management</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Too badly in debt, there are some things we can do to help you out</h6>
                    <p class="card-text">With our all new "Earn quick" scheme, you can do several things to pay off your debt with us. For example, you can rent out your computer to mine bitcoin or give us your data to sell to Google. Easy and free ways to pay off debt, quick!</p>
                    <a href="/support/Finances" class="card-link">Tell me more</a>
                    <a href="/support/Contact" class="card-link">Speak to support</a>
                </div>
            </div>
        </div>
    </div>

    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center mt-4">
            <a href="/support/Online">Get help with logging in to and managing your account</a>
            <a href="/support/Online" class="badge badge-primary badge-pill">
                <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                </svg>
            </a>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="/support/Transfer">Get help reversing a transfer</a>
            <a href="/support/Transfer" class="badge badge-primary badge-pill">
                <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                </svg>
            </a>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="/support/Contact">Get help with meeting in person</a>
            <a href="/support/Contact" class="badge badge-primary badge-pill">
                <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                </svg>
            </a>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="/support/Fraud">Get help with lost or stolen cards</a>
            <a href="/support/Fraud" class="badge badge-primary badge-pill">
                <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                </svg>
            </a>
        </li>
    </ul>

    <?php require ABSPATH . getRawThemeContent("Footer.php", "components/"); ?>
<?php }
