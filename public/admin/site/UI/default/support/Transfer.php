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

    This is the themeing files included in the default installation of DSJAS.
    It contains HTML and PHP files required to load and display the default theme.

    This file should never be accessed directly, and instead should only be
    required by a file which has already bootstrapped the site.
    This means that your script must have defined the ABSPATH constants
    and preformed other required bootstrapping tasks before the page
    can be displayed.


    For more information of theming and creating your own themes, please refer to the
    API documentation for themes and plugins.
*/

require_once(ABSPATH . INC . "api/theme/General.php");
require_once(ABSPATH . INC . "api/theme/Appearance.php");

// Theme entry point
function getTheme()
{ ?>

    <body>
        <?php require(ABSPATH . "/admin/site/UI/default/components/SupportNav.php"); ?>

        <div class="container">
            <div class="header mb-4">
                <h1 class="display-4">
                    Help with transferring money
                </h1>

                <p class="lead">Safe and secure. Two things that we wish our money transferral system was</p>
            </div>

            <ul>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#transfer" data-toggle="collapse">Get help making a transfer</a>
                    <a href="#transfer" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="transfer">
                        <div class="card card-body">
                            <h4>It's as simple as it could be</h4>

                            <ul>
                                <li>Visit the transfer page <a href="/user/Transfer">here</a>.</li>
                                <li>Enter the required information</li>
                                <li>You're done!</li>
                            </ul>

                            <p>If you continually get errors or have trouble using the transfer page, your account may be disabled or a fraud alert may be ongoing.
                                <a href="/support/Contact">Contact us</a> and try again
                            </p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#reverse" data-toggle="collapse">How can I dispute/reverse a transfer?</a>
                    <a href="#reverse" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="reverse">
                        <div class="card card-body">
                            <h4>That's more of a difficult one...</h4>

                            <p>
                                Transfers aren't usually reversible. Maybe <a href="/support/Contact">contact us</a> and we might be able to help
                            </p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#safe" data-toggle="collapse">How can I be safe when transferring?</a>
                    <a href="#safe" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="safe">
                        <div class="card card-body">
                            <h4>Follow the golden rule</h4>

                            <p>
                                Transfer to your own accounts only. That's it; that's all there is to it.
                            </p>

                        </div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#reporting" data-toggle="collapse">My transfer was refused?</a>
                    <a href="#reporting" class="badge badge-primary badge-pill" data-toggle="collapse">
                        <svg class="bi bi-arrow-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <div class="collapse" id="reporting">
                        <div class="card card-body">
                            <h4>Check the details and try again</h4>

                            <ul>
                                <li>Make sure that you're not trying to transfer a negative amount</li>
                                <li>Make sure that the accounts you're trying to transfer between are different</li>
                                <li>Make sure the account you're trying to transfer from/to isn't frozen or disabled</li>
                                <li><a href="/support/Contact">Ask us</a> about fraud alerts on your account</li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>

            <p>Transfers are one of the most powerful tools on our dashboard. But, it can be dangerous.
                Please <a href="/support/Fraud">report fraud</a> if something seems off.
            </p>
        </div>
    </body>
<?php }
