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

require("../AdminBootstrap.php");

require(ABSPATH . INC . "Users.php");
require(ABSPATH . INC . "Banking.php");
require(ABSPATH . INC . "csrf.php");

if (!isset($_POST["createUser"])) {
    header("Location: /admin/bank/users.php");
    die();
}

$csrf = getCSRFSubmission();
if (!$csrf) {
    die(getCSRFFailedError());
}

if (!isset($_POST["username"]) || $_POST["username"] == "" || !isset($_POST["password"]) || $_POST["password"] == "") {
    die("<strong>Error</strong> Please supply all required parameters");
}

createUser($_POST["username"], $_POST["email"], $_POST["password"], $_POST["passwordHint"]);

if (isset($_POST["generateBankAccounts"])) {
    genRandomBankAccounts(getUserIDFromName($_POST["username"]));
}

header("Location: /admin/bank/users.php?userCreated");

?>

<html>
<?php require(ABSPATH . INC . "components/AdminSidebar.php"); ?>

<div class="container" id="content">
    <div class="alert alert-info">
        <p>Creating your account, please wait...</p>

        <p>Page stuck? <a href="/admin/bank/users.php">Click here</a></p>
    </div>
</div>

</html>