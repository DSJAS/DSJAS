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

$configuration = parse_ini_file(ABSPATH . "/Config.ini");

if (installRequired($configuration)) {
    redirectToInstall($configuration);
}


?>

<body class="container-fluid">

    <div class="jumbotron-fluid text-center">

        <img class="rounded-circle" src="/assets/scammer-logo.jpg" width=300 height=300>

        <h1 class="display-4">Success! You're all set up and ready to go</h1>
        <p class="lead">D.S Johnson & Son has been fully set up and should be ready to go. Thanks for installing!</p>
        <hr class="my-4 bg-dark">
        <a class="btn btn-primary" href="/">Go to homepage</a>
        <a class="btn btn-warning" href="/admin/user/SignIn.php?post_install">Admin dashboard</a>

    </div>

</body>