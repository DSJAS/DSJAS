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

if (!isUpdateAvailable()) {
    die("Already up to date");
}

// Preliminary info
$major = getMajorVersion();
$minor = getMinorVersion();
$patch = getPatchVersion();
$band = getUpdateBand();

$u = getLatestAvailableVersion($band);
$uMajor = $u[0];
$uMinor = $u[1];
$uPatch = $u[2];

// Download update archive
$archiveURL = getArchiveLocation($uMajor, $uMinor, $uPatch, $band);

Requests::register_autoloader();
try {
    $downloadRequest = Requests::get($archiveURL);
    $fileContent = $downloadRequest->body;
} catch (Requests_Exception $e) {
    die("Download of update archive failed: $e");
}

if (!$downloadRequest->success || $downloadRequest->status_code != 200)
{
    die("Download of update archive failed");
}

// Dump file
$archive = tempnam("", "dsjasupdate");
$fh = fopen($archive, "w");
fputs($fh, $fileContent);

// Make Phar detect it
$newArchive = "$archive.tar.gz";
fclose($fh);
rename($archive, $newArchive);

// Extract tar file
$tarPath = "$archive.tar";
$tar = new PharData($newArchive);
$tar->decompress();

// Finally, get sources
$src = new PharData($tarPath);
$src->extractTo(dirname($archive), null, true);
