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
        <script src=<?php echo (getThemeContent("accounts.js", "js/")) ?>></script>

        <?php include ABSPATH . getRawThemeContent("DashboardNav.php", "components/");

        addModuleDescriptor("alert_area");  ?>

        <div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="closeModalLabel">We're sorry to see you go, but also not...</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><strong>Due to the sensitive nature of closing an account, you will need to request this in person. Keep in mind, this can be done over the phone.</strong></p>
                        <hr>
                        <p>Due to the fact that closing an account can be devastating if done incorrectly or by a malicious third party, you will need to call support in person or visit a branch in order to perform this action.
                            There is no way to remotely close your account. However, <?php echo (getBankName()); ?> will never prevent you from closing an account. We just need to be sure that you really want this.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <a href="/support/Online" class="btn btn-link">More information</a>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <br>

            <h3>Account summary</h3>

            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Account number</th>
                        <th scope="col">Available balance</th>
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
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>

            <h3>Quick actions</h3>

            <?php
            if (count(getAccountsArray()) == 0) { ?>
                <p class="text-small text-muted">You don't appear to have any accounts</p>
                <a href="/user/Apply.php">Apply now</a>
            <?php } ?>

            <a href="/user/Apply" class="btn btn-success">Apply now</a>
            <button class="btn btn-danger" onclick="closeAccount()">Close an account</button>

            <div class="btn-group">
                <a href="/support/Support" class="btn btn-dark">Get help</a>
                <a href="/support/Contact" class="btn btn-warning">Contact support</a>
            </div>

            <?php addModuleDescriptor("footer");  ?>
            <hr>
        </div>
    </body>
<?php }
