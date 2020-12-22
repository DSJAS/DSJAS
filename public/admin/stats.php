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

require "AdminBootstrap.php";

require ABSPATH . INC . "DB.php";
require ABSPATH . INC . "Stats.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Util.php";
require_once ABSPATH . INC . "csrf.php";


$config = new Configuration(true, false, false, false);
$stats = new Statistics($config);


$statState = $stats->getCurrentStatsState();
switch ($statState) {
    case STATSTATE_INACTIVE:
        $controlButtonColor = "success";
        $controlButtonText = "Start session";

        $statusSummaryTitle = "No active session: not recording";
        $statusSummaryText = "You do not have an active session. To record your statistics for this time using the site, " .
                             "click the button above. Recording will begin when a relevant event is recorded";

        $stateBadgeColor = "secondary";
        $stateBadgeText = "Inactive";

        break;

    case STATSTATE_FROZEN:
        $controlButtonColor = "primary";
        $controlButtonText = "Clear session";

        $statusSummaryTitle = "Session frozen: not recording";
        $statusSummaryText = "Your session has ended, but the results are still stored for viewing. To start a new " .
                             "session, clear your current statistics by clicking the button above.";

        $stateBadgeColor = "warning";
        $stateBadgeText = "Frozen";

        break;

    case STATSTATE_ACTIVE:
        $controlButtonColor = "danger";
        $controlButtonText = "Stop session";

        $statusSummaryTitle = "Session active: recording";
        $statusSummaryText = "You have an active recording session. All relevant statistics and measures of your ".
                             "usage of the site are being actively recorded. To view results, click the button above.";

        $stateBadgeColor = "success";
        $stateBadgeText = "Active";

        break;

    default:
        $controlButtonColor = "secondary";
        $controlButtonText = "Unknown";

        $stateBadgeColor = "danger";
        $stateBadgeText = "Unknown";

        break;
}

if (isset($_GET["shiftState"])) {
    $csrf = getCSRFSubmission();
    var_dump($csrf);
    if (!verifyCSRFToken($csrf)) {
        die(getCSRFFailedError());
    }

    $stats->shiftStatsState();

    redirect("/admin/stats.php");
    die();
} else {
    regenerateCSRF();
}

$timeNow = time();
$sessionTimestamps = $stats->getSessionTimestamps();

if ($statState == STATSTATE_FROZEN) {
    $secondsActive = $sessionTimestamps[1] - $sessionTimestamps[0];
    $minutesActive = round($secondsActive / 60, 2);
    $hoursActive = round($minutesActive / 60);
} else if ($statState == STATSTATE_ACTIVE) {
    $secondsActive = $timeNow - $sessionTimestamps[0];
    $secondsFormatted = $secondsActive % 60;
    $minutesActive = round($secondsActive / 60);
    $hoursActive = round($minutesActive / 60);
} else {
    $secondsActive = 0;
    $minutesActive = 0;
    $hoursActive = 0;
}

$totalPageHits = $stats->getStatistic("total_page_hits");


$csrfToken = getCSRFToken();
?>

<script src="/include/js/stats-update.js"></script>

<div id="content">
    <?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">DSJAS Session Statistics</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <strong>Current session state:</strong>
                <span class="ml-1 d-flex align-items-center badge badge-<?= $stateBadgeColor ?>"><?= $stateBadgeText ?></span>
            </div>
        </div>
    </div>

    <?php
    if (!$stats->statisticsAvailable()) {
        dsjas_alert("Statistics unavailable", "The statistics system requires a functioning database to work correctly. The database is currently either disabled or setup incorrectly. Please verify your settings and try again.", "danger");
        die();
    }
    ?>

    <div id="main-control-panel" class="row bg-light border stats-control-panel">
        <div class="col col-md-4" id="control-button">
            <a href="/admin/stats.php?shiftState&csrf=<?= $csrfToken ?>" class="btn btn-<?= $controlButtonColor ?> btn-lg btn-block"><?= $controlButtonText ?></a>
            <hr>

            <div class="btn-group">
                <button class="btn btn-primary" data-toggle="popover" data-trigger="focus" title="<?= $statusSummaryTitle ?>" data-placement="bottom" data-content="<?= $statusSummaryText ?>">Help</button>
                <a href="https://github.com/DSJAS/DSJAS/tree/master/docs/administration" class="btn btn-secondary">More information</a>
            </div>

        </div>

        <div class="col col-md-8" id="control-status">
            <div class="row stats-glance-counters">
                <!-- Session active for -->
                <div class="col col-3 offset-1 bg-white border stats-glance-counter">
                    <?php if ($statState == STATSTATE_FROZEN) { ?>
                    <h3 class="text-primary text-monospace font-weight-bold"><?= $minutesActive ?><br>(mins)</h3>
                    <?php } else if ($statState == STATSTATE_ACTIVE) { ?>
                    <h3 class="text-primary text-monospace font-weight-bold">
                    <span id="hours"><?= $hoursActive ?></span>:<span id="minutes"><?= $minutesActive ?></span>:<span id="seconds"><?= $secondsFormatted ?></span>
                    </h3>
                    <?php } else if ($statState == STATSTATE_INACTIVE) { ?>
                    <h3 class="text-secondary text-monospace font-weight-bold">N/A</h3>
                    <?php } ?>
                    <hr>
                    <p>Total scammer time wasted</p>
                </div>

                <!-- Sesion started (time) -->
                <div class="col col-3 offset-1 bg-white border stats-glance-counter">
                    <?php if ($statState == STATSTATE_FROZEN) { ?>
                    <h3 class="text-primary text-monospace font-weight-bold"><?php echo(gmdate("g:i a m.d.y", $sessionTimestamps[0])); ?></h3>
                    <?php } else if ($statState == STATSTATE_ACTIVE) { ?>
                    <h3 class="text-primary text-monospace font-weight-bold"><?php echo(gmdate("g:i a m.d.y", $sessionTimestamps[0])); ?></h3>
                    <?php } else if ($statState == STATSTATE_INACTIVE) { ?>
                    <h3 class="text-secondary text-monospace font-weight-bold">N/A</h3>
                    <?php } ?>
                    <hr>
                    <p>Time of session start</p>
                </div>

                <!-- Total page hits during session -->
                <div class="col col-3 offset-1 bg-white border stats-glance-counter">
                    <?php if ($statState == STATSTATE_FROZEN) { ?>
                    <h3 class="text-primary text-monospace font-weight-bold"><?= $totalPageHits ?></h3>
                    <?php } else if ($statState == STATSTATE_ACTIVE) { ?>
                    <h3 class="text-primary text-monospace font-weight-bold"><?= $totalPageHits ?></h3>
                    <?php } else if ($statState == STATSTATE_INACTIVE) { ?>
                    <h3 class="text-secondary text-monospace font-weight-bold">N/A</h3>
                    <?php } ?>

                    <br>
                    <hr>
                    <p>Total page views</p>
                </div>
            </div>
        </div>
    </div>

</div>