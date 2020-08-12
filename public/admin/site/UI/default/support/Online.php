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

        <div class="container">
            <div class="header mb-4">
                <h1 class="display-4">
                    Online banking support
                </h1>

                <p class="lead"><?php echo getBankName() ?> has award winning customer service for our award winning online banking from an award winning reviewing organization who has won awards.</p>
            </div>

            <ul>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#signIn" data-toggle="collapse">Having trouble signing in?</a>
                    <a href="#signIn" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="signIn">
                        <div class="card card-body">
                            <h4>Don't worry: your account is still safe</h4>

                            <ul>
                                <li>Please <a href="/support/Contact">contact support</a>. They will assist you in the process of resetting your password</li>
                                <li>Call our helpline. More information is available <a href="/support/Contact">here</a>.</li>
                                <li>Visit us in branch and we can give you access back to your account. Be sure to bring proof of ID and ownership!</li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#access" data-toggle="collapse">How can I access my money online?</a>
                    <a href="#access" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="access">
                        <div class="card card-body">
                            <h4>It's simple really</h4>

                            <p>Navigate to the bank's homepage <a href="/">here</a>. Then, click on Login.
                                Enter your username/email and password and complete any required verification steps.
                                You should be greeted with your user dashboard!
                            </p>

                            <p>
                                Your user dashboard is the place you can access funds, make transfers and perform actions on your account.
                            </p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#transfer" data-toggle="collapse">How do I transfer money between my accounts?</a>
                    <a href="#transfer" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="transfer">
                        <div class="card card-body">
                            <h4>We've got that covered for you</h4>

                            <p>In the online banking panel, click on transfer next to the account you wish to transfer from. Then, select the account to transfer to
                                and we'll handle the rest.
                            </p>

                            <a href="/user/Transfer">Access the transfer page</a>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#reporting" data-toggle="collapse">How can I keep my account secure?</a>
                    <a href="#reporting" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="reporting">
                        <div class="card card-body">
                            <h4><?php echo getBankName() ?> has that covered for you</h4>

                            <ul>
                                <li>Never give out your banking details, especially not your password</li>
                                <li>Never log anybody in to your account</li>
                                <li>Never transfer to any account you don't fully trust</li>
                                <li>Microsoft will never ask for your banking details</li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </body>
<?php }
