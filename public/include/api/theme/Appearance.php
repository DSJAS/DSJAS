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

/*
    THEMING API
    ===========

    This file contains the functions and APIs required to write a theme
    for DSJAS.

    It does nothing on its own, but does provide useful utility functions
    for theming scripts and provides a way for a theme to be consistent
    in behaviour to the rest of the site.

    For more information on the theming API, please refer to the API
    documentation.

*/

function setTitle($title)
{
    echo ("<title>" . $title . "</title>");
}

function setMeta($metaTag, $content)
{
    $metaText = "<meta " . $metaTag . "=" . $content . ">";
    echo ($metaText);
}

function getThemeContent($scriptName, $themeRelativeLocation = "/")
{
    $useDefault = $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_THEME_CONFIG, "config", "use_default");

    if ($useDefault) {
        $themeName = "default";
    } else {
        $themeName = $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
    }

    $path = "'/admin/site/UI/";
    $path .= $themeName;
    $path .= "/";
    $path .= $themeRelativeLocation;
    $path .= $scriptName;
    $path .= "'";

    return $path;
}

function getRawThemeContent($scriptName, $themeRelativeLocation = "/")
{
    $useDefault = $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_THEME_CONFIG, "config", "use_default");

    if ($useDefault) {
        $themeName = "default";
    } else {
        $themeName = $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
    }

    $path = "/admin/site/UI/";
    $path .= $themeName;
    $path .= "/";
    $path .= $themeRelativeLocation;
    $path .= $scriptName;

    return $path;
}
