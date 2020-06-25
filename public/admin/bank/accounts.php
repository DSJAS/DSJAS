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

require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Administration.php";
require ABSPATH . INC . "Banking.php";
require ABSPATH . INC . "csrf.php";

require_once ABSPATH . INC . "Customization.php";

if (isset($_GET["doCloseAccount"])) {
    $csrf = getCSRFSubmission("GET");
    if (!verifyCSRFToken($csrf)) {
        die(getCSRFFailedError());
    }

    closeAccount($_GET["doCloseAccount"]);

    header("Location: /admin/bank/accounts.php?accountDeleted");
    die();
} else if (isset($_GET["doChangeBalance"])) {
    $csrf = getCSRFSubmission("GET");
    if (!verifyCSRFToken($csrf)) {
        die(getCSRFFailedError());
    }

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `account_balance` = ? WHERE `account_identifier` = ?",
        [$_GET["newBalance"], $_GET["doChangeBalance"]],
        "di"
    );

    $database->prepareQuery($query);
    $database->query();

    header("Location: /admin/bank/accounts.php?changesSaved");
    die();
} else if (isset($_GET["doDrainAccount"])) {
    $csrf = getCSRFSubmission("GET");
    if (!verifyCSRFToken($csrf)) {
        die(getCSRFFailedError());
    }

    $database = $database = new DB(
        $configuration["server_hostname"],
        $configuration["database_name"],
        $configuration["username"],
        $configuration["password"]
    );

    $query = new PreparedStatement(
        "UPDATE `accounts` SET `account_balance` = 0.00 WHERE `account_identifier` = ?",
        [$_GET["doDrainAccount"]],
        "i"
    );

    $database->prepareQuery($query);
    $database->query();

    header("Location: /admin/bank/accounts.php?changesSaved");
    die();
} else if (isset($_GET["doForceTransaction"])) {
    $csrf = getCSRFSubmission("GET");
    if (!verifyCSRFToken($csrf)) {
        die(getCSRFFailedError());
    }

    performTransaction($_GET["sourceAccount"], $_GET["destinationAccount"], $_GET["amount"], $_GET["description"], $_GET["type"]);

    header("Location: /admin/bank/accounts.php?changesSaved");
    die();
}

if (isset($_GET["closeAccount"])) { ?>
    <div class="text-center">
        <h1 style="color: red">Warning</h1>
        <p class="lead">Closing an account is permanent and associated data cannot be recovered. All funds will be drained and the account will not show for any bank users.</p>
        <p><strong>Are you sure you wish to continue?</strong></p>
        <hr>
        <a class="btn btn-danger" href="/admin/bank/accounts.php?doCloseAccount=<?php echo ($_GET["closeAccount"]); ?>&csrf=<?php echo ($_GET["csrf"]); ?>">Confirm</a>
        <a class="btn btn-secondary" href="/admin/bank/accounts.php">Cancel</a>
    </div>
    <?php

    die();
} else if (isset($_GET["changeBalance"])) { ?>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Change account balance</h1>
            <p class="lead">You are changing the account balance of account number <strong><?php echo $_GET["changeBalance"] ?></strong>.<br> The account balance will be changed to the specified number. Existing transactions will be unaffected.</p>

            <hr>

            <form action="/admin/bank/accounts.php" method="GET">
                <input type="text" style="visibility: hidden; position: absolute" name="doChangeBalance" value="<?php echo ($_GET["changeBalance"]); ?>">
                <?php getCSRFFormElement(); ?>

                <div class="input-group mb-3 align-left">
                    <label for="amount" class="col-sm-2 col-form-label">New balance:</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" class="form-control" name="newBalance" id="amount" step="0.01" required>
                </div>

                <div class="input-group mb-3 align-left">
                    <input type="submit" value="Confirm" class="btn btn-warning">
                </div>

                <small class="text-muted">This action does not store a transaction record and is therefore not visible from the account dashboard. However, this means it is also not reversible. Please verify the value before confirming.</small>
            </form>
        </div>
    </div>
    <?php

    die();
}


