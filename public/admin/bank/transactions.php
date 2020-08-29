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


regenerateCSRF();

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div id="content">

    <?php if (isset($_GET["transactionReversed"])) { ?>
        <div class="alert alert-success">
            <p><strong>The specified transfer was reversed</strong> The specified transaction was reversed and both involved accounts have had their funds adjusted accordingly. You may have to refresh bank pages for the changes to take effect.</p>
        </div>
    <?php } ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Manage transactions</h1>
    </div>

    <div class="alert alert-info">
        <strong>Looking to create a transaction?</strong> Transactions can be created on the <a href="/admin/bank/accounts.php#forceTransaction">accounts management page</a>
    </div>

    <div class="card admin-panel">
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead thead-dark">
                    <th>ID</th>
                    <th>Timestamp</th>
                    <th>Description</th>
                    <th>Source account number</th>
                    <th>Destination account number</th>
                    <th>Amount</th>
                    <th></th>
                </thead>

                <tbody class="tbody">
                    <?php foreach (getAllTransactions() as $transaction) { ?>
                        <tr>
                            <td><strong><?php echo $transaction["transaction_id"] ?></strong></td>
                            <td><?php echo $transaction["transaction_date"] ?></td>
                            <td><?php echo $transaction["transaction_description"] ?></td>
                            <td><?php echo $transaction["origin_account_id"] ?></td>
                            <td><?php echo $transaction["dest_account_id"] ?></td>
                            <td><?php echo $transaction["transaction_amount"] ?></td>
                            <td>
                                <a class="text-danger" href="/admin/bank/reverseTransaction.php?id=<?php echo $transaction["transaction_id"] ?>&csrf=<?php echo getCSRFToken(); ?>">
                                    Reverse
                                </a>
                                <br>
                                <a class="text-primary" href="/admin/bank/transactionInfo.php?id=<?php echo $transaction["transaction_id"] ?>">More info</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</html>