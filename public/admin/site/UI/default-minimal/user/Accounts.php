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
require_once(ABSPATH . INC . "api/theme/Accounts.php");
require_once(ABSPATH . INC . "api/theme/Dashboard.php");

// Theme entry point
function getTheme()
{ ?>

    <body>
        <script src=<?php echo (getThemeContent("accounts.js", "js/")) ?>></script>

        <?php require(ABSPATH . "/admin/site/UI/default-minimal/components/DashboardNav.php");

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
                            <td><?php echo (censorAccountNumber($account["account_identifier"])); ?></td>
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
