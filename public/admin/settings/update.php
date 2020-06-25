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

    <div class="alert alert-danger">
        <p><strong>Attention:</strong> DSJAS is currently in alpha and automatic updates are not yet set up.
            You may need to manually check for updates until the automatic update system has been set up.
            In the future, the program will contain an automatic updater and the experience will be much smoother.
            We apologize for any inconvenience.</p>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Update status</h3>
            <a class="btn btn-primary" href="/admin/settings/update.php?forceCheck">Check now</a>
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