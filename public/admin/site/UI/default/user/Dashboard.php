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
require_once THEME_API . "Dashboard.php";

// Theme entry point
function getTheme()
{
?>

    <body>
        <?php include ABSPATH . getRawThemeContent("DashboardNav.php", "components/");

        addModuleDescriptor("alert_area");  ?>

        <div class="container-fluid mt-4">

            <div class="col-3 p-5 boxcol rounded float-right d-none d-lg-block">
                <div class="row card rounded mb-4 p-3">
                    <div class="card-body">
                        <h5 class="card-title">Welcome back!</h5>

                        <hr>

                        <div class="card-text">
                            <p class="text-center"><strong>We've been expecting you</strong></p>
                            <p>Your account summary is available <a href="/user/Manage.php"></a>.</p>

                            <p>Your currently have a total of <strong>3</strong> unread alerts and <strong>5</strong> unopened documents</p>
                        </div>
                        <a class="btn btn-success" href="#" onclick="alert('We\'re sorry, there was an error while attempting to retrieve your info. Please try again later or contact support.');">View all</a>
                    </div>
                </div>

                <div class="row card rounded p-3 mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Get the app</h3>

                        <hr>

                        <div class="card-text">
                            <p>
                                Bank on the go and save paper with our new app.
                            </p>

                            <div class="text-center">
                                <a href="https://sourceforge.net/">
                                    <img src="<?php echo getRawThemeContent("download-app.png", "assets/"); ?>" alt="Get the app today" width="200" height="150">
                                </a>
                            </div>

                            <hr>
                            <small class="text-muted">Download today from the Google Play or App Store</small>
                        </div>
                    </div>
                </div>

                <div class="row p-3 card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Need help?
                        </h5>

                        <p>Get in touch with a support representative within three minutes (withing working hours only)</p>

                        <p>We sure love reading your messages for ten hours a day! Send us some more!</p>

                        <a href="/support/Contact.php" class="btn btn-warning dashboard-footer-buttons">Contact support</a>
                        <?php addModuleDescriptor("dashboard_footer");  ?>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col">

                    <h3>My Accounts</h3>

                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Account number</th>
                                <th scope="col">Available balance</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach (getAccountsArray() as $account) { ?>
                                <tr>
                                    <td class="text-primary"><?php echo ($account["account_name"]); ?></td>
                                    <td><?php echo (censorAccountNumber($account["account_number"])); ?></td>
                                    <?php if (isPricePositive($account["account_balance"])) { ?>
                                        <td class="text-success">$<?php echo ($account["account_balance"]); ?></td>
                                    <?php } else { ?>
                                        <td class="text-danger">$<?php echo ($account["account_balance"]); ?></td>
                                    <?php } ?>
                                    <td><a href="/user/Transfer.php">Transfer</a></td>
                                    <td><a href="/user/Manage.php">Manage</a></td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>

                    <?php
                    if (count(getAccountsArray()) == 0) { ?>
                        <p class="text-small text-muted">You don't appear to have any accounts</p>
                        <a href="/user/Apply.php">Apply now</a>
                    <?php } ?>

                    <br>
                    <hr>
                    <br>

                    <h3>Recent transactions</h3>

                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Account</th>
                                <th scope="col">Description</th>
                                <th scope="col">Type</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach (getRecentTransactionsArray(5) as $info) {

                                // Miniature workaround to make sure the transaction type is capitalized
                                $type = $info["transaction_type"];
                                $type[0] = strtoupper($type[0]);

                            ?>
                                <tr>
                                    <td><?php echo ($info["transaction_date"]); ?></td>
                                    <td><?php echo (getDisplayAccountNumber($info["origin_account_id"])); ?></td>
                                    <td><?php echo ($info["transaction_description"]); ?></th>
                                    <td><?php echo ($type); ?></th>
                                        <?php if (isPricePositive($info["transaction_amount"])) { ?>
                                    <td class="text-success">$<?php echo ($info["transaction_amount"]); ?></td>
                                <?php } else { ?>
                                    <td class="text-danger">$<?php echo ($info["transaction_amount"]); ?></td>
                                <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php
                    if (count(getRecentTransactionsArray(5)) == 0) { ?>
                        <p class="text-small text-muted">No recent transaction history</p>
                        <a href="/user/Transfer.php">Make one now</a>
                        <hr>
                    <?php }
                    ?>

                    <div class="row card-deck mb-4">
                        <div class="card col dashboard-adverts" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Be scam smart</h5>
                                <p class="card-text">Fraud is everywhere. Get good now at spotting them so that you aren't a victim later. You could be speaking to a scammer right now...</p>
                                <a href="https://www.actionfraud.police.uk/">More information</a>
                            </div>
                        </div>
                        <div class="card col dashboard-adverts" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Join CP Rewards</h5>
                                <p class="card-text">With community-public rewards scheme, you can get cash for everyday purchases at high street stores. Give those small stores a go and get rewarded.</p>
                                <a href="/CPRewards">Get started</a>
                            </div>
                        </div>
                        <div class="card col dashboard-adverts" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Record low interest rates</h5>
                                <p class="card-text">You're already approved! It only takes several hours to open an account and you're set for life.
                                    Of course, set meaning set up with an account. The riches come later.
                                </p>
                                <a href="/user/Apply.php">Learn more</a>
                            </div>
                        </div>

                        <?php addModuleDescriptor("dashboard_notices");  ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
<?php }
