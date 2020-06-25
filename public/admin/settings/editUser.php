<?php

/**
 * Welcome to Dave-Smith Johnson & Son family bank!
 * 
 * This is a tool to assist with scam baiting, especially with scammers attempting to
 * obtain bank information or to attempt to scam you into giving money.
 * 
 * This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
 * is free to use and change for all users. Scam bait as much as you want!
 * 
 * This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
 * I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!
 * 
 * Please, waste these people's time as much as possible. It's fun and it does good for everyone.
 */

require "../AdminBootstrap.php";

require ABSPATH . INC . "DB.php";
require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Administration.php";

require ABSPATH . INC . "csrf.php";


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

    if (getCurrentUserId(true) == $_GET["doToggleEnabledUser"]) { ?>
        <div class="alert alert-danger">
            <p><strong>Cannot disable account</strong> You appear to be attempting to disable your own account. You cannot disable the account you are logged in to.
                To disable this account, please create or login to another account and order the disable from that other account.

                <a href="/admin/settings/accounts.php">Go back to the accounts page</a>
            </p>
        </div>
        <?php
        die();
    }

    if (getInfoFromUserID($_GET["doToggleEnabledUser"], "account_enabled", true)) {
        disableUser($_GET["doToggleEnabledUser"], true);
    } else {
        enableUser($_GET["doToggleEnabledUser"], true);
    }

    header("Location: /admin/settings/accounts.php");
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

    if (isset($_GET["deleteUser"])) { ?>
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
        if (getCurrentUserId(true) == $_GET["toggleEnabledUser"]) { ?>
            <div class="alert alert-danger">
                <p><strong>Cannot disable account</strong> You appear to be attempting to disable your own account. You cannot disable the account you are logged in to.
                    To disable this account, please create or login to another account and order the disable from that account.

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

    if (isset($_GET["editUser"])) { ?>

        <?php if (!getInfoFromUserID($_GET["editUser"], "account_enabled", true)) { ?>
            <div class="alert alert-warning">
                <p><strong>This account is disabled</strong> You're editing an account which is currently disabled. Regardless of changes, this user will not be able to access their account or profile.</p>
                <a href="/admin/settings/editUser.php?toggleEnabledUser=<?php echo ($_GET["editUser"]); ?>&csrf=<?php echo (getCSRFToken()); ?>">Enable this account</a>
            </div>
        <?php } ?>

        <?php if ($_GET["editUser"] == getCurrentUserId(true)) { ?>
            <div class="alert alert-info">
                <p><strong>This is you!</strong> You're editing your own account information. Any changes made will immediately be reflected on your profile.</p>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning">
                <p><strong>Take care: This isn't your account!</strong> You're editing your another person's account information. Please take care and avoid making unwanted changes.</p>
            </div>
        <?php } ?>

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