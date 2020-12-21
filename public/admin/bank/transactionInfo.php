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

require_once ABSPATH . INC . "Util.php";

if (!isset($_GET["id"])) {
    header("Location: /admin/bank/transactions.php");
}

$database = $database = new DB(
    $configuration["server_hostname"],
    $configuration["database_name"],
    $configuration["username"],
    $configuration["password"]
);

$information = new PreparedStatement(
    "SELECT * FROM `transactions` WHERE `transaction_id` = ?",
    [$_GET["id"]],
    "i"
);

$database->prepareQuery($information);
$database->query();

$info = $information->result[0];

if ($information->affectedRows < 1) {
    dsjas_alert("No such transaction", "A transaction with the specified ID does not exist", "danger");
    die();
}

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div id="content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Viewing transaction information for transaction ID <strong><?php echo $info["transaction_id"] ?></strong></h1>
    </div>

    <div class="alert alert-info">
        <strong>We're sorry, this is read only</strong> DSJAS transactions cannot be edited after they are stored. Therefore, the below information is <i>read only</i>.

        If you need to, you can <a href="/admin/bank/transactions.php">reverse a transaction</a>
    </div>

    <form action="/admin/bank/transactions.php">
        <h3>Basic information</h3>

        <div class="form-group">
            <label for="id">Transaction ID:</label>
            <input class="form-control" type="text" value="<?php echo $info["transaction_id"] ?>" id="id" readonly>
            <small class="form-text text-muted">This is used internally by DSJAS and is never displayed to the user</small>
        </div>

        <div class="form-group">
            <label for="date">Timestamp:</label>
            <input class="form-control" type="text" value="<?php echo $info["transaction_date"] ?>" id="date" readonly>
            <small class="form-text text-muted">This timestamp is in the local time of your server (usually the local time at the hosting location)</small>
        </div>

        <hr>

        <h3>Monetary information</h3>

        <div class="form-group">
            <label for="amount">Amount transferred:</label>
            <input class="form-control" type="text" value="$<?php echo $info["transaction_amount"] ?>" id="amount" readonly>
        </div>

        <hr>

        <h3>Account information</h3>

        <div class="form-group">
            <label for="sender">Sender account:
                <span data-toggle="tooltip" data-placement="right" title="The account number of the account which sent the money from the transaction">
                    <svg class="bi bi-question-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.57 6.033H5.25C5.22 4.147 6.68 3.5 8.006 3.5c1.397 0 2.673.73 2.673 2.24 0 1.08-.635 1.594-1.244 2.057-.737.559-1.01.768-1.01 1.486v.355H7.117l-.007-.463c-.038-.927.495-1.498 1.168-1.987.59-.444.965-.736.965-1.371 0-.825-.628-1.168-1.314-1.168-.901 0-1.358.603-1.358 1.384zm1.251 6.443c-.584 0-1.009-.394-1.009-.927 0-.552.425-.94 1.01-.94.609 0 1.028.388 1.028.94 0 .533-.42.927-1.029.927z" />
                    </svg>
                </span>
            </label>
            <input class="form-control" type="text" value="<?php echo $info["origin_account_id"] ?>" id="sender" readonly>
        </div>

        <div class="form-group">
            <label for="dest">Recipient account:
                <span data-toggle="tooltip" data-placement="right" title="The account number of the account which received the money from the transaction">
                    <svg class="bi bi-question-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.57 6.033H5.25C5.22 4.147 6.68 3.5 8.006 3.5c1.397 0 2.673.73 2.673 2.24 0 1.08-.635 1.594-1.244 2.057-.737.559-1.01.768-1.01 1.486v.355H7.117l-.007-.463c-.038-.927.495-1.498 1.168-1.987.59-.444.965-.736.965-1.371 0-.825-.628-1.168-1.314-1.168-.901 0-1.358.603-1.358 1.384zm1.251 6.443c-.584 0-1.009-.394-1.009-.927 0-.552.425-.94 1.01-.94.609 0 1.028.388 1.028.94 0 .533-.42.927-1.029.927z" />
                    </svg>
                </span>
            </label>
            <input class="form-control" type="text" value="<?php echo $info["dest_account_id"] ?>" id="dest" readonly>
        </div>

        <hr>

        <h3>Visual information</h3>

        <div class="form-group">
            <label for="desc">Transaction description:</label>
            <input class="form-control" type="text" value="<?php echo $info["transaction_description"] ?>" id="desc" readonly>
        </div>

        <div class="form-group">
            <label for="type">Transaction type:</label>
            <input class="form-control" type="text" value="<?php echo $info["transaction_type"] ?>" id="type" readonly>
            <small class="form-text text-muted">The transaction type has no impact on financial workings and is purely visual. Some themes may use this for extra information to be displayed</small>
        </div>
    </form>

    <hr>

    <div class="mt-4">
        <a href="/admin/bank/transactions.php" class="btn btn-secondary">Return to transactions page:</a>
        <a href="/admin/bank/reverseTransaction.php?id=<?php echo $info["transaction_id"] ?>&csrf=<?php echo getCSRFToken(); ?>" class="btn btn-danger">Reverse transaction</a>
    </div>
</div>


</html>