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

require(ABSPATH . INC . "DB.php");
require(ABSPATH . INC . "Users.php");
require(ABSPATH . INC . "Administration.php");

require(ABSPATH . INC . "csrf.php");

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
} else {
    regenerateCSRF();
}
?>

<html>
<?php require(ABSPATH . INC . "components/AdminSidebar.php"); ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require(ABSPATH . INC . "components/AdminSettingsNav.php"); ?>

    <?php if (isset($_GET["resetFailed"])) { ?>
        <div class="alert alert-danger">
            <strong>Failed to reset password</strong> An error occurred while attempting to reset that user's password.
            This could be due to a faulty database. Please, <a href="/admin/settings/Index.php">verify your database settings</a>.
        </div>
    <?php } ?>

    <?php if (isset($_GET["createFailed"])) { ?>
        <div class="alert alert-danger">
            <strong>Failed to create user</strong> An error occurred while attempting to create a user.
            Please ensure that the username entered is not already in use and that your database is configured correctly.
        </div>
    <?php } ?>

    <?php if (isset($_GET["resetSuccess"])) { ?>
        <div class="alert alert-success">
            <strong>Successfully reset password</strong> The specified user's password has been reset to the requested value.
            All active logon sessions have been destroyed.
        </div>
    <?php } ?>

    <?php if (isset($_GET["createSuccess"])) { ?>
        <div class="alert alert-success">
            <strong>Successfully created new user</strong> The requested user has been created.
            If the account was marked as enabled, you can now sign in to the new account with the credentials you specified.
        </div>
    <?php } ?>

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