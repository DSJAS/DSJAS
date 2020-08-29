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

require "../AdminBootstrap.php";

require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Update.php";
require ABSPATH . INC . "Administration.php";
require ABSPATH . INC . "Banking.php";

require_once ABSPATH . INC . "Customization.php";

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Manage your bank</h1>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>At a glance</h3>
        </div>

        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <th>Appearance</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>Your bank is currently functioning as</td>
                        <td><?php echo (getCurrentBankName()); ?></td>
                    </tr>
                    <tr>
                        <td>On the URL</td>
                        <td><?php echo (getCurrentBankURL()); ?></td>
                    </tr>
                </tbody>

                <thead class="thead-dark">
                    <th>Accounts</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>Number of online user accounts</td>
                        <td><?php echo (getNumberOfUsers(false)); ?></td>
                    </tr>
                    <tr>
                        <td>Number of bank accounts</td>
                        <td><?php echo (getNumberOfAccounts()); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Tasks</h3>
        </div>

        <div class="card-body">
            <div class="btn-group">
                <a href="/admin/bank/users.php" class="btn btn-primary">Manage user accounts</a>
                <a href="/admin/bank/accounts.php" class="btn btn-secondary">Manage bank accounts</a>
            </div>

            <a href="/admin/bank/transactions.php" class="btn btn-warning">Manage transactions</a>
        </div>
    </div>
</div>

</html>