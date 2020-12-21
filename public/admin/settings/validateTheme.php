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
require_once ABSPATH . INC . "Util.php";

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
    <?php dsjas_alert("Validation complete", "The validator has completed and the results are available" .
                      "<br> <a href=\"/admin/settings/mod.php#validatorResults\">View full results</a>", "info", false);

    switch ($results[0]) {
    case "fatal_error":
        dsjas_alert("Results summary: Fatal error", "The validator ran and encountered an invalid theme, making it impossible to continue loading.
This means that this theme is invalid and will crash the site if used. We recommend uninstalling it and switching to another theme", "danger", false);
        break;

    case "errors_found":
        dsjas_alert("Results summary: Fail", "The validator ran and reported a fail. This means that the theme is either invalid or failes to provide
the basic elements of a valid theme. We recommend that this theme should not be used, as it is likely to be unstable", "danger", false);
        break;

    case "passed_warnings":
        dsjas_alert("Results summary: Passed with warnings", "The validator ran and reported minor issues (warnings). This means that the theme is likely
to work but may have minor instabilities", "warning", false);
        break;

    case "passed":
        dsjas_alert("Results summary: Passed", "The validator ran and reported a pass. This means that the theme is verifiably working. You can expect
this theme to function normally in most situations", "success", false);
        break;

    default:
        dsjas_alert("Results summary: No result", "The validator ran and failed to provide a result. This probably means there were no issues", "primary", false);
        break;
    }
    ?>

    <a href="#rawData" data-toggle="collapse">For developers</a>
    <div class="collapse" id="rawData">
        <?php var_dump($results); ?>
    </div>
</div>