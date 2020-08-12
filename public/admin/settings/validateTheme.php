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

require ABSPATH . INC . "ThemeValidator.php";

require ABSPATH . INC . "csrf.php";
require ABSPATH . INC . "Administration.php";

ignore_user_abort(true); // Don't allow the user to cancel this operation by closing the loading browser


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


    $validator = new ThemeValidator();
    $validator->run();

    $results = $validator->getResults();

    updateValidatorState($results[0]);

    switch ($results[0]) {
    case "fatal_error":
        addAdministrationNotice(
            "validationResult",
            "Admin event: the theme validator has completed",
            "The theme validator was ran by an administrator at $humanTime. The validation has failed and returned fatal errors - your theme may be invalid",
            "/admin/settings/mod.php",
            "More info",
            4
        );
        break;

    case "errors_found":
        addAdministrationNotice(
            "validationResult",
            "Admin event: the theme validator has completed",
            "The theme validator was ran by an administrator at $humanTime. The validation has failed due to an error in the theme - your theme will behave unexpectedly",
            "/admin/settings/mod.php",
            "See full validation results",
            4
        );
        break;

    case "passed_warnings":
        addAdministrationNotice(
            "validationResult",
            "Admin event: the theme validator has completed",
            "The theme validator was ran by an administrator at $humanTime. The validation has passed, but warnings were returned - your theme may function unexpectedly",
            "/admin/settings/mod.php",
            "See full validation results",
            3
        );
        break;

    case "passed":
        addAdministrationNotice(
            "validationResult",
            "Admin event: the theme validator has completed",
            "The theme validator was ran by an administrator at $humanTime. The validation has completed and has returned no issues",
            "/admin/settings/mod.php",
            "See full validation results",
            5
        );
        break;

    default:
        addAdministrationNotice(
            "validationResult",
            "Admin event: the theme validator has completed",
            "The theme validator was ran by an administrator at $humanTime. The validation has completed and has returned no results",
            "/admin/settings/mod.php",
            "See full validation results",
            5
        );
        break;
    }
} else {
    header("Location: /admin/settings/mod.php");
    die();
}

?>

<?php require ABSPATH . INC . "/components/AdminSidebar.php"; ?>

<div id="content">
    <div class="alert alert-success">
        <strong>Validator ran</strong>
        The validator has finished running and results have been stored.

        <a href="/admin/settings/mod.php#validatorResults">View full results</a>
    </div>

    <?php
    switch ($results[0]) {
    case "fatal_error": ?>
            <div class="alert alert-danger">
                <strong>Results summary: Fatal error</strong>
                The validator ran and encountered an invalid theme, making it impossible to continue/load.

                The current theme caused a fatal error, meaning that it would likely crash a site if used. We recommend uninstalling this theme until the developer fixes the problem.

                If all else fails, check that your configuration is valid. Switch to and from this theme to refresh the config cache.

                <i>This theme will not work, please do not attempt to use it</i>
            </div>
        <?php
        break;

    case "errors_found": ?>
            <div class="alert alert-danger">
                <strong>Results summary: Failed</strong>
                The validator ran and reported a fail. This means that the theme is either invalid or fails to provide the basic elements a valid theme requires.

                We recommend that you <strong>do not use this theme</strong> for stability reasons, until the developer has fixed the issues it causes.
            </div>
        <?php
        break;

    case "passed_warnings": ?>
            <div class="alert alert-warning">
                <strong>Results summary: Passed with warnings</strong>
                The validator ran and reported a pass with warnings. This means that the theme is probably working fine, but may behave strangely in some circumstances.

                <i>It will probably be fine, right?</i>
            </div>
        <?php
        break;

    case "passed": ?>
            <div class="alert alert-success">
                <strong>Results summary: Passed</strong>
                The validator ran and reports a pass. This means that the theme is verifiably valid and working.
                You can expect this theme to function normally in almost all situations.
            </div>
        <?php
        break;

    default: ?>
            <div class="alert alert-info">
                <strong>Results summary: No result</strong>
                The validator ran and failed to provide a result. This probably means there were no issues.

                <i>No news is good news</i>
            </div>
        <?php
        break;
    }
    ?>

    <a href="#rawData" data-toggle="collapse">For developers</a>
    <div class="collapse" id="rawData">
        <?php var_dump($results); ?>
    </div>
</div>