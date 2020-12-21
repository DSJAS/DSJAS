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

require ABSPATH . INC . "csrf.php";
require_once ABSPATH . INC . "Util.php";


$globalConfigText = file_get_contents(ABSPATH . "/Config.ini");
$themeConfigText = file_get_contents(ABSPATH . "/admin/site/UI/config.ini");
$moduleConfigText = file_get_contents(ABSPATH . "/admin/site/modules/config.ini");
$extensionConfigText = file_get_contents(ABSPATH . "/admin/site/extensions/config.ini");


$globalLen = count(explode("\n", $globalConfigText));
$themeLen = count(explode("\n", $themeConfigText));
$moduleLen = count(explode("\n", $moduleConfigText));
$extensionLen = count(explode("\n", $extensionConfigText));


if (isset($_POST["doSave"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission());

    if (!$csrf) {
        die(getCSRFFailedError());
    }

    $global = $_POST["global"];
    $theme = $_POST["theme"];
    $module = $_POST["module"];
    $extension = $_POST["extension"];

    $globalConfigText = file_put_contents(ABSPATH . "/Config.ini", $global);
    $themeConfigText = file_put_contents(ABSPATH . "/admin/site/UI/config.ini", $theme);
    $moduleConfigText = file_put_contents(ABSPATH . "/admin/site/modules/config.ini", $module);
    $extensionConfigText = file_put_contents(ABSPATH . "/admin/site/extensions/config.ini", $extension);


    die();
}

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require ABSPATH . INC . "components/AdminSettingsNav.php"; ?>

    <p style="display: none" id="csrfToken"><?php echo (getCSRFToken()); ?></p>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Advanced settings</h1>
    </div>

    <?php

    if (isset($_GET["success"])) {
        dsjas_alert("Settings saved", "The requested configuration values were overwritten", "success", false);
    } else if (isset($_GET["error"])) {
        dsjas_alert("Failed to save settings", "There was an error while attempting to save your settings. Any changes were discarded", "danger", false);
    }

?>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Global configuration file</h3>
        </div>

        <div class="card-body">
            <form>
                <div class="form-group">
                    <textarea id="global" class="form-control" rows=<?php echo ($globalLen); ?> type="textarea"><?php echo ($globalConfigText) ?></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Theme configuration file</h3>
        </div>

        <div class="card-body">
            <form>
                <div class="form-group">
                    <textarea id="theme" class="form-control" rows=<?php echo ($themeLen); ?> type="textarea"><?php echo ($themeConfigText) ?></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Module configuration file</h3>
        </div>

        <div class="card-body">
            <form>
                <div class="form-group">
                    <textarea id="module" class="form-control" rows=<?php echo ($moduleLen); ?> type="textarea"><?php echo ($moduleConfigText) ?></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-header d-flex justify-content-between">
            <h3>Extension configuration file</h3>
        </div>

        <div class="card-body">
            <form>
                <div class="form-group">
                    <textarea id="extension" class="form-control" rows=<?php echo ($extensionLen); ?> type="textarea"><?php echo ($extensionConfigText) ?></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-light admin-panel">
        <div class="card-body">
            <button onclick="advancedSaveSettings()" class="btn btn-primary">
                Save
                <span id="saveProgress" class="spinner-border spinner-border-sm" style="display: none"></span>
            </button>
            <button onclick="discardChanges()" class="btn btn-danger">Discard changes</button>
        </div>
    </div>
</div>

</html>