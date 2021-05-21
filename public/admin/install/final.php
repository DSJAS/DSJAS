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
define("STEP_URL", "/admin/install/final.php");
define("STEP_PROTECT", true);

require "install_bootstrap.php";


if (!handleDBVerification()) { ?>
    <div class="alert alert-danger" role="alert">
        <p><strong>Database error</strong> The database doesn't seem to be working and we were unable to make a connection. Please verify that the details entered perviously were correct and that your database server is online. You may need to manually edit your configuration file on the server.</p>
    </div>
    <?php
    die();
}

if (isset($_GET["error"]) && $_GET["error"] != "") {
    if ($_GET["error"] == "missing") { ?>
        <div class="alert alert-danger" role="alert">
            <p><strong>Missing information</strong> The server reported that some information that is required is not present. Please ensure that you filled in all required fields in the form</p>
        </div>
    <?php
    } elseif ($_GET["error"] == "weakpass") { ?>
        <div class="alert alert-danger" role="alert">
            <p><strong>Weak password</strong> The password entered was not strong enough and has been refused by the server. Please ensure it is at least 5 characters long and includes a number</p>
        </div>
    <?php
    } elseif ($_GET["error"] == "unknown") { ?>
        <div class="alert alert-danger" role="alert">
            <p><strong>Unknown error</strong> An unknown error has occurred and the settings could not be saved</p>
        </div>
<?php }
}

if (isset($_POST["submitFinal"])) {
    setupPrimarySettings();
    setupPrimaryAdministrator();

    finalizeInstall(); /* After this line of code, we will be redirected to the front page and installation is over */
    die();
} elseif (isset($_POST["skipFinal"])) {
    handleSkipFinal();

    finalizeInstall(); /* After this line of code, we will be redirected to the front page */
    die();
}


?>

<body class="container-fluid text-center">

    <div class="jumbotron-fluid">
        <img class="rounded-circle" src="/assets/scammer-logo.jpg" width=300 height=300>

        <h1 class="display-4">Final setup</h1>
        <p class="lead">Congratulations! Your installation is now complete. Now, we just need to know a few more things before you can get started</p>
        <hr class="my-4 bg-dark">
    </div>

    <div class="row">
        <div class="col border">
            <h3 class="display-4">Administrative user setup</h3>
            <p class="lead">Please supply your preferred credentials for the new administrator account. You will login to this account after setup.</p>

            <form class="text-left">
                <div class="form-group">
                    <label for="usernameInput">Username</label>
                    <input type="username" class="form-control" id="usernameInput" placeholder="Username - required">
                </div>
                <div class="form-group">
                    <label for="emailInput">Email</label>
                    <input type="email" class="form-control" id="emailInput" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <input type="password" class="form-control" id="passwordInput" placeholder="Password - required">
                    <small id="passInfo" class="form-text text-muted">Please choose a strong password, at least 5 characters long and including a number</small>
                </div>
                <div class="form-group">
                    <label for="passwordHintInput">Password hint</label>
                    <input type="text" class="form-control" id="passwordHintInput" placeholder="Password hint">
                    <small id="hintInfo" class="form-text text-muted">If you ever forget your password, this will help you to remember. Don't make it too obvious. Remember, this should only make sense to you</small>
                </div>
            </form>
        </div>
        <div class="col border">
            <h3 class="display-4">Site configuration</h3>
            <p class="lead">The site has certain customization options you may wish to set before visiting the site for the first time. Don't worry, you can change these later.</p>

            <form class="text-left">
                <div class="form-group">
                    <label for="banknameInput">Bank name</label>
                    <input type="text" class="form-control" id="banknameInput" placeholder="D.S Johnson & Son">
                    <small id="banknameHelp" class="form-text text-muted">This is the text that will be used in the title bar, the navigation menu etc.</small>
                </div>
                <div class="form-group">
                    <label for="urlInput">Bank URL</label>
                    <input type="text" class="form-control" id="urlInput" placeholder="https://djohnson.financial">
                    <small id="urlHelp" class="form-text text-muted">This is the URL that you have set up the bank to be served on. If you have not done this, leave it blank</small>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="administrativeCheck">
                    <label class="form-check-label" for="administrativeCheck">Disable administrator dashboard (not recommended)</label>
                </div>
            </form>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col">
            <button onclick="submitFinal()" class="btn btn-primary form-button-fill">
                Confirm and complete setup
                <span id="saveProgress" class="spinner-border spinner-border-sm d-none"></span>
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="#" onclick="skipStepFinal()">Skip this step</a>
        </div>
    </div>

</body>