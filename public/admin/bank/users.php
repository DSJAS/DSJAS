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
require ABSPATH . INC . "Administration.php";
require ABSPATH . INC . "Banking.php";
require ABSPATH . INC . "csrf.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Util.php";


regenerateCSRF();

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php
    if (isset($_GET["success"])) {
        dsjas_alert("Success", "The action completed successfully", "success");
    }

    if (isset($_GET["resetFailed"])) {
        dsjas_alert("Failed to reset password", "There was an error while attempting to reset the requested user's password. Please ensure you provided a reset password", "success");
    }

    if (isset($_GET["userDeleted"])) {
        dsjas_alert("User deleted", "The requested user account has been erased and all associated bank account have been closed", "primary");
    }

    if (isset($_GET["userCreated"])) {
        dsjas_alert("User created", "A user has been created with the specified details. If you asked DSJAS to generate random bank account, the accounts have been created, enabled and associated with the account", "success");
    }
    ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Manage bank users</h1>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Existing users</h3>
        </div>

        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <th>ID</th>
                    <th>Username</th>
                    <th>Real Name</th>
                    <th>Password hint</th>
                    <th>Email</th>
                    <th>Owned bank accounts</th>
                    <th>Actions</th>
                </thead>

                <tbody>
                    <?php foreach (getAllUsers() as $account) { ?>
                        <tr>
                            <td><strong><?php echo ($account["user_id"]); ?></strong></td>
                            <td><?php echo ($account["username"]); ?></td>
                            <td><?php echo ($account["real_name"]); ?></td>
                            <td><?php echo ($account["password_hint"]); ?></td>
                            <td><?php echo ($account["email"]); ?></td>
                            <td>
                                <a data-toggle="collapse" href="#accountsCollapse-<?php echo $account["user_id"] ?>">
                                    Show
                                </a>

                                <div class="collapse" id="accountsCollapse-<?php echo $account["user_id"] ?>">

                                    <?php
                                    $accounts = getAllAccountsForUser($account["user_id"]);

                                    foreach ($accounts as $bAccount) { ?>
                                        <ul>
                                            <li>[<?php echo $bAccount["account_identifier"] ?>] - <?php echo $bAccount["account_name"] ?></li>
                                        </ul>
                                    <?php } ?>
                                </div>
                            </td>
                            <td class="btn-group">
                                <a href="/admin/bank/editUser.php?editUser=<?php echo ($account["user_id"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-primary">Edit user</a>
                                <a href="/admin/bank/createAccount.php?user=<?php echo ($account["user_id"]); ?>" class="btn btn-success">Create bank account</a>
                                <a href="/admin/bank/editUser.php?deleteUser=<?php echo ($account["user_id"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-danger">Delete user</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Create new user</h3>
        </div>

        <div class="card-body">
            <form action="/admin/bank/createUser.php" method="POST">
                <input type="text" style="visibility: hidden; position: absolute" name="createUser" value="1">
                <?php getCSRFFormElement(); ?>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputUsername">Username</label>
                        <input type="text" class="form-control" id="inputUsername" name="username" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputEmail">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="email">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="inputRealName">Real name</label>
                        <input type="text" class="form-control" id="inputRealName" name="realName">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputPassword">Password</label>
                        <input type="password" class="form-control" id="inputPassword" name="password" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword">Password hint</label>
                        <input type="text" class="form-control" id="inputPasswordHint" name="passwordHint">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="generateRandom" name="generateBankAccounts" checked>
                        <label class="form-check-label" for="generateRandom" data-toggle="tooltip" data-placement="top" title="Generate bank accounts with random details and funds">
                            Generate randomized bank accounts for this user
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Reset password</h3>
        </div>

        <div class="card-body">
            <form action="/admin/bank/editUser.php" method="POST">
                <input type="text" style="visibility: hidden; position: absolute" name="resetPassword" value="1">
                <?php getCSRFFormElement(); ?>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="passwordInput">Username</label>
                        <select type="email" name="username" class="form-control" id="usernameInput">
                            <?php
                            foreach (getAllUsers() as $user) { ?>
                                <option><?php echo ($user["username"]); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="passwordInput">New password</label>
                        <input type="password" name="password" class="form-control" id="passwordInput" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Confirm</button>
            </form>
        </div>
    </div>
</div>

</html>