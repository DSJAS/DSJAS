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

require(ABSPATH . INC . "csrf.php");
require(ABSPATH . INC . "Administration.php");

ignore_user_abort(true); // Don't allow the user to cancel this install by closing the loading browser


if (isset($_GET["validateTheme"])) {
    $csrf = getCSRFSubmission("GET");
    if (!verifyCSRFToken($csrf)) {
        die(getCSRFFailedError());
    }

    $timestamp = time();
    $humanTime = date("jS F Y [h:i A]", $timestamp);

    updateValidatorTimestamp();
    addAdministrationNotice(
        "validationRan",
        "Admin event: theme validator ran",
        "The theme validator was ran by an administrator at $humanTime. The result will be delivered when available",
        "/admin/settings/mod.php",
        "See validator settings/results",
        0
    );

    updateValidatorState("no_issues");
    addAdministrationNotice(
        "validationResult",
        "Admin event: the theme validator has completed",
        "The theme validator was ran by an administrator at $humanTime. The validation has completed and has returned no results",
        "/admin/settings/mod.php",
        "See full validation results",
        5
    );
} else {
    header("Location: /admin/settings/mod.php");
    die();
}

?>

<?php require(ABSPATH . INC . "/components/AdminSidebar.php"); ?>

<div id="content">
    <div class="alert alert-success">
        <strong>Validator ran</strong>
        The validator has finished running and results have been stored.

        <a href="/admin/settings/mod.php#validatorResults">View full results</a>
    </div>
</div>