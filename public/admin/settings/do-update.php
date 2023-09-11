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

require_once ABSPATH . INC . "Update.php";
require_once ABSPATH . INC . "csrf.php";
require_once ABSPATH . INC . "Administration.php";
require_once ABSPATH . INC . "Util.php";

ignore_user_abort(true); // Don't allow the user to cancel this install by closing the loading browser
set_time_limit(0); // Don't stop the script if it takes too long
ob_start(); // Enable output buffering

function err($error)
{ ?>
    <div class="alert alert-danger">
        <p>
            <strong>Update Failure</strong> A fatal error ocurred while downloading the update (detail: <?= $error ?>).
            <a href="/admin/settings/update.php">Return to Update Page</a>
        </p>
    </div>
<?php

    addAdministrationNotice("update_failure", "DSJAS System Update Failed",
        "A DSJAS system update was attempted at " . date("H:i d/M/Y") . ". A fatal error ocurred and no upgrade was performed. The current system version was unaffected. "
        . "Error reason: " . $error,

        "/admin/settings/update.php",
        "Update Options",
        ADMIN_NOTICE_WARNING
    );
    die();
}

if (!isUpdateAvailable()) {
    err("Already up to date");
}

// Preliminary info
$rel = getCurrentRelease();
$u = getLatestAvailableVersion(getUpdateBand());
$url = $u->getDownload(getUpdateBand());

if ($url === false)
{
    err("Given release contained no matching download archive");
}

Requests::register_autoloader();
try {
    $downloadRequest = Requests::get($url);
    $fileContent = $downloadRequest->body;
} catch (Requests_Exception $e) {
    err("Download of update archive failed: $e");
}

if (!$downloadRequest->success || $downloadRequest->status_code != 200)
{
    err("Download of update archive failed");
}

// Dump file
$archive = tempnam("", "dsjasupdate");
$fh = fopen($archive, "w");
fputs($fh, $fileContent);

// Clean and make update dir
if (is_dir(ABSPATH . "/uploads/update/"))
{
    recursiveDeleteDirectory(ABSPATH . "/uploads/update/");
}
mkdir(ABSPATH . "/uploads/update/");

// Open and extract archive
$zip = new ZipArchive();
if ($zip->open($archive, ZipArchive::RDONLY) !== true)
{
    err("Downloaded zip archive was invalid");
}
$zip->extractTo(ABSPATH . "/uploads/update/");

// Copy out configuration files
if (copy(ABSPATH . "/Config.ini", ABSPATH . "/uploads/update/Config.ini") === false)
{
    err("Failed to copy out current configuration");
}

// Finally, copy out to source tree
try {
    recurseCopy(ABSPATH . "/uploads/update/", ABSPATH);
} catch (Exception $ex) {
    err("Failed to copy upload archive to live server root: $e");
}

// Tell the administrator
addAdministrationNotice("update_success", "DSJAS System Update Succeeded",
    "A DSJAS system update was completed at " . date("H:i d M Y") . ". DSJAS was upgraded to v" . $u->toString() . " and is now the latest version. "
    . "Your theme and module configurations have been reset to the default to avoid any incompatabilities with the new version of DSJAS.",

    "/admin/settings/update.php",
    "View Patch Notes",
    ADMIN_NOTICE_SUCCESS
);

?>

<div class="alert alert-success">
    <p>
        <strong>Update Success</strong> DSJAS has been upgraded to version <?= $u->toString() ?>.
        Your main configuration has been preserved, but theme and module configurations have been reset in case of incompatibility with the new version.

        <br>
        <a href="/admin/settings/update.php">Return to Update Page</a>
</div>