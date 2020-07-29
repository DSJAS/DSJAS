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

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/Customization.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/vendor/hooks/src/gburtini/Hooks/Hooks.php";


function getCurrentThemeName()
{
    $default = $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_THEME_CONFIG, "config", "use_default");

    if ($default) {
        return "default";
    } else {
        return $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
    }
}

function getBankName()
{
    return $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_GLOBAL_CONFIG, "customization", "bank_name");
}

function getBankURL()
{
    return $GLOBALS["THEME_GLOBALS"]["shared_conf"]->getKey(ID_GLOBAL_CONFIG, "customization", "bank_domain");
}

function addModuleDescriptor($descriptorName)
{
    \gburtini\Hooks\Hooks::run("module_hook_event", [$descriptorName, $GLOBALS["THEME_GLOBALS"]["module_manager"]]);
}
