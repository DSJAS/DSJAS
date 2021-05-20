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

        <div class="container">
            <br>

            <h3>My Accounts</h3>

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

            <?php
            if (count(getAccountsArray()) == 0) { ?>
                <p class="text-small text-muted">You don't appear to have any accounts</p>
                <a href="/user/Apply.php">Apply now</a>
            <?php } ?>

            <?php addModuleDescriptor("footer");  ?>
            <hr>
        </div>
    </body>
<?php }
