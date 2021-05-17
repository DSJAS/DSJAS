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
require ABSPATH . INC . "Banking.php";
require ABSPATH . INC . "csrf.php";

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div id="content">

    <?php

    if (isset($_GET["deleteUser"])) { ?>
        <div class="text-center">
            <h1 style="color: red">Warning</h1>
            <p class="lead">Deleting a user is permanent and will erase all data associated with the account.
                <strong>This includes all bank accounts currently associated with the account.</strong>
                You cannot undo this action and the account data will not be recoverable. In addition, all closed bank accounts will not be re-openable.
            </p>
            <p><strong>Are you sure you wish to continue?</strong></p>
            <hr>
            <a class="btn btn-danger" href="/admin/bank/editUser.php?doDeleteUser=<?php echo ($_GET["deleteUser"]); ?>&csrf=<?php echo ($_GET["csrf"]); ?>">Confirm</a>
            <a class="btn btn-secondary" href="/admin/bank/users.php">Cancel</a>
        </div>
    <?php } elseif (isset($_GET["doDeleteUser"])) {
        $csrf = getCSRFSubmission("GET");
        if (!verifyCSRFToken($csrf)) {
            getCSRFFailedError();
            die();
        }

        eraseUser($_GET["doDeleteUser"]);
        header("Location: /admin/bank/users.php?userDeleted");
    } elseif (isset($_POST["doEditUser"])) {
        $csrf = getCSRFSubmission();
        if (!verifyCSRFToken($csrf)) {
            getCSRFFailedError();
            die();
        }

        $config = parse_ini_file(ABSPATH . "/Config.ini");
        $userID = $_POST["doEditUser"];

        $database = new DB(
            $configuration["server_hostname"],
            $configuration["database_name"],
            $configuration["username"],
            $configuration["password"]
        );

        $query = new PreparedStatement(
            "UPDATE `users` SET `username` = ?, `real_name` = ?, `email` = ?, `password_hint` = ? WHERE `user_id` = $userID",
            [$_POST["username"], $_POST["realName"], $_POST["email"], $_POST["passwordHint"]],
            "ssss"
        );

        $database->prepareQuery($query);
        $database->query();

        if ($_POST["doEditUser"] == getCurrentUserId(true)) {
            fillSessionDetails(true, $_POST["username"], getUserIDFromName($_POST["username"], false), false); // Update in memory details cache
        }

        header("Location: /admin/bank/users.php?success");
    } elseif (isset($_POST["resetPassword"])) {
        if (!verifyCSRFToken(getCSRFSubmission())) {
            die(getCSRFFailedError());
        }

        if (!isset($_POST["username"]) || $_POST["username"] == null || !isset($_POST["password"]) || $_POST["password"] == null) {
            header("Location: /admin/settings/accounts.php?resetFailed");
            die();
        }

        $userId = getUserIDFromName($_POST["username"], false);

        changeUserPassword($userId, $_POST["password"], false);

        header("Location: /admin/bank/users.php?success");
    } else { ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="admin-header col col-offset-6">Now editing <strong>bank</strong> user "<?php echo (getInfoFromUserID($_GET["editUser"], "username", false)); ?>"</h1>
        </div>

        <form action="/admin/bank/editUser.php" method="POST">
            <input type="text" style="visibility: hidden; position: absolute" name="doEditUser" value="<?php echo ($_GET["editUser"]); ?>">
            <input id="csrf" type="text" style="visibility: hidden; position: absolute" name="<?php echo (CSRF_FORM_NAME); ?>" value="<?php echo (getCSRFToken()); ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "username", false))); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4">Real name</label>
                    <input type="text" class="form-control" id="realName" name="realName" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "real_name", false))); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="inputAddress">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "email", false))); ?>">
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="inputPassword4">Password hint</label>
                    <input type="text" class="form-control" id="passwordHint" name="passwordHint" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "password_hint", false))); ?>">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Save</button>
                <button class="btn btn-danger" onclick="location.reload()">Discard changes</button>
            </div>
            <div class="btn-group">
                <a href="/admin/bank/createAccount.php?user=<?php echo ($_GET["editUser"]); ?>" class="btn btn-success">Create bank account for this user</a>
                <a href="/admin/bank/migrateAccount.php?user=<?php echo ($_GET["editUser"]); ?>" class="btn btn-secondary">Migrate an account to this user</a>
            </div>

            <a href="/admin/bank/editUser?deleteUser=<?php echo ($_GET["editUser"]); ?>&csrf=<?= getCSRFToken(); ?>" class="btn btn-danger">Delete this user</a>

            <br><br>
            <small class="text-muted">Looking for passwords? You can reset passwords on the accounts screen. Your password is not available once stored and can only be reset by an administrator.</small>
        </form>
    <?php } ?>

</div>

</html>