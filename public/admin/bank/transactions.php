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