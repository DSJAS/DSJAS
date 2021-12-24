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

require ABSPATH . INC . "vendor/requests/library/Requests.php";


define("LATEST_UPDATE_ENDPOINT", "https://dsjas.github.io/update/DSJAS/latest-");
define("ARCHIVE_ENDPOINT", "https://github.com/DSJAS/DSJAS/archive");


static $__version_information;


function getMajorVersion()
{
    $info = loadVersionInfo();

    return $info["version"]["major"];
}

function getMinorVersion()
{
    $info = loadVersionInfo();

    return $info["version"]["minor"];
}

function getPatchVersion()
{
    $info = loadVersionInfo();

    return $info["version"]["patch"];
}

function getVersionName()
{
    $info = loadVersionInfo();

    return $info["version-name"];
}

function getVersionDescription()
{
    $info = loadVersionInfo();

    return $info["version-description"];
}

function getUpdateBand()
{
    $info = loadVersionInfo();

    return $info["version-release-band"];
}

function getSemanticVersion()
{
    $info = loadVersionInfo();

    $major = $info["version"]["major"];
    $minor = $info["version"]["minor"];
    $patch = $info["version"]["patch"];
    $band = $info["version-release-band"];

    $ver = "$major.$minor.$patch-$band";

    return $ver;
}

function getVersionString()
{
    $info = loadVersionInfo();

    $major = $info["version"]["major"];
    $minor = $info["version"]["minor"];
    $patch = $info["version"]["patch"];

    $ver = "$major.$minor.$patch";

    return $ver;
}

function parseVersionString($version)
{
    $around = explode("-", $version);
    $version = explode(".", $around[0]);

    if (count($around) > 1) {
        $band = $around[1];
    } else {
        $band = "";
    }

    if (count($version) != 3) {
        $version = ["-1", "-1", "-1"];
    }

    return [$version[0], $version[1], $version[2], $band];
}

function getLatestAvailableVersion($band)
{
    Requests::register_autoloader();

    $currentBand = getUpdateBand();

    try {
        $json = Requests::get(LATEST_UPDATE_ENDPOINT . $currentBand . ".json")->body;
        $decoded = json_decode($json, true);
    } catch (Requests_Exception $e) {
        return "0.0.0";
    }

    $version = $decoded["latest"];
    return $version;
}

function isUpdateAvailable()
{
    $currentVersion = getVersionString();
    $latest = getLatestAvailableVersion(getUpdateBand());

    $versions = parseVersionString($latest);

    if ((int)$versions[0] > (int)getMajorVersion()
        || (int)$versions[1] > (int)getMinorVersion()
        || (int)$versions[2] > (int)getPatchVersion()) {

        return true;
    } else {
        return false;
    }
}

function isInsiderBand()
{
    $band = getUpdateBand();

    if ($band == "stable" || $band == "") {
        return false;
    }

    return true;
}

function getArchiveLocation($major, $minor, $patch, $band)
{
    return sprintf("%s/%d.%d.%d-%s.tar.gz", ARCHIVE_ENDPOINT, $major, $minor, $patch, $band);
}

function loadVersionInfo()
{
    global $__version_information;

    if (!isset($__version_information) || $__version_information == null) {
        $jsonContent = file_get_contents(ABSPATH . "/Version.json");

        $__version_information = json_decode($jsonContent, true);

        return $__version_information;
    } else {
        return $__version_information;
    }
}
