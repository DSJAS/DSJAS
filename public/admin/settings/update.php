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

function showAheadWarning()
{
    if (getCurrentRelease()->laterThanRelease(getLatestAvailableVersion(getUpdateBand())) &&
        !getLatestAvailableVersion(getUpdateBand())->isDummy())
        return "";
    else
        return "d-none";
}

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require ABSPATH . INC . "components/AdminSettingsNav.php"; ?>


    <div class="modal fade" id="patchNotes" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Patch Notes for <?= getVersionString() ?></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <?= getVersionDescription() ?>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">DSJAS Updates</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <p><strong>Your current version:</strong> <?= getVersionString() ?> <span class="badge <?= $bandBadgeStyle ?>">
                    <?= $band ?></span></p>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Update status</h3>
        </div>

        <div class="card-body">
            <?php if (getLatestAvailableVersion($band)->isDummy()) { ?>


                <h3 class="text-danger">
                    <svg class="bi bi-exclamation-triangle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8.982 1.566a1.13 1.13 0 00-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5a.905.905 0 00-.9.995l.35 3.507a.552.552 0 001.1 0l.35-3.507A.905.905 0 008 5zm.002 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                    Check failed</h3>

                    <hr>

                    <?php if (isRateLimited()) { ?>
                        <p class="text-secondary">
                            <strong>GitHub API Rate Limit Reached</strong>
                            You have reached the GitHub API rate limit. Please wait until <?= gmdate("g:i a m.d.y", getTimeoutExpire()) ?>
                            and try again.
                        </p>
                    <?php } else { ?>
                        <p class="text-secondary">
                            <strong>Failed to Contact Update Server</strong>
                            The releases API did not respond to the request or DSJAS could not reach the internet.
                            Please check your server's internet connection and that GitHub is currently up correctly.
                        </p>
                    <?php }

            } else {

            if (isUpdateAvailable()) { ?>
                <h3 class="text-warning">
                    <svg class="bi bi-exclamation-triangle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8.982 1.566a1.13 1.13 0 00-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5a.905.905 0 00-.9.995l.35 3.507a.552.552 0 001.1 0l.35-3.507A.905.905 0 008 5zm.002 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                    Updates available</h3>

                    <hr>

                    <div class="btn-group">
                        <a class="btn btn-primary" href="/admin/settings/do-update.php">Update now</a>
                        <a class="btn btn-secondary" href="<?= getLatestAvailableVersion($band)->getLink() ?>">More information</a>
                    </div>
            <?php } else { ?>
                <h3 class="text-success">
                    <svg class="bi bi-check" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 010 .708l-7 7a.5.5 0 01-.708 0l-3.5-3.5a.5.5 0 11.708-.708L6.5 10.293l6.646-6.647a.5.5 0 01.708 0z" clip-rule="evenodd" />
                    </svg>
                    You're up to date!</h3>

            <?php }
            } ?>


            <span class="text-warning <?= showAheadWarning() ?>">
                <br>

                <svg class="bi bi-exclamation-triangle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8.982 1.566a1.13 1.13 0 00-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5a.905.905 0 00-.9.995l.35 3.507a.552.552 0 001.1 0l.35-3.507A.905.905 0 008 5zm.002 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                </svg>

                Local version is newer than latest available
            </span>

            <hr>

            <p><strong>Current version:</strong> <?= getSemanticVersion() ?></p>
            <p><strong>Latest available version:</strong> <?= getLatestAvailableVersion($band)->toString() ?></p>
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
                    <td class="admin-info-value"><?= getSemanticVersion() ?></td>
                </tr>
                <tr>
                    <td class="admin-info-key">Version name:</td>
                    <td class="admin-info-value"><?= getVersionName() ?></td>
                </tr>
            </table>

            <a href="#" data-toggle="modal" data-target="#patchNotes">Patch notes</a>
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
                    <td class="admin-info-value">
                        <?= ($insiderBand) ? "Yes" : "No" ?>
                    </td>
                </tr>
                <tr>
                    <td class="admin-info-key">Your current release band</td>
                    <td class="admin-info-value"><?= $band ?></td>
                </tr>
            </table>
        </div>
    </div>

</html>