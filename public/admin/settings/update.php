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

require ABSPATH . INC . "Update.php";


if (isInsiderBand()) {
    $insiderBand = true;

    $band = getUpdateBand();

    if ($band == "alpha") {
        $bandBadgeStyle = "badge-danger";
    } else {
        $bandBadgeStyle = "badge-warning";
    }
} else {
    $insiderBand = false;
    $band = "stable";

    $bandBadgeStyle = "badge-success";
}

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require ABSPATH . INC . "components/AdminSettingsNav.php"; ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">DSJAS Updates</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <p><strong>Your current version:</strong> <?php echo (getVersionString()); ?> <span class="badge <?php echo ($bandBadgeStyle); ?>">
                    <?php echo ($band); ?></span></p>
        </div>
    </div>

    <div class="alert alert-warning">
        <p><strong>Attention:</strong> DSJAS does not yet contain an automatic updater. This means that you will need
            to download and install updates yourself. For more information and specific instructions, please see the
            <a href="https://github.com/DSJAS/DSJAS/blob/master/docs/administration/Performing%20an%20update.md">upgrade guide</a>.
        </p>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Update status</h3>
        </div>

        <div class="card-body">
            <?php if (!isUpdateAvailable()) { ?>
                <h3 class="text-success">
                    <svg class="bi bi-check" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 010 .708l-7 7a.5.5 0 01-.708 0l-3.5-3.5a.5.5 0 11.708-.708L6.5 10.293l6.646-6.647a.5.5 0 01.708 0z" clip-rule="evenodd" />
                    </svg>
                    You're up to date!</h3>
            <?php } else { ?>
                <h3 class="text-warning">
                    <svg class="bi bi-exclamation-triangle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8.982 1.566a1.13 1.13 0 00-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5a.905.905 0 00-.9.995l.35 3.507a.552.552 0 001.1 0l.35-3.507A.905.905 0 008 5zm.002 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                    Updates available</h3>

                    <hr>

                    <div class="btn-group">
                        <a class="btn btn-primary" href="https://github.com/DSJAS/DSJAS/releases">Download update</a>
                        <a class="btn btn-secondary" href="https://github.com/DSJAS/DSJAS/blob/master/docs/administration/Performing%20an%20update.md">More information</a>
                    </div>
            <?php } ?>

            <hr>

            <p><strong>Current version:</strong> <?php echo (getVersionString()); ?></p>
            <p><strong>Latest available version:</strong> <?php echo (getLatestAvailableVersion($band)); ?></p>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Current version</h3>
        </div>

        <div class="card-body">
            <table class="table table-dark table-bordered">
                <tr>
                    <td class="admin-info-key">Current version:</td>
                    <td class="admin-info-value"><?php echo (getSemanticVersion()); ?></td>
                </tr>
                <tr>
                    <td class="admin-info-key">Version name:</td>
                    <td class="admin-info-value"><?php echo (getVersionName()); ?></td>
                </tr>
                <tr>
                    <td class="admin-info-key">Version description:</td>
                    <td class="admin-info-value"><?php echo (getVersionDescription()); ?></td>
                </tr>
            </table>

            <a href="https://github.com/OverEngineeredCode/DSJAS/releases">Patch notes</a>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Pre-releases</h3>
            <?php if (isInsiderBand()) { ?>
                <p class="badge badge-success">Opted in</p>
            <?php } else { ?>
                <p class="badge badge-danger">Opted out</p>
            <?php } ?>
        </div>

        <div class="card-body">
            <h5>Help the development of DSJAS today by opting in to a pre-release!</h5>

            <p>Help the DSJAS community-driven work by taking our in-progress work and giving it a test run. Get brand-new features straight
                from the desks of the developers and help us make the next version of the program even better.

                You can sign up completely free by downloading a pre-release from the place you download normal releases.
            </p>

            <table class="table table-dark table-bordered">
                <tr>
                    <td class="admin-info-key">Opted in to pre-release</td>
                    <td class="admin-info-value"><?php if ($insiderBand) {
                                                        echo ("Yes");
} else {
                                                     echo ("No");
                                                 } ?></td>
                </tr>
                <tr>
                    <td class="admin-info-key">Your current release band</td>
                    <td class="admin-info-value"><?php echo ($band); ?></td>
                </tr>
            </table>
        </div>
    </div>

</html>