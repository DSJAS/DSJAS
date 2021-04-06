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

require ABSPATH . INC . "DB.php";
require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Administration.php";

require ABSPATH . INC . "csrf.php";
require_once ABSPATH . INC . "Util.php";

$usersArray = getUsersArray(true);

if (isset($_POST["resetPassword"])) {
    if (!verifyCSRFToken(getCSRFSubmission())) {
        die(getCSRFFailedError());
    }

    if (!isset($_POST["username"]) || $_POST["username"] == null || !isset($_POST["password"]) || $_POST["password"] == null) {
        header("Location: /admin/settings/accounts.php?resetFailed");
        die();
    }

    $userId = getUserIDFromName($_POST["username"], true);

    changeUserPassword($userId, $_POST["password"], true);

    header("Location: /admin/settings/accounts.php?resetSuccess");
    die();
} elseif (isset($_POST["createUser"])) {
    if (!verifyCSRFToken(getCSRFSubmission())) {
        die(getCSRFFailedError());
    }

    if (!isset($_POST["username"]) || $_POST["username"] == null || !isset($_POST["password"]) || $_POST["password"] == null) {
        header("Location: /admin/settings/accounts.php?createFailed");
        die();
    }

    if (userExists($_POST["username"], null, null, true)) {
        header("Location: /admin/settings/accounts.php?createFailed");
        die();
    }

    $enabled = $_POST["accountEnabled"] == "on";

    createUser($_POST["username"], $_POST["email"], $_POST["password"], $_POST["passwordHint"], $enabled, true);

    header("Location: /admin/settings/accounts.php?createSuccess");
    die();
} else {
    regenerateCSRF();
}
?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require ABSPATH . INC . "components/AdminSettingsNav.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">DSJAS User Settings</h1>
    </div>

    <?php if (isset($_GET["resetFailed"])) {
        dsjas_alert("Failed to reset password", "An error occured while attempting to reset a user password
This is likely due to a faulty database. Please, <a href=\"/admin/settings/Index.php\">verify your database settings</a>", "danger", true);
    }

    if (isset($_GET["createFailed"])) {
        dsjas_alert("Failed to create user", "An error occured while attempting to create a user. Please ensure that the
username is not already in use and <a href=\"/admin/settings/Index.php\">verify your database settings</a>", "danger", true);
    }

    if (isset($_GET["resetSuccess"])) {
        dsjas_alert("Successfully reset password", "The specified user's password was reset to the requested value. All active sessions have been ended", "success", true);
    }

    if (isset($_GET["createSuccess"])) {
        dsjas_alert("Successfully created new user", "A user has been created with the requested values. If enabled, the account can now be accessed with the specified
credentials via <a href=\"/admin/user/SignIn.php\">the admin login</a>", "success", true);
    } ?>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Existing users</h3>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Real name</th>
                        <th>Email</th>
                        <th>Date of registration</th>
                        <th>New account</th>
                        <th>Account enabled</th>
                        <th></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($usersArray as $user) { ?>
                        <tr>
                            <th scope="row"><?php echo ($user["user_id"]); ?></th>
                            <td><?php echo ($user["username"]); ?></td>
                            <td><?php echo ($user["real_name"]); ?></td>
                            <td><?php echo ($user["email"]); ?></td>
                            <td><?php echo ($user["date_of_registration"]); ?></td>

                            <?php if ($user["new_account"]) { ?>
                                <td class="text-success">Yes</td>
                            <?php } else { ?>
                                <td class="text-danger">No</td>
                            <?php } ?>

                            <?php if ($user["account_enabled"]) { ?>
                                <td class="text-success">Yes</td>
                            <?php } else { ?>
                                <td class="text-danger">No</td>
                            <?php } ?>

                            <td></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a type="button" href="/admin/settings/editUser.php?editUser=<?php echo ($user["user_id"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-warning">Edit</a>
                                    <a type="button" href="/admin/settings/editUser.php?deleteUser=<?php echo ($user["user_id"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-danger">Delete</a>

                                    <?php if ($user["account_enabled"]) { ?>
                                        <a type="button" href="/admin/settings/editUser.php?toggleEnabledUser=<?php echo ($user["user_id"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-danger">Disable</a>
                                    <?php } else { ?>
                                        <a type="button" href="/admin/settings/editUser.php?toggleEnabledUser=<?php echo ($user["user_id"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-success">Enable</a>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Create user</h3>
        </div>

        <div class="card-body">
            <form action="/admin/settings/accounts.php" method="POST">
                <input type="text" style="visibility: hidden; position: absolute" name="createUser" value="1">
                <input id="usercsrf" type="text" style="visibility: hidden; position: absolute" name="<?php echo (CSRF_FORM_NAME); ?>" value="<?php echo (getCSRFToken()); ?>">

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
                        <input class="form-check-input" type="checkbox" id="enabled" name="accountEnabled" checked>
                        <label class="form-check-label" for="gridCheck">
                            Enable this account
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Reset user password</h3>
        </div>

        <div class="card-body">
            <form method="POST" action="/admin/settings/accounts.php">
                <input type="text" style="visibility: hidden; position: absolute" name="resetPassword" value="1">
                <input id="csrf" type="text" style="visibility: hidden; position: absolute" name="<?php echo (CSRF_FORM_NAME); ?>" value="<?php echo (getCSRFToken()); ?>">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="passwordInput">Username</label>
                        <select type="email" name="username" class="form-control" id="usernameInput">
                            <?php
                            foreach ($usersArray as $user) { ?>
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