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


if (isset($_GET["doDeleteUser"])) {
    $csrf = getCSRFSubmission("GET");
    if (!verifyCSRFToken($csrf)) {
        getCSRFFailedError();
        die();
    }

    eraseUser($_GET["doDeleteUser"], true);
    header("Location: /admin/settings/accounts.php");
}

if (isset($_GET["doToggleEnabledUser"])) {
    $csrf = getCSRFSubmission("GET");
    if (!verifyCSRFToken($csrf)) {
        getCSRFFailedError();
        die();
    }

    if (getCurrentUserId(true) == $_GET["doToggleEnabledUser"]) {
        die("Error: Refusing to disable the currently logged account");
    }

    if (getInfoFromUserID($_GET["doToggleEnabledUser"], "account_enabled", true)) {
        disableUser($_GET["doToggleEnabledUser"], true);
    } else {
        enableUser($_GET["doToggleEnabledUser"], true);
    }

    header("Location: /admin/settings/accounts.php");
    die();
}

if (isset($_POST["doEditUser"])) {
    $csrf = getCSRFSubmission();
    if (!verifyCSRFToken($csrf)) {
        getCSRFFailedError();
        die();
    }

    $config = parse_ini_file(ABSPATH . "/Config.ini");
    $userID = $_POST["doEditUser"];

    if ($_POST["password"] == "xxxxxxxxxxxx") {
        $database = new DB(
            $configuration["server_hostname"],
            $configuration["database_name"],
            $configuration["username"],
            $configuration["password"]
        );

        $query = new PreparedStatement(
            "UPDATE `siteusers` SET `username` = ?, `real_name` = ?, `email` = ?, `password_hint` = ? WHERE `user_id` = $userID",
            [$_POST["username"], $_POST["realName"], $_POST["email"], $_POST["passwordHint"]],
            "ssss"
        );

        $database->prepareQuery($query);
        $database->query();
    } else {
        $database = new DB(
            $configuration["server_hostname"],
            $configuration["database_name"],
            $configuration["username"],
            $configuration["password"]
        );

        $newHash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $query = new PreparedStatement(
            "UPDATE `siteusers` SET `username` = ?, `real_name` = ?, `email` = ?, `password_hash` = ?, `password_hint` = ? WHERE `user_id` = $userID",
            [$_POST["username"], $_POST["realName"], $_POST["email"], $newHash, $_POST["passwordHint"]],
            "sssss"
        );

        $database->prepareQuery($query);
        $database->query();
    }

    if ($_POST["doEditUser"] == getCurrentUserId(true)) {
        fillSessionDetails(true, $_POST["username"], getUserIDFromName($_POST["username"], true), true); // Update in memory details cache
    }

    header("Location: /admin/settings/accounts.php");
    die();
}

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <script src="/include/js/editUser.js"></script>

    <?php require ABSPATH . INC . "components/AdminSettingsNav.php";

    if (isset($_GET["deleteUser"])) {
        if (getCurrentUserId(true) == $_GET["deleteUser"]) {
            dsjas_alert("Cannot delete account", "You appear to be attempting to delete your own account. This action is not allowed and the operation has been cancelled",
                        "danger", false);

    ?>

                    <a href="/admin/settings/accounts.php">Go back to the accounts page</a>
                </p>
            </div>
    <?php
        die();
    } ?>

        <div class="text-center">
            <h1 style="color: red">Warning</h1>
            <p class="lead">Deleting a user is permanent and will erase all data associated with the account. You cannot undo this action and the account data will not be recoverable.</p>
            <p><strong>Are you sure you wish to continue?</strong></p>
            <hr>
            <a class="btn btn-danger" href="/admin/settings/editUser.php?doDeleteUser=<?php echo ($_GET["deleteUser"]); ?>&csrf=<?php echo ($_GET["csrf"]); ?>">Confirm</a>
            <a class="btn btn-secondary" href="/admin/settings/accounts.php">Cancel</a>
        </div>

    <?php }

    if (isset($_GET["toggleEnabledUser"])) {
        if (getCurrentUserId(true) == $_GET["toggleEnabledUser"]) {
            dsjas_alert("Cannot disable account", "You appear to be attempting to disable your own account. You cannot disable the currently signed in account.
To disable this account, create of login to another account and order the disable from there", "danger", false);

            ?>
                    <a href="/admin/settings/accounts.php">Go back to the accounts page</a>
                </p>
            </div>
            <?php
            die();
        }

        if (getInfoFromUserID($_GET["toggleEnabledUser"], "account_enabled", true)) { ?>
            <div class="text-center">
                <h1 style="color: red">Warning</h1>
                <p class="lead">Disabling a user account will immediately cause this user to loose access the site.
                    The account will not be able to be signed in to until it is re-enabled and any existing sessions will be ended.
                </p>
                <p><strong>Are you sure you wish to continue?</strong></p>
                <hr>
                <a class="btn btn-danger" href="/admin/settings/editUser.php?doToggleEnabledUser=<?php echo ($_GET["toggleEnabledUser"]); ?>&csrf=<?php echo ($_GET["csrf"]); ?>">Confirm</a>
                <a class="btn btn-secondary" href="/admin/settings/accounts.php">Cancel</a>
            </div>
        <?php } else { ?>
            <div class="text-center">
                <h1 style="color: red">Warning</h1>
                <p class="lead">Enabling this account will allow anybody with the logon credentials to gain immediate access to the site.
                </p>
                <p><strong>Are you sure you wish to continue?</strong></p>
                <hr>
                <a class="btn btn-danger" href="/admin/settings/editUser.php?doToggleEnabledUser=<?php echo ($_GET["toggleEnabledUser"]); ?>&csrf=<?php echo ($_GET["csrf"]); ?>">Confirm</a>
                <a class="btn btn-secondary" href="/admin/settings/accounts.php">Cancel</a>
            </div>
        <?php }
    }

    if (isset($_GET["editUser"])) {
        if (!getInfoFromUserID($_GET["editUser"], "account_enabled", true)) {
            $enableLink = "/admin/settings/editUser.php?toggleEnabledUser=" . htmlentities($_GET["editUser"]) . "&csrf=" . getCSRFToken();

            dsjas_alert("<p>This account is disabled", "You are editing an account which is currently disabled. Regardless of changes, this user will still not be able to access their account.</p>" .
                        "<a href=\"$enableLink\">Enable this account</a>", "warning", false);
        }

        if ($_GET["editUser"] == getCurrentUserId(true)) {
            dsjas_alert("This is you!", "You're editing your own account information. Any changes will be reflected on your profile", "info", false);
        } else {
            dsjas_alert("Take care: This isn't your account!", "You're editing another person's account information. Please take care and avoid making unwanted changes", "warning", false);
        }
        ?>


        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="admin-header col col-offset-6">Now editing user "<?php echo (getInfoFromUserID($_GET["editUser"], "username", true)); ?>"</h1>
        </div>

        <form action="/admin/settings/editUser.php" method="POST">
            <input type="text" style="visibility: hidden; position: absolute" name="doEditUser" value="<?php echo ($_GET["editUser"]); ?>">
            <input id="csrf" type="text" style="visibility: hidden; position: absolute" name="<?php echo (CSRF_FORM_NAME); ?>" value="<?php echo (getCSRFToken()); ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "username", true))); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4">Real name</label>
                    <input type="text" class="form-control" id="realName" name="realName" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "real_name", true))); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="inputAddress">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "email", true))); ?>">
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Password</label>
                    <input type="password" class="form-control" id="password" name="password" onmouseover="onPasswordHover()" onmouseout="onPasswordHoverEnd()" value="xxxxxxxxxxxx" data-toggle="popover" title="Attention! Dummy data" data-content="For security reasons, your password (once stored) is not retrievable. This password box contains dummy data - not your password. If you forgot your password, please reset it on the accounts menu.">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4">Password hint</label>
                    <input type="text" class="form-control" id="passwordHint" name="passwordHint" value="<?php echo (stripslashes(getInfoFromUserID($_GET["editUser"], "password_hint", true))); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="/admin/settings/accounts.php" class="btn btn-danger">Discard changes</a>
        </form>


    <?php } ?>

</div>

</html>