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

/* Bootstrapper definitions */
define("STEP_NAME", "DSJAS install welcome");
define("STEP_URL", "/admin/install/install.php");

require "install_bootstrap.php";


if (isset($_GET["regenToken"])) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> The token has been regenerated.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
} elseif (isset($_GET["completePrimary"])) {
    completePrimarySetup();

    header("Location: /admin/install/verification.php");
    die();
}


generateVerificationToken(ABSPATH . "/setuptoken.txt");

?>


<body class="container-fluid text-center">

    <div class="jumbotron-fluid">

        <img class="rounded-circle" src="/assets/scammer-logo.jpg" width=300 height=300>

        <h1 class="display-4">Welcome to D.S Johnson & Son</h1>
        <p class="lead">You're moments away from being all set up to annoy some scammers, but first we need to set some things up</p>
        <hr class="my-4 bg-dark">
        <p>In order to set up the site, you must prove that you are the owner of the server it is running on, or at least have access to it. To do so, please follow the below method:</p>

    </div>

    <div class="card-deck">
        <div class="card">
             <div class="card-header">
                <h5>Step 1</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Access your server and navigate to the root of the webserver</h5>
                <p class="card-text">You can do this remotely, for example with ssh, or locally by accessing the server machine. Your server root is the folder where the application is installed. The path is displayed below.</p>
            </div>
        </div>

        <div class="card">
             <div class="card-header">
                <h5>Step 2</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Find the setup token file</h5>
                <p class="card-text">Find a file named <strong>setuptoken.txt</strong>. This file will contain the token you will need to access the server in the next step. If you don't have this file, click <i>Regenerate Token</i> below. This will make the file and enter the token you need.</p>
            </div>
        </div>

        <div class="card">
             <div class="card-header">
                <h5>Step 3</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Copy or note down the token</h5>
                <p class="card-text">This token will allow you to verify your ownership in the next step. Please make sure the token is exactly the same as it was in the file or you <strong>you will not get access.</strong></p>
            </div>
        </div>
    </div>

    <hr>
    <p><strong>Your server root is located at: </strong> <?php echo (ABSPATH) ?></p>

    <hr>

    <span style="padding: 25">
        <a class="btn btn-rounded btn-primary" href="/admin/install/install.php?completePrimary">Continue to verification</a>
        <a class="btn btn-rounded btn-warning" href="/admin/install/install.php?regenToken">Regenerate token</a>
        <a class="btn btn-rounded btn-secondary" href="https://github.com/DSJAS/DSJAS/blob/master/docs/install/Verifying%20ownership.md">More information</a>
    </span>

</body>