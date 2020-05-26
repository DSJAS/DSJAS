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

require(ABSPATH . INC . "Customization.php");
require(ABSPATH . INC . "csrf.php");

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
<?php require(ABSPATH . INC . "components/AdminSidebar.php"); ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require(ABSPATH . INC . "components/AdminSettingsNav.php");

    if (isset($_GET["notArchive"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme or module failed to install</strong> The file you just attempted to upload as a theme or module is not a valid archive.
                Your theme/module should be delivered in a zip archive.
                If you have downloaded a one of the above which is not a zip archive, please contact the developer; their extension is broken.</p>
        </div>
    <?php }

    if (isset($_GET["wrongType"])) { ?>
        <div class="alert alert-danger">
            <p><strong>You're trying to install this extension in the wrong place</strong> You tried to install the wrong type extension in this place.
                Please make sure that you are uploading the correct package and that you are on the right page.</p>
        </div>
    <?php }

    if (isset($_GET["unknownError"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme or module failed to install</strong> There was an unknown error while attempting to install that theme/module.
                Please try again or report to the developers. Perhaps try downloading the package again?</p>
        </div>
    <?php }

    if (isset($_GET["noFile"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme or module failed to install</strong> No upload package was provided.</p>
        </div>
    <?php }

    if (isset($_GET["sizeError"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme or module failed to install</strong> This plugin is too large. DSJAS themes and modules have a maximum file size due to technical constraints.
                Please contact the plugin developer</p>
        </div>
    <?php }

    if (isset($_GET["missingManifest"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Malformed or invalid package</strong> The uploaded archive is not a valid DSJAS package.
                Please make sure that the archive you uploaded is a package and that the archive has not been damaged.</p>
        </div>
    <?php }

    if (isset($_GET["malformedManifest"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme failed to install</strong> The uploaded archive is a package, but the contained configuration is invalid.
                This means that DSJAS was unable to read the instructions the developer has provided on how to perform the install.</p>
            </p>
        </div>
    <?php }

    if (isset($_GET["alreadyExists"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme or module already installed</strong> You've tried to upload a theme or module which has already been installed - either by you or another administrator.
                You are not able to install the same theme or module twice. If the extension you are uploading is not already installed, the name may be already in use.
                <br>
                <strong>Before you can use an extension, you need to enable it!</strong> Before DSJAS will load any modules or themes, they need to be enabled.
                You can do this through the settings panel.
            </p>
            </p>
        </div>
    <?php }

    if (isset($_GET["themeInstalled"])) { ?>
        <div class="alert alert-success">
            <p><strong>Theme successfully installed</strong> DSJAS has installed your theme.
                To apply this theme and use it as the active one for the site, you must enable it from the installed theme settings panel.
            </p>
        </div>
    <?php }

    if (isset($_GET["themeInstalledEnabled"])) { ?>
        <div class="alert alert-success">
            <p><strong>Theme successfully installed</strong> DSJAS has installed your theme.
                The theme has also been enabled. Reload your page to apply the changes to the bank.
            </p>
        </div>
    <?php }

    if (isset($_GET["activatedTheme"])) { ?>
        <div class="alert alert-success">
            <p><strong>Enabled theme</strong> That theme has been enabled. Refresh any bank pages to apply the new theme.</p>
        </div>
    <?php }

    if (isset($_GET["uninstalledTheme"])) { ?>
        <div class="alert alert-warning">
            <p><strong>Uninstalled theme</strong> The specified theme has been deleted. Your theme has been reset to the default until another is specified.</p>
        </div>
    <?php }

    if (isset($_GET["themeDownloadFailed"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Failed to download theme</strong> There was an error while attempting to download your theme from that URL. Please check the URL is correct and that your server is able to reach the location.</p>
        </div>
    <?php }

    if (isset($_GET["moduleSaved"])) { ?>
        <div class="alert alert-success">
            <p><strong>Settings saved</strong> Your module settings saved successfully. Any disabled or enabled modules have had their status updated</p>
        </div>
    <?php }

    if (isset($_GET["moduleUninstalled"])) { ?>
        <div class="alert alert-warning">
            <p><strong>Module uninstalled</strong> The selected module was uninstalled. If you ever choose to re-install the module, your configuration will be preserved.</p>
        </div>
    <?php } ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Modules and Themes</h1>
    </div>

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
                <h3>Information</h3>
            </div>

            <div class="card-body">
                <h4 class="card-title">Change the way the site looks with a click</h4>
                <hr>

                <p><strong>DSJAS themes are the face of the site; they define what the site is, how it looks and what pages are available.</strong>
                    DSJAS themes allow you to specify a set of rules which tell the site what you want the browser to see when it navigates to a bank-side
                    page. Essentially, a theme contains a lot of code which tells DSJAS what to send to the browser when it asks for something.
                    There is nothing themes can't change about the process, and no two instances of DSJAS look the same with themes!
                </p>
            </div>
        </div>

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

        <div class="card bg-light admin-panel">
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
                    <a class="btn btn-success" href="/admin/settings/mod.php?validateTheme&csrf=<?php echo (getCSRFToken()); ?>">Run</a>
                </div>

                <?php if ($lastValidation == "never_run") { ?>
                    <small class="text-small text-secondary">Last validation ran: <strong class="text-danger">You have never ran the validator for this theme</strong></small>
                <?php } else { ?>
                    <small class="text-small text-secondary">Last validation ran: <?php echo ($lastValidationTimestamp); ?></small>
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
                    The DSJAS theme API is designed to be as simple as it could be, whilst still giving
                    you, the theme developer, complete control.

                    To get started, visit our wiki for instructions on getting started, how to use the API
                    and how to use the development kit.
                </p>

                <a href="https://github.com/OverEngineeredCode/DSJAS/wiki" class="btn btn-primary">Go to the wiki</a>
            </div>
        </div>
    </div>

    <div id="modulePanel" style="display: none" class="mt-3">

        <div class="card bg-light admin-panel">
            <div class="card-header d-flex justify-content-between">
                <h3>Information</h3>
            </div>

            <div class="card-body">
                <h4 class="card-title">Add features and change existing ones with fun, community-made modules</h4>
                <hr>

                <p><strong>DSJAS modules change the interface (buttons, text fields etc) or add new interfaces to change site behaviour.</strong>
                    DSJAS modules allow you to add elements to the user interface in the browser. For example, a module may add a reset password button
                    to the login page. This allows for some really fun and interesting ways of playing with scammers. The best part is that they work with
                    any theme, meaning that you can swap in modules to add features with a click!
                    <br>
                    <br>
                    DSJAS ships with some interesting modules to try out. Maybe give them a try and see what kind of things you can do!
                </p>
            </div>
        </div>

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

                            $enabled = $conf->getKey(ID_MODULE_CONFIG, "active_modules", $module)
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
                                        <a href="#" class="card-link">More details</a>
                                        <a class="text-danger ml-1" href="/admin/settings/installModule.php?uninstallModule=<?php echo ($module) ?>&csrf=<?php echo (getCSRFToken()); ?>">Uninstall</a>
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
                    Modules are the core of DSJAS and are one of the most rewarding and fun things to make.
                    Just about anything can be done with them, provided you know how.

                    However, the "provided you know how" part is a little bit of a problem.
                    Modules are the most complex extension pipelines the site exposes.
                    So, you will need to learn how to use them before even attempting to
                    make one.
                </p>
                <br>
                <p>
                    To get started, visit our wiki for instructions and a getting started guide - as well as an in-depth
                    explanation of how modules work and how they are programmed internally. The modules API and SDK is
                    free from the usual download location.
                </p>

                <a href="https://github.com/OverEngineeredCode/DSJAS/wiki" class="btn btn-primary">Go to the wiki</a>
            </div>
        </div>
    </div>
</div>

</html>