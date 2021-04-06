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

require_once ABSPATH . INC . "Customization.php";
require ABSPATH . INC . "csrf.php";

require_once ABSPATH . INC . "Util.php";

$conf = new Configuration(false, true, false, true);

$lastValidation = $conf->getKey(ID_THEME_CONFIG, "validation", "last_validation");
$lastValidationTimestamp = $conf->getKey(ID_THEME_CONFIG, "validation", "last_validation_timestamp");

$installedThemes = scandir(ABSPATH . "/admin/site/UI/");

if ($conf->getKey(ID_THEME_CONFIG, "config", "use_default")) {
    $activeTheme = "default";
} else {
    $activeTheme = $conf->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
}

$installedModules = scandir(ABSPATH . "/admin/site/modules");


regenerateCSRF();

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require ABSPATH . INC . "components/AdminSettingsNav.php";

    if (isset($_GET["notArchive"])) {
        dsjas_alert("Invalid package archive", "The uploaded installation file was not a valid package archive. You should be uploading a zip archive file.", "danger", true);
    }

    if (isset($_GET["wrongType"])) {
        dsjas_alert("Invalid extension type", "You are attempting to upload a module as a theme or a theme as a module. Please check that you uploaded the archive to the correct
settings panel", "danger", true);
    }

    if (isset($_GET["unknownError"])) {
        dsjas_alert("Package failed to install", "An unknown error has prevented the installation of the uploaded package. Please try again later", "danger", false);
    }

    if (isset($_GET["noFile"])) {
        dsjas_alert("No provided file", "No package archive was provided", "danger", true);
    }

    if (isset($_GET["sizeError"])) {
        dsjas_alert("Package too large", "The uploaded package archive has exceeded the maximum upload limit. Plese contact the package developer", "danger", true);
    }

    if (isset($_GET["missingManifest"])) {
        dsjas_alert("Not a package archive", "The uploaded archive is not a valid DSJAS package. Please contact the package developer", "danger", true);
    }

    if (isset($_GET["malformedManifest"])) {
        dsjas_alert("Malformed package archive", "The uploaded package archive's settings could not be read. Please contact the package developer", "danger", true);
    }

    if (isset($_GET["alreadyExists"])) {
        dsjas_alert("Package already exists", "The theme/module uploaded already exists and cannot be re-installed", "danger", true);
        dsjas_alert("Have you enabled this package?", "To apply installed packages, they must first be enabled in the settings panel below", "primary", true);
    }

    if (isset($_GET["themeInstalled"])) {
        dsjas_alert("Theme successfully installed", "DSJAS has installed your theme, but not applied it. To apply this theme to your site, enable it from the theme settings panel", "success", true);
    }

    if (isset($_GET["moduleInstalled"])) {
        dsjas_alert("Module successfully installed", "DSJAS has installed your module, but not enabled it. Modules must be enabled before they are applied to the side", "success", true);
    }

    if (isset($_GET["themeInstalledEnabled"])) {
        dsjas_alert("Theme successfully applied", "DSJAS has installed and applied the requested theme", "success", true);
    }

    if (isset($_GET["activatedTheme"])) {
        dsjas_alert("Theme applied", "Successfully enabled and applied the requested theme. Refresh any bank pages for changes to take effect", "success", true);
    }

    if (isset($_GET["uninstalledTheme"])) {
        dsjas_alert("Uninstalled theme", "The specified theme has been deleted. Your theme bas been reset to the default until another is specified", "success", true);
    }

    if (isset($_GET["themeDownloadFailed"])) {
        dsjas_alert("Package download failed", "DSJAS failed to download a package archive from the given URL. Contact the vendor and check the URL's correctness", "danger", true);
    }

    if (isset($_GET["moduleSaved"])) {
        dsjas_alert("Settings saved", "Module changes applied. Refresh any open bank pages for changes to take effect", "success", true);
    }

    if (isset($_GET["moduleUninstalled"])) {
        dsjas_alert("Module uninstalled", "The specified module has been deleted. Your module configuration has been preserved", "success", true);
    }

    if (isset($_GET["noSuchTheme"])) {
        dsjas_alert("No such theme", "The theme requested for application does not exist", "danger", true);
    }

    ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Modules and Themes</h1>
    </div>

    <?php
        
        foreach ($installedModules as $module) {
            if (is_file(ABSPATH . "/admin/site/modules/" . $module) || $module == "." || $module == "..") {
                continue;
            }

            $configText = file_get_contents(ABSPATH . "/admin/site/modules/" . $module . "/config.json");
            $config = json_decode($configText, true);

            $modalName = "deleteModal" . $module;
            $modalTitle = "Really delete \"" . $config["name"] . "\"?";
            ?>

            <div class="modal fade" id="<?= $modalName ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= $modalTitle ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Deleting a module is permanent and cannot be undone once completed.
                            Are you sure you wish to continue?
                        </div>
                        <div class="modal-footer">
                            <a href="/admin/settings/installModule.php?uninstallModule=<?= $module ?>&csrf=<?php echo (getCSRFToken()); ?>" type="button" class="btn btn-danger">Confirm</a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>

    <ul class="nav nav-tabs nav-fill">
        <li class="nav-item">
            <a class="nav-link active text-primary" onclick="switchToThemes()" id="themeTabBar">Theme</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="switchToModules()" id="moduleTabBar">Modules</a>
        </li>
    </ul>

    <div id="themePanel" class="mt-3">

        <div class="card bg-light admin-panel">
            <div class="card-header d-flex justify-content-between">
                <h3>Installed themes</h3>
            </div>

            <div class="card-body">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <th>Currently active theme</th>
                        <th></th>
                    </thead>

                    <tbody>
                        <tr>
                            <td><?php echo ($activeTheme); ?></td>
                            <td></td>
                        </tr>
                    </tbody>

                    <thead class="thead-dark">
                        <th>Installed themes (not active)</th>
                        <th></th>
                    </thead>

                    <thead class="thead">
                        <th>Theme name</th>
                        <th>Actions</th>
                    </thead>

                    <tbody>
                        <?php foreach ($installedThemes as $theme) {
                            if (is_file(ABSPATH . "/admin/site/UI/" . $theme) || $theme == $activeTheme || $theme == "." || $theme == "..") {
                                continue;
                            }
                            ?>
                            <tr>
                                <td><?php echo ($theme); ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a type="button" class="btn btn-primary" href="/admin/settings/installTheme.php?enableTheme=<?php echo ($theme); ?>&csrf=<?php echo (getCSRFToken()); ?>">Activate</a>
                                        <a type="button" class="btn btn-danger" href="/admin/settings/installTheme.php?uninstallTheme=<?php echo ($theme); ?>&csrf=<?php echo (getCSRFToken()); ?>">Uninstall</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card bg-light admin-panel">
            <div class="card-header d-flex justify-content-between">
                <h3>Install a theme</h3>
            </div>

            <div class="card-body">
                <h4 class="card-title">Method 1: Upload theme package</h4>
                <p class="text-secondary">Upload the zip archive which contains the packaged theme provided by the developer and DSJAS will install it for you</p>

                <form action="/admin/settings/installTheme.php" method="POST" enctype="multipart/form-data">
                    <?php getCSRFFormElement(); ?>
                    <input id="actionName" type="text" style="visibility: hidden; position: absolute" name="installTheme" value="1">

                    <div class="input-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="fileInputLabel">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="hidden" name="MAX_FILE_SIZE" value="44040192">
                                <input type="file" class="custom-file-input" id="themeFileInput" name="themeFile" accept="application/zip" required>
                                <label class="custom-file-label" for="fileInput">Choose file</label>
                            </div>

                            <div class="input-group-append">
                                <button class="btn btn-warning" type="submit" id="submitUpload">Install</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="enableTheme" name="enabled">
                        <label class="form-check-label" for="enableTheme">
                            Enable this theme after installation
                        </label>
                    </div>
                </form>

                <hr>
                <h4 class="card-title">Method 2: Provide an install URL</h4>
                <p class="text-secondary">DSJAS will download a theme package form this URL and install it using the developer's instructions.
                    This will save a significant amount of time - due to the fact that you don't have to download the theme and upload it again (which can take some time).
                </p>

                <form action="/admin/settings/installTheme.php" method="POST">
                    <?php getCSRFFormElement(); ?>
                    <input id="actionName" type="text" style="visibility: hidden; position: absolute" name="installThemeURL" value="1">

                    <div class="input-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="urlInputLabel">
                                    <svg class="bi bi-link" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.354 5.5H4a3 3 0 000 6h3a3 3 0 002.83-4H9c-.086 0-.17.01-.25.031A2 2 0 017 10.5H4a2 2 0 110-4h1.535c.218-.376.495-.714.82-1z" />
                                        <path d="M6.764 6.5H7c.364 0 .706.097 1 .268A1.99 1.99 0 019 6.5h.236A3.004 3.004 0 008 5.67a3 3 0 00-1.236.83z" />
                                        <path d="M9 5.5a3 3 0 00-2.83 4h1.098A2 2 0 019 6.5h3a2 2 0 110 4h-1.535a4.02 4.02 0 01-.82 1H12a3 3 0 100-6H9z" />
                                        <path d="M8 11.33a3.01 3.01 0 001.236-.83H9a1.99 1.99 0 01-1-.268 1.99 1.99 0 01-1 .268h-.236c.332.371.756.66 1.236.83z" />
                                    </svg>
                                </span>
                            </div>

                            <input type="text" class="form-control" id="urlInput" name="themeURL" placeholder="URL">

                            <div class="input-group-append">
                                <button class="btn btn-warning" type="submit" id="submitURL">Download and install</button>
                            </div>
                        </div>
                    </div>
                </form>

                <hr>
                <small class="text-small text-danger"><strong>Warning:</strong> Themes have full control over your webserver after they are installed.
                    <strong>Do not</strong> install any theme which could be malicious and always check that the theme is from a trusted developer.
                </small>
            </div>
        </div>

        <div class="card bg-light admin-panel" id="validatorResults">
            <div class="card-header d-flex justify-content-between">
                <h3>Theme validation</h3>
            </div>

            <div class="card-body">
                <div class="card-title d-flex justify-content-between">
                    <h3>Run a validation</h3>
                </div>

                <p>If the theme you are using is not working correctly, the theme validator could assist you in fixing it.
                    DSJAS can automatically fix some issues with themes and re-install themes which are detected to be damaged.
                </p>

                <div class="d-flex justify-content-between">
                    <p>Run the validator now:</p>
                    <a class="btn btn-success" href="/admin/settings/validateTheme.php?validateTheme&csrf=<?php echo (getCSRFToken()); ?>">Run</a>
                </div>

                <?php if ($lastValidation == "never_run") { ?>
                    <small class="text-small text-secondary">Last validation ran: <strong class="text-danger">You have never ran the validator for this theme</strong></small>
                <?php } else { ?>
                    <small class="text-small text-secondary">Last validation ran: <?php echo date("jS F Y [h:i A]", $lastValidationTimestamp) ?></small>
                <?php } ?>
            </div>
        </div>


        <div class="card bg-light admin-panel">
            <div class="card-header d-flex justify-content-between">
                <h3>For developers</h3>
            </div>

            <div class="card-body">
                <h4 class="card-title">Interested in developing your own theme?</h4>
                <hr>

                <p>
                    DSJAS themes allow for the appearance and content of the site to be changed completely
                    by developers and users.

                    If UI is your thing or that sounds interesting to you, look into DSJAS theme development:
                </p>

                <a href="https://github.com/DSJAS/DSJAS/tree/master/docs/themes" class="btn btn-primary">Go to the wiki</a>
            </div>
        </div>
    </div>

    <div id="modulePanel" style="display: none" class="mt-3">

        <div class="card bg-light admin-panel">
            <div class="card-header d-flex justify-content-between">
                <h3>Installed modules</h3>
                <button class="btn btn-primary" onclick="saveModuleSettings()">Save changes</button>
            </div>

            <div class="card-body">
                <div class="container mt-4">
                    <div class="row" id="modulesContainer">
                        <?php
                        $count = 0;
                        foreach ($installedModules as $module) {
                            if (is_file(ABSPATH . "/admin/site/modules/" . $module) || $module == "." || $module == "..") {
                                continue;
                            }

                            $configText = file_get_contents(ABSPATH . "/admin/site/modules/" . $module . "/config.json");
                            $config = json_decode($configText, true);

                            $enabled = $conf->getKey(ID_MODULE_CONFIG, "active_modules", $module);
                            $uninstallModal = "deleteModal" . $module;
                            ?>
                            <div class="col-auto mb-3">
                                <input id="moduleName<?php echo ($count); ?>" type="text" style="visibility: hidden; position: absolute" name="moduleName" value="<?php echo ($module); ?>">
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo ($config["name"]); ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?php echo ($config["version"]); ?></h6>
                                        <p class="card-text"><?php echo ($config["description"]); ?></p>
                                        <div class="custom-control custom-switch" data-toggle="tooltip" data-placement="top" title="Enable/disable module">
                                            <input type="checkbox" class="custom-control-input" id="moduleEnableSwitch<?php echo ($count); ?>" <?php if ($enabled) {
                                                                                                                                                    echo ("checked");
} ?>>
                                            <label class="custom-control-label" for="moduleEnableSwitch<?php echo ($count); ?>"></label>
                                        </div>
                                        <?php if (isset($config["information-link"]) && $config["information-link"] != "") { ?>
                                            <a href="<?php echo ($config["information-link"]); ?>" class="card-link">More details</a>
                                        <?php } ?>
                                        <a class="text-danger ml-1" href="#" data-toggle="modal" data-target="#<?= $uninstallModal ?>">Uninstall</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $count++;
                        } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light admin-panel">
            <div class="card-header d-flex justify-content-between">
                <h3>Install a module</h3>
            </div>

            <div class="card-body">
                <form action="/admin/settings/installModule.php" method="POST" enctype="multipart/form-data">
                    <?php getCSRFFormElement(); ?>
                    <input id="moduleActionName" type="text" style="visibility: hidden; position: absolute" name="installModule" value="1">

                    <div class="input-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="fileInputLabel">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="hidden" name="MAX_FILE_SIZE" value="44040192">
                                <input type="file" class="custom-file-input" id="moduleFileInput" name="moduleFile" accept="application/zip" required>
                                <label class="custom-file-label" for="fileInput">Choose file</label>
                            </div>

                            <div class="input-group-append">
                                <button class="btn btn-warning" type="submit" id="submitModuleUpload">Install</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="enableModule" name="enabled">
                        <label class="form-check-label" for="enableModule">
                            Enable this module after installation
                        </label>
                    </div>
                </form>

                <hr>
                <small class="text-small text-danger"><strong>Important:</strong> Modules don't have the same level of access to the server that themes or extensions do.
                    <strong>However</strong> they can still run code in the browser and make requests, read the contents of cookies and edit the layout of the site.
                    Please apply the same level of scrutiny to modules as for themes.
                </small>
            </div>
        </div>

        <div class="card bg-light admin-panel">
            <div class="card-header d-flex justify-content-between">
                <h3>For developers</h3>
            </div>

            <div class="card-body">
                <h4 class="card-title">Interested in developing your own module?</h4>
                <hr>

                <p>
                    Modules provide a way of adding more features to whatever theme's appearance they come across.
                    If that interests you, look into DSJAS module development
                </p>
                <br>

                <a href="https://github.com/DSJAS/DSJAS/tree/master/docs/modules" class="btn btn-primary">Go to the wiki</a>
            </div>
        </div>
    </div>
</div>

</html>