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

require("../AdminBootstrap.php");

require(ABSPATH . INC . "Users.php");
require(ABSPATH . INC . "Update.php");
require(ABSPATH . INC . "Administration.php");
require(ABSPATH . INC . "Banking.php");

require(ABSPATH . INC . "/api/theme/General.php");

?>

<html>
<?php require(ABSPATH . INC . "components/AdminSidebar.php"); ?>

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
                        <td><?php echo (getBankName()); ?></td>
                    </tr>
                    <tr>
                        <td>On the URL</td>
                        <td><?php echo (getBankURL()); ?></td>
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
            <a href="/admin/bank/support.php" class="btn btn-secondary">Manage support requests</a>
        </div>
    </div>
</div>

</html>