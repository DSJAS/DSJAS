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
    $overallBalance = 0;
    $overallDebt = 0;
    $accountStanding = "<span class='text-warning'>normal</span>";

    $accounts = getAccountsArray();
    foreach ($accounts as $account) {
        $balance = $account["account_balance"];
        if (isPricePositive($balance)) {
            $overallBalance += $account["account_balance"];
        } else {
            $overallDebt += abs($account["account_balance"]);
        }
    }

    if ($overallBalance < $overallDebt) {
        $accountStanding = "<span class='text-danger'>concerning</span>";
    } else if ($overallDebt == 0) {
        $accountStanding = "<span class='text-success'>spotless</span>";
    } else if ($overallBalance == 0) {
        $accountStanding = "<span class='text-warning'>broke</span>";
    } else if ($overallBalance > 10000) {
        $accountStanding = "<span class='text-info'>great</span>";
    } else {
        $accountStanding = "<span class='text-primary'>good</span>";
    }

?>

    <body>
        <script src=<?php echo (getThemeContent("accounts.js", "js/")) ?>></script>

        <?php include ABSPATH . getRawThemeContent("DashboardNav.php", "components/");

        addModuleDescriptor("alert_area");  ?>

        <div class="container">
            <br>

            <h3>Manage your online account</h3>
            <br>

            <h4 class="border-bottom">Your summary</h4>

            <p><strong>Hi there, <?php echo (getDisplayName()); ?>!</strong> Below is your account summary, generated on <?php echo (date("d/M/Y")) ?> at <?php echo (date("H:i")); ?></p>

            <br>

            <div class="border mb-3">
                <p><strong>Your overall balance:</strong> $<?php echo ($overallBalance); ?></p>
                <p><strong>Your overall debt:</strong> $<?php echo ($overallDebt); ?></p>

                <p><strong>Your account standing:</strong> <?php echo ($accountStanding); ?></p>
                <small class="text-muted">You should always aim to keep your account in normal or good standing. If your account becomes in a "concerning" state, you may be asked about your finances</small>
            </div>


            <h4 class="border-bottom">Your bank accounts</h4>

            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Account number</th>
                        <th scope="col">Available balance</th>
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
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>

            <h4 class="border-bottom">Banking statements</h4>

            <p class="text-muted">Future statements will appear here. Requested statements will be stored for up to 3 years after issue time.</p>


            <?php addModuleDescriptor("footer");  ?>
        </div>
    </body>
<?php }
