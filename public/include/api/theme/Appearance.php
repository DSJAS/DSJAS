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
    $config = new Configuration(false, true, false, false);
    $useDefault = $config->getKey(ID_THEME_CONFIG, "config", "use_default");

    if ($useDefault) {
        $themeName = "default";
    } else {
        $themeName = $config->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
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
    $config = new Configuration(false, true, false, false);
    $useDefault = $config->getKey(ID_THEME_CONFIG, "config", "use_default");

    if ($useDefault) {
        $themeName = "default";
    } else {
        $themeName = $config->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
    }

    $path = "/admin/site/UI/";
    $path .= $themeName;
    $path .= "/";
    $path .= $themeRelativeLocation;
    $path .= $scriptName;

    return $path;
}
