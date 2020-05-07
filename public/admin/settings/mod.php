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


regenerateCSRF();

?>

<html>
<?php require(ABSPATH . INC . "components/AdminSidebar.php"); ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require(ABSPATH . INC . "components/AdminSettingsNav.php");

    if (isset($_GET["themeNotArchive"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme failed to install</strong> The file you just attempted to upload as a theme is not a valid archive.
                Your theme should be delivered in a zip archive.
                If you have downloaded a theme which is not a zip archive, please contact the theme developer; their theme is broken.</p>
        </div>
    <?php }

    if (isset($_GET["unknownThemeError"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme failed to install</strong> There was an unknown error while attempting to install that theme.
                Please try again or report to the theme developers. Perhaps try downloading the theme package again?</p>
        </div>
    <?php }

    if (isset($_GET["missingManifest"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Malformed or invalid theme</strong> The uploaded archive is not a valid DSJAS theme package.
                Please make sure that the archive you uploaded is a theme package and that the archive has not been damaged.</p>
        </div>
    <?php }

    if (isset($_GET["malformedManifest"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme failed to install</strong> The uploaded archive is a theme, but the contained configuration is invalid.
                This means that DSJAS was unable to read the instructions the theme developer has provided on how to perform the install.</p>
            </p>
        </div>
    <?php }

    if (isset($_GET["themeExists"])) { ?>
        <div class="alert alert-danger">
            <p><strong>Theme already installed</strong> You've tried to upload a theme that's already installed!
                Before a theme is applied, it needs to be enabled by the site administrator (that's you).
                To do this, use the "Installed themes" panel and click on "Activate" next to the theme you wish to apply.
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
                                <button class="btn btn-warning" type="submit" id="submit" onsubmit="showThemeInstalling()">Install</button>
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
                    You won't have to wait for the download to complete on your computer then upload the file (which can take some time).
                </p>

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