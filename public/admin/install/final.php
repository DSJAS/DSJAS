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

require "install_bootstrap.php";

require "../../include/install/Utils.php";
require "../../include/install/TrackState.php";

$configuration = parse_ini_file("../../Config.ini");

if (!installRequired($configuration)) {
    header("Location: /");
} elseif (findRedirectLocation($configuration) != "/admin/install/final.php") {
    redirectToInstall($configuration);
}

if (!verifySetupAuth()) { ?>
    <div class="alert alert-danger" role="alert">
        <p><strong>Security error</strong> You are not authorized to run the setup process. Your authentication token is not authorized to continue the process.</p>
    </div>
    <?php
    die();
}

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
    <?php } elseif ($_GET["error"] == "weakpass") { ?>
        <div class="alert alert-danger" role="alert">
            <p><strong>Weak password</strong> The password entered was not strong enough and has been refused by the server. Please ensure it is at least 5 characters long and includes a number</p>
        </div>
    <?php }
}

if (isset($_POST["submitFinal"])) {
    setupPrimaryAdministrator();
    setupPrimarySettings();

    finalizeInstall(); /* After this line of code, we will be redirected to the front page and installation is over */
} elseif (isset($_POST["skipFinal"])) {
    handleSkipFinal();

    finalizeInstall(); /* After this line of code, we will be redirected to the front page */
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
                    <label for="passwordHintInput">Password</label>
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
                    <input checked type="checkbox" class="form-check-input" id="administrativeCheck">
                    <label class="form-check-label" for="administrativeCheck">Allow access to administrator areas by default (recommended)</label>
                </div>
            </form>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col">
            <button onclick="submitFinal()" class="btn btn-primary form-button-fill">Confirm and complete setup</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="#" onclick="skipStepFinal()">Skip this step</a>
        </div>
    </div>

</body>