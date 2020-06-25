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

require "install_bootstrap.php";

require "../../include/install/Database.php";
require "../../include/install/Utils.php";
require "../../include/install/TrackState.php";

$configuration = parse_ini_file("../../Config.ini");

if (!installRequired($configuration)) {
    header("Location: /");
} elseif (findRedirectLocation($configuration) != "/admin/install/db_config.php") {
    redirectToInstall($configuration);
}

if (!verifySetupAuth()) { ?>
    <div class="alert alert-danger" role="alert">
        <p><strong>Security error</strong> You are not authorised to run the setup process. Your authentication token is not authorised to continue the process.</p>
    </div>
    <?php
    die();
}

if (isset($_GET["feedback_success"])) { ?>
    <div class="alert alert-success" role="alert">
        <p><strong>Success</strong> You have successfully authenticated as the owner of the website and can now continue the setup process.</p>
    </div>
<?php }

if (isset($_GET["nodb"])) {
    if (isset($_GET["confirm"]) && $_GET["confirm"] == 1) {
        update_config("database", "running_without_database", "1");
        update_config("setup", "database_installed", "1");

        header("Location: /admin/install/db_config.php");
        die();
    } else {
        handleNoDBConfirmation();
        die();
    }
}

if (isset($_POST["submit"])) {
    saveDatabaseInformation();
    setupDatabaseServer();

    completeDatabaseStage();
} elseif (isset($_GET["manualSetup"])) {
    update_config("setup", "database_installed", "1");
    update_config("database", "running_without_database", "0");

    header("Location: /admin/install/final");
}

?>

<body class="container-fluid">

    <div class="jumbotron-fluid text-center">

        <img class="rounded-circle" src="/assets/scammer-logo.jpg" width=300 height=300>

        <h1 class="display-4">Database setup</h1>
        <p class="lead">D.S J & Son needs a working SQL database server to store certain information. This will need to be setup before continuing.</p>
        <hr class="my-4 bg-dark">
        <p>You will need to set up a user for the web app with a password. Once you have done this, return to the form and fill in all the information. When you provide the necessary details, we will setup the database for you.</p>

    </div>

    <form class="container text-right">

        <div class="form-group row">
            <label for="servername" class="col col-2">Server hostname:</label>
            <input class="col col-8" type="text" placeholder="E.g: localhost" id="servername" style="margin-left: 30px">
        </div>
        <div class="form-group row">
            <label for="dbname" class="col col-2">Database name:</label>
            <input class="col col-8" type="text" placeholder="E.g DSJAS" id="dbname" style="margin-left: 30px">
        </div>
        <div class="form-group row">
            <label for="username" class="col col-2">Username:</label>
            <input class="col col-8" type="text" placeholder="Username" id="username" style="margin-left: 30px">
        </div>
        <div class="form-group row">
            <label for="password" class="col col-2">Password:</label>
            <input class="col col-8" type="password" placeholder="Password" id="password" style="margin-left: 30px">
        </div>
    </form>

    <hr>
    <span>
        <a class="btn btn-primary" onclick="confirmAndSetup()">Confirm and setup</a>
        <a class="btn btn-secondary" onclick="testConfiguration()" id="configCheck" data-toggle="popover" title="Checking configuration..." data-content="Sending configuration to server. Please wait...">Test configuration</a>
        <a class="btn btn-warning" href="/admin/install/db_config.php?manualSetup">I wish to manually set up my database</a>
        <a class="btn btn-danger" href="/admin/install/db_config.php?nodb&confirm=0">Continue without a database</a>
    </span>

    <br>
    <br>
    <p class="lead small"><strong>Note:</strong> The database information that you enter will be used to perform further setup actions when you confirm them. If you do not what this, select to setup manually</p>

</body>