regenerateCSRF();

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div id="content">
    <?php if (isset($_GET["accountMigrated"])) { ?>
        <div class="alert alert-success">
            <p><strong>Successfully migrated the specified account</strong> The specified account has been migrated and any associated tasks have completed. You may have to refresh bank pages for the changes to take effect.</p>
        </div>
    <?php } ?>

    <?php if (isset($_GET["migrationFailed"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Failed to migrate user account</strong> The specified user account could not be deleted due to an error. Please ensure that you provided all required information.</p>
        </div>
    <?php } ?>

    <?php if (isset($_GET["accountCreated"])) { ?>
        <div class="alert alert-success">
            <p><strong>Account creation success</strong> An account has been created and stored with the specified information. You may need to reload any open bank pages for the changes to take effect.</p>
        </div>
    <?php } ?>

    <?php if (isset($_GET["accountDeleted"])) { ?>
        <div class="alert alert-warning">
            <p><strong>Account closed</strong> The specified account was deleted and any remaining funds were drained.</p>
        </div>
    <?php } ?>

    <?php if (isset($_GET["creationFailed"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Failed to create account</strong> An error ocurred while attempting to create that account. Please try again and ensure that all required information has been provided.</p>
        </div>
    <?php } ?>

    <?php if (isset($_GET["changesSaved"])) { ?>
        <div class="alert alert-success">
            <p><strong>Changes saved</strong> The requested changes have been stored. Affected accounts have been updated.</p>
        </div>
    <?php } ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Manage bank accounts</h1>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Existing accounts</h3>
        </div>

        <div class="card-body">
            <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#accountsCollapse">
                Show accounts
            </button>

            <div class="collapse mt-2" id="accountsCollapse">
                <table class="table">
                    <thead class="thead-dark">
                        <th>ID</th>
                        <th>Account name</th>
                        <th>Account type</th>
                        <th>Account owner</th>
                        <th>Balance</th>
                        <th>Disabled</th>
                        <th>Actions</th>
                    </thead>

                    <tbody>
                        <?php foreach (getAllAccounts() as $account) { ?>
                            <tr>
                                <td><strong><?php echo ($account["account_identifier"]); ?></strong></td>
                                <td><?php echo ($account["account_name"]); ?></td>
                                <td><?php echo ($account["account_type"]); ?></td>
                                <td><?php echo (getInfoFromUserID($account["associated_online_account_id"], "username")); ?></td>
                                <td>$<?php echo ($account["account_balance"]); ?></td>
                                <td>
                                    <?php if ($account["account_disabled"]) { ?>
                                        <p class="text-danger">Yes</p>
                                    <?php } else { ?>
                                        <p class="text-success">No</p>
                                    <?php } ?>
                                </td>

                                <td class="btn-group">
                                    <a href="/admin/bank/accounts.php?changeBalance=<?php echo ($account["account_identifier"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-primary">Change balance</a>
                                    <a href="/admin/bank/accounts.php?doDrainAccount=<?php echo ($account["account_identifier"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-warning">Drain account</a>
                                    <a href="/admin/bank/accounts.php?closeAccount=<?php echo ($account["account_identifier"]); ?>&csrf=<?php echo (getCSRFToken()); ?>" class="btn btn-danger">Close account</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Create a new account</h3>
        </div>

        <div class="card-body">
            <a href="/admin/bank/createAccount.php" class="btn btn-success">Account creation wizard</a>

            <hr>
            <small class="text-muted">
                The account creation wizard will guide you through the process of creating an account and managing initial funds, account owners etc.
            </small>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Migrate an account</h3>
        </div>

        <div class="card-body">
            <a href="/admin/bank/migrateAccount.php" class="btn btn-success">Account migration wizard</a>

            <hr>
            <small class="text-muted">
                The account migration wizard will allow you to move accounts between users at any time and change important details such as holder name and account numbers
            </small>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Force a transaction</h3>
        </div>

        <div class="card-body">
            <h4>Force a transaction on an account</h4>
            <p>Forcing a transaction on an account will cause that account to add a transaction to the transaction database and the account's balance to be adjusted</p>

            <form action="/admin/bank/accounts.php" method="GET">
                <input type="text" style="visibility: hidden; position: absolute" name="doForceTransaction" value="1">
                <?php getCSRFFormElement(); ?>

                <div class="form-group">
                    <label for="sourceAccountSelection">Source account</label>
                    <select class="form-control" id="sourceAccountSelection" name="sourceAccount">
                        <?php foreach (getAllAccounts() as $account) { ?>
                            <option value="<?php echo $account["account_identifier"] ?>">[<?php echo $account["account_identifier"] ?>] <?php echo $account["account_name"] ?> - <?php echo getInfoFromUserID($account["associated_online_account_id"], "username");  ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="destAccountSelection">Destination account</label>
                    <select class="form-control" id="destAccountSelection" name="destinationAccount">
                        <?php foreach (getAllAccounts() as $account) { ?>
                            <option value="<?php echo $account["account_identifier"] ?>">[<?php echo $account["account_identifier"] ?>] <?php echo $account["account_name"] ?> - <?php echo getInfoFromUserID($account["associated_online_account_id"], "username");  ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="type">Transaction type</label>
                    <select class="form-control" id="type" name="type">
                        <?php foreach (TRANSACTION_TYPES as $type) { ?>
                            <option value="<?php echo $type ?>"><?php echo $type ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="input-group mb-3 align-left">
                    <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" class="form-control" name="amount" id="amount" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="desc">Description</label>
                    <input type="text" name="description" id="desc" class="form-control" required>
                </div>

                <input type="submit" value="Transfer" class="btn btn-success">
            </form>
        </div>
    </div>
</div>

</html>