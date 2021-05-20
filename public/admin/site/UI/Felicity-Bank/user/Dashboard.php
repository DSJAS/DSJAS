<?php

/*
FELICITY BANK PORT - DSJAS
==========================

Welcome to the DSJAS theme port of Felicity Bank!

Felicity bank is an open source and free template for making
fake banking sites. This port is an exact replica of the site
ported to the DSJAS theme API, allowing for dynamically changing
the name, branding etc.

Almost nothing has been changed about the original, but some features
had to be removed due to their conflict with existing modules or
other plugins.

Please check out the Felicity Bank GitHub page here: https://github.com/0xB9/Felicity-Bank-Inc.


For more information of theming and creating your own themes, please refer to the
API documentation for themes and plugins.
*/

require THEME_API . "Dashboard.php";

function getTheme()
{
?>

    <link rel="stylesheet" href=<?php echo (getThemeContent("dashboard.css", "styles/")) ?>>

    <body>
        <!--[if lt IE 7]>
      <p class="browsehappy">
        You are using an <strong>outdated</strong> browser. Please
        <a href="#">upgrade your browser</a> to improve your experience.
      </p>
    <![endif]-->

        <nav class="navbar navbar-expand-sm navbar-light bg-light border" style="height: 8vh; width: 100%;">
            <div class="container">
                <a href="#" class="navbar-brand"><?php echo getBankName(); ?></a>

                <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarMenu">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-link">
                            <a href="#">Business</a>
                        </li>
                        <li class="nav-link">
                            <a href="/invest">Investing</a>
                        </li>
                        <li class="nav-link">
                            <a href="https://en.wikipedia.org/wiki/Technical_support_scam" target="_blank">Report Fraud</a>
                        </li>
                        <li class="nav-link">
                            <a href="/contact">Contact</a>
                        </li>
                        <li class="nav-link">
                            <a href="/user/Logout?logout=1">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- end of navbar -->

        <div style="height: 3vh;"></div>

        <!-- end of space, main content: -->
        <div class="container">
            <div class="page-header">
                <div class="alert alert-info">
                    A new branch opened on 69<sup>th</sup> street next to the Piggly
                    Wiggly market. Contact your branch manager today.
                </div>
                <div class="alert alert-danger">
                    Due to a recent spike in fraud transfers, in order to transfer funds between your accounts please call our
                    hotline 1-800-FRAUD-STOP
                </div>

                <!-- end of alerts/messages at the top -->

                <div class="row">
                    <div class="col-sm-8">
                        <h3>Accounts</h3>
                        <hr />
                        <!-- start of table -->
                        <table class="table table-striped border">
                            <thead>
                                <tr>
                                    <th>Account Type</th>
                                    <th>Account Number</th>
                                    <th>Current Balance</th>
                                    <th>Available Balance</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach (getAccountsArray() as $account) { ?>
                                    <tr>
                                        <td><?php echo $account["account_name"] ?></td>
                                        <td><?php echo censorAccountNumber($account["account_number"]); ?></td>
                                        <td>$<?php echo $account["account_balance"] ?></td>
                                        <td>$<?php echo $account["account_balance"] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">What's Your Credit Score?</h5>
                                        <p class="card-text">
                                            Find out what your credit score is right now, seriously
                                            it's really easy.
                                        </p>
                                        <a href="#" class="card-link">Learn More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Join AARP Rewards</h5>
                                        <p class="card-text">
                                            You could be saving serious coin on everyday purchases.
                                        </p>
                                        <a href="#" class="card-link">Learn More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Record Low Interest Rates</h5>
                                        <p class="card-text">
                                            You're already approved. It takes just a moment to open an
                                            account.
                                        </p>
                                        <a href="#" class="card-link">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="height: 2vh;"></div>
                        <a target="_blank" href="/contact" class="btn btn-lg btn-warning">
                            Contact Support
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <h3>Pending Transactions</h3>
                        <hr />
                        <table class="table table-striped border">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach (getRecentTransactionsArray(12) as $transaction) { ?>
                                    <tr>
                                        <td><?php echo $transaction["transaction_date"] ?></td>
                                        <td><?php echo $transaction["transaction_description"] ?></td>
                                        <td>
                                            <?php if (isPricePositive($transaction["transaction_amount"])) { ?>
                                                <span id="greenhighlight">$<?php echo $transaction["transaction_amount"] ?></span>
                                            <?php } else { ?>
                                                <span id="redhighlight">$<?php echo $transaction["transaction_amount"] ?></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div style="height: 8vh;"></div>
    </body>
<?php }
