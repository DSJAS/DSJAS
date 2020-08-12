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
require ABSPATH . INC . "Banking.php";
require ABSPATH . INC . "csrf.php";

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

createUser($_POST["username"], $_POST["email"], $_POST["password"], $_POST["passwordHint"], true, false, $_POST["realName"]);

if (isset($_POST["generateBankAccounts"])) {
    genRandomBankAccounts(getUserIDFromName($_POST["username"]));
}

header("Location: /admin/bank/users.php?userCreated");

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="container" id="content">
    <div class="alert alert-info">
        <p>Creating your account, please wait...</p>

        <p>Page stuck? <a href="/admin/bank/users.php">Click here</a></p>
    </div>
</div>

</html>