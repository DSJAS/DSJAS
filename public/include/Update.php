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

function getLatestAvailableVersion($band)
{
    return "1.0.0"; // Hard code this value until update services are available
}

function isUpdateAvailable()
{
    $currentVersion = getVersionString();
    $latest = getLatestAvailableVersion(getUpdateBand());

    return $currentVersion != $latest;
}

function isInsiderBand()
{
    $band = getUpdateBand();

    if ($band == "stable" || $band == "") {
        return false;
    }

    return true;
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
