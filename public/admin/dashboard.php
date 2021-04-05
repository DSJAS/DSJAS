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

require "AdminBootstrap.php";

require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Update.php";
require ABSPATH . INC . "Administration.php";

require ABSPATH . INC . "Stats.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Util.php";


if (isset($_GET["purgeNotifications"])) {
    purgeAdministrationNotices();
}


$majorVersion = getMajorVersion();
$minorVersion = getMinorVersion();
$patch = getPatchVersion();
$semantic = getSemanticVersion();

$adminNotices = getAdministrationNotices();
$areAdminNotices = count($adminNotices) >= 1;

$newAccount = currentUserIsNew(true);


if ($newAccount) {
    makeCurrentUserUsed(true);
}


$stats = new Statistics();
$stats->incrementCounterStat("total_page_hits");
$stats->incrementCounterStat("admin_page_hits");

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Welcome back, <?php echo (getCurrentUsername(true)); ?></h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="/admin/settings/" class="btn btn-sm btn-outline-secondary">Settings</a>
                <a href="https://github.com/OverEngineeredCode/DSJAS/tree/master/docs/administration" class="btn btn-sm btn-outline-secondary">Help</a>
            </div>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Notifications</h3>
            <a class="btn btn-danger" href="/admin/dashboard.php?purgeNotifications">Clear</a>
        </div>

        <div class="card-body">
            <?php
            if ($areAdminNotices || isset($_GET["purgeNotifications"]) || $newAccount) {
                handleAdminNotices($adminNotices);

                if (isset($_GET["purgeNotifications"])) {
                    dsjas_alert("Notifications cleared", "This message will be removed automatically", "success");
                }

                if ($newAccount) {
                    dsjas_alert("Welcome to DSJAS!", "DSJAS has detected that your account is new. Welcome! You have reached the admin dashboard,
where you can find most of the site's features. If you need any help, you can probably find it in the wiki, accessible through the help link
on the sidebar. Have fun and happy scambaiting! <i>(This message will only appear once and will be cleared automtically)</i>", "info");
                }
            } else { ?>
                <p class="text-small text-muted">No notifications are available</p>
            <?php } ?>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <h3 class="card-header">Quick information</h3>

        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>General overview</th>
                        <th></th>
                    </tr>
                </thead>

                <tr>
                    <td class="admin-info-key">Site name</td>
                    <td class="admin-info-value"><?php echo (getCurrentBankName()); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Site URL</td>
                    <td class="admin-info-value"><?php echo (getCurrentBankURL()); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Site version</td>
                    <td class="admin-info-value"><?php echo ($semantic); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Current theme</td>
                    <td class="admin-info-value"><?php echo (getActiveTheme()); ?></td>
                </tr>

                <thead class="thead-dark">
                    <tr>
                        <th>Accounts</th>
                        <th></th>
                    </tr>
                </thead>

                <tr>
                    <td class="admin-info-key">Amount of users</td>
                    <td class="admin-info-value"><?php echo (getNumberOfUsers(false)); ?></td>
                </tr>

                <tr>
                    <td class="admin-info-key">Amount of site users</td>
                    <td class="admin-info-value"><?php echo (getNumberOfUsers(true)); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <h3 class="card-header">Quick actions</h3>

        <div class="card-body">
            <div class="btn-group" role="group">
                <a class="btn btn-primary admin-quick-action-btn" href="/admin/settings/accounts.php">New site user</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="/admin/bank/users.php">New user</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="/admin/bank/accounts.php">New bank account</a>
            </div>

            <div class="btn-group" role="group">
                <a class="btn btn-primary admin-quick-action-btn" href="/admin/settings/mod.php">Install a theme</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="/admin/settings/ext.php">Install a plugin</a>
                <a class="btn btn-secondary admin-quick-action-btn" href="/admin/settings/mod.php">Configure modules</a>
            </div>

            <a href="/admin/settings/mod.php#validatorResults" class="btn btn-success admin-quick-action-btn">Validate theme</a>

            <div class="btn-group" role="group">
                <a href="/admin/user/Logout" class="btn btn-danger admin-quick-action-btn">Logout</a>
                <a href="/user/Logout" class="btn btn-secondary admin-quick-action-btn">Logout from bank</a>
            </div>

        </div>
    </div>
</div>

</html>