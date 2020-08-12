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


if (isset($_POST["migrateAccount"])) {
    $csrf = getCSRFSubmission();
    if (!verifyCSRFToken($csrf)) {
        die(getCSRFFailedError());
    }

    $configuration = parse_ini_file(ABSPATH . "/Config.ini");

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    if (isset($_POST["accountID"]) && isset($_POST["destinationUser"])) {
        associateAccountWithUser($_POST["destinationUser"], $_POST["accountID"]);
    } else {
        header("Location: /admin/bank/accounts.php?migrationFailed");
        die();
    }

    if (isset($_POST["drainAccount"])) {
        drainAccount($_POST["accountID"]);
    }

    if (isset($_POST["updateHolderName"])) {
        $newName = getInfoFromUserID($_POST["destinationUser"], "real_name");

        $query = new PreparedStatement(
            "UPDATE `accounts` SET `holder_name` = ? WHERE `account_identifier` = ?",
            [$newName, $_POST["accountID"]],
            "si"
        );

        $database->prepareQuery($query);
        $database->query();
    }

    if (isset($_POST["disableAccount"])) {
        disableAccount($_POST["accountID"]);
    }

    if (isset($_POST["deleteTransactions"])) {
        $query = new PreparedStatement(
            "DELETE FROM `transactions` WHERE `origin_account_id` = ?",
            [$_POST["accountID"]],
            "i"
        );

        $database->prepareQuery($query);
        $database->query();
    }

    header("Location: /admin/bank/accounts.php?accountMigrated");
    die();
}

regenerateCSRF();

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<script src="/include/js/migrateAccount.js"></script>

<form class="container-fluid" id="content" action="/admin/bank/migrateAccount.php" method="POST">

    <input type="text" style="visibility: hidden; position: absolute" name="migrateAccount" value="1">
    <?php getCSRFFormElement(); ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Account migration wizard</h1>
    </div>

    <div class="card admin-panel">
        <div class="card-header">
            <h3>Welcome</h3>
        </div>

        <div class="card-body">
            <strong>Welcome to the account migration wizard!</strong>
            <hr>

            <p>This wizard will guide you through the steps necessary to migrate an account to another owner in DSJAS. Some information or steps may already be completed if you were linked here by another page.</p>

            <p>Please fill in all the required information to ensure that DSJAS can properly move all funds between users to migrate this account.</p>

            <small class="text-muted">Click next to continue</small>
            <br>

            <button type="button" onclick="selectAccount()" class="btn btn-primary mt-3">Next</button>
        </div>
    </div>

    <div class="card admin-panel d-none" id="selectAccount">
        <div class="card-header">
            <h3>Select account</h3>
        </div>

        <div class="card-body">
            <strong>Please select the account you wish to migrate to another user.</strong>
            <hr>

            <div id="accountSelectAccount" class="form-group">
                <label for="userSelect">Select the account to migrate:</label>
                <select class="form-control account-result" name="accountID">
                    <?php foreach (getAllAccounts() as $account) {
                        $accountOwner = getInfoFromUserID($account["associated_online_account_id"], "username");
                        ?>
                        <option value="<?php echo $account["account_identifier"] ?>"><strong><?php echo $accountOwner ?></strong> - <?php echo $account["account_name"]; ?> [<?php echo $account["account_identifier"]; ?>]</option>
                    <?php } ?>
                </select>

                <hr>

                <small class="text-muted">Click next to continue</small>
                <br>

                <button type="button" onclick="selectDestination()" class="btn btn-primary mt-3">Next</button>

            </div>
        </div>
    </div>

    <div class="card admin-panel d-none" id="selectDestination">
        <div class="card-header">
            <h3>Select destination</h3>
        </div>

        <div class="card-body">
            <strong>Please select the user which the account should be migrated to. The account will then be associated with that user</strong>
            <hr>

            <div id="accountSelectUser" class="form-group">
                <label for="userSelect">Select the user who will own the account after the migration:</label>
                <select class="form-control" id="userSelect" name="destinationUser">
                    <?php foreach (getAllUsers() as $user) { ?>
                        <option value="<?php echo $user["user_id"]; ?>"><?php echo $user["username"]; ?></option>
                    <?php } ?>
                </select>
            </div>

            <hr>

            <small class="text-muted">Click next to continue</small>
            <br>

            <button type="button" onclick="selectFinal()" class="btn btn-primary mt-3">Next</button>

        </div>
    </div>

    <div class="card admin-panel d-none" id="final">
        <div class="card-header">
            <h3>Extra information</h3>
        </div>

        <div class="card-body">
            <strong>Please choose the following optional pieces of information</strong>
            <hr>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="drainAccount" name="drainAccount">
                <label class="form-check-label" for="drainAccount">Drain this account after migrating</label>
                <small id="drainHelp" class="form-text text-muted">The account's balance will be set to zero and all existing funds will be drained.</small>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="changeHolder" name="updateHolderName" checked>
                <label class="form-check-label" for="updateHolderName">Automatically change the account's holder name (recommended)</label>
                <small id="updateHolderHelp" class="form-text text-muted">The new holder name will be generated based on the real name property of the destination account.</small>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="disableAccount" name="disableAccount">
                <label class="form-check-label" for="disableAccount">Disable this account after migration</label>
                <small id="disableHelp" class="form-text text-muted">The account will not be able to receive or send transfers until you enable it.</small>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="deleteTransactions" name="deleteTransactions">
                <label class="form-check-label" for="deleteTransactions">Remove existing transactions</label>
                <small id="delTransactionsHelp" class="form-text text-muted">Any transactions already made from this account will be deleted. Any transactions <strong>to</strong> this account will be preserved.</small>
            </div>

            <hr>

            <small class="text-muted">Click migrate below to complete the wizard and perform the migration</small>
        </div>
    </div>

    <div class="card admin-panel d-none" id="submit">
        <input type="submit" class="btn btn-warning form-control" value="Migrate">
    </div>
</form>

</html>