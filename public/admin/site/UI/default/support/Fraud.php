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

// Theme entry point
function getTheme()
{
    ?>

    <body>
        <?php include ABSPATH . getRawThemeContent("SupportNav.php", "components/"); ?>

        <div class="alert alert-warning">
            <strong>ATTENTION: Security alert</strong> The <?php echo getBankName() ?> Fraud Prevention Department has been made aware of a large amount of technical support and refund scams circulating among customers.
            If <strong>anybody</strong> asks for your login details or for you to sign in to your account, it may be a scam.
            Please learn more <a href="https://www.actionfraud.police.uk/">here</a>.
        </div>

        <div class="container">
            <div class="header mb-4">
                <h1 class="display-4">
                    Fraud prevention and cyber crime
                </h1>

                <p class="lead"><?php echo getBankName() ?> is committed to preventing cyber crime and helping people like you keep your money safe from fraudsters.</p>
            </div>

            <ul>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#commonFraud" data-toggle="collapse">Common types of fraud</a>
                    <a href="#commonFraud" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="commonFraud">
                        <div class="card card-body">
                            <h4>Fraud is everywhere, here are the most common types:</h4>

                            <ul>
                                <li><strong>Technical support scam</strong> Somebody claiming to be from a tech company will contact you pretending that you have a problem that needs fixing and need to charge you to fix it</li>
                                <li><strong>Refund scam</strong> Somebody will contact you telling you that a refund is available for you. They will then log in to your bank to "refund you" and will attempt to steal money from there</li>
                                <li><strong>Wire scam</strong> Some fraudsters may ask you to wire them money in order to pay for something or pay a debt. The <?php echo getBankName() ?> online banking dashboard is secured to guard against this</li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#info" data-toggle="collapse">Where can I find out more?</a>
                    <a href="#info" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="info">
                        <div class="card card-body">
                            <h4>More information is available here</h4>

                            <ul>
                                <li><a href="https://en.wikipedia.org/wiki/Technical_support_scam">Learn more here</a></li>
                                <li><a href="https://support.microsoft.com/en-us/help/4013405/windows-protect-from-tech-support-scams">See the official Microsoft/Windows Technical Department</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#afterScam" data-toggle="collapse">What to do if you have been scammed</a>
                    <a href="#afterScam" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="afterScam">
                        <div class="card card-body">
                            <h4>Please don't panic and follow the instructions below</h4>

                            <p>Websites like <a href="https://www.actionfraud.police.uk/">Action Fraud</a> can help. Please call or contact them and go from there</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#reporting" data-toggle="collapse">Reporting cyber crime/fraud</a>
                    <a href="#reporting" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="reporting">
                        <div class="card card-body">
                            <h4>You can report cyber crime to the following agencies</h4>

                            <ul>
                                <li><a href="https://www.actionfraud.police.uk/">Action Fraud</a></li>
                                <li><a href="https://nationalcrimeagency.gov.uk/what-we-do/crime-threats/cyber-crime">National Cyber Crime Agency</a></li>
                                <li><a href="https://www.fbi.gov/">The Federal Bureau of Investigation</a></li>
                                <li>If all else fails <a href="https://en.wikipedia.org/wiki/Russian_mafia">The Russian Mafia</a> can always help</li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>

            <h3>Info at a glance</h3>

            <p><?php echo getBankName() ?> has been committed to preventing fraud since our founding in 2005. Now, over 15 years later, we still haven't beaten them.
                But we have prevented two in three fraud attempts on our website with our proprietary <strong>Scam-B-gone</strong> technology.</p>
        </div>
    </body>
<?php }
