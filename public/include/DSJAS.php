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

require_once ABSPATH . INC . "Module.php";
require_once ABSPATH . INC . "Module.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "DB.php";

require_once ABSPATH . INC . "Stats.php";

require_once ABSPATH . INC . "vendor/hooks/src/gburtini/Hooks/Hooks.php";

/**
 * The main DSJAS load routine
 *
 * Handles loading and sending modules, loading the theme and then sending that. In addition,
 * this routine handles setting the THEME_GLOBALS, which are used to send critical info
 * to the theme API/load functions Finally, any required statistics will be modified to the
 * appropriate values
 *
 * @global array $GLOBALS["THEME_GLOBAL"]               Sends information to the theme and/or associated API or load functions
 *
 * @param string   $fileName              (defaults to Index.php)      Used to load files from the theme content directory and the fileFilter engine
 * @param string   $dirName               (defaults to /)               The current single-level directory we should search for content in (ignored by fileFilter)
 * @param callable $moduleCallBack        (defaults to unset)  The callback we should jump to for the theme load hooks (used by things like the validator)
 * @param string   $defaultModuleHook     (defaults to all)   The name of the global hook we should call on theme load for modules that want content to load when the page does
 * @param array    $additionalModuleHooks (no defaults)    The names of additional callbacks which should be called on theme load (for example, user on user page load)
 *
 * @return void This function should not return until the end of script execution
 */
function dsjas($fileName = "Index.php", $dirName = "/", $moduleCallBack = null, $defaultModuleHook = "all", $additionalModuleHooks = [])
{
    $fileFilterPath = str_replace(ABSPATH . "/", "", $fileName);
    $moduleManager = new ModuleManager($fileFilterPath);
    $moduleManager->processModules($moduleCallBack);


    \gburtini\Hooks\Hooks::run("module_hook_event", [$defaultModuleHook, $moduleManager]);

    foreach ($additionalModuleHooks as $hook) {
        \gburtini\Hooks\Hooks::run("module_hook_event", [$hook, $moduleManager]);
    }


    $config = new Configuration(true, true, false, false);
    if ($config->getKey(ID_THEME_CONFIG, "config", "use_default")) {
        $useTheme = DEFAULT_THEME;
    } else {
        $useTheme = $config->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
    }

    // Initialise shared DB for theme API connections
    $hostname = $config->getKey(ID_GLOBAL_CONFIG, "database", "server_hostname");
    $databaseName = $config->getKey(ID_GLOBAL_CONFIG, "database", "database_name");
    $username = $config->getKey(ID_GLOBAL_CONFIG, "database", "username");
    $password = $config->getKey(ID_GLOBAL_CONFIG, "database", "password");

    $sharedDB = new DB($hostname, $databaseName, $username, $password);

    // Initialise shared configuration for theme APIs
    $sharedConfig = new Configuration(true, true, false, true);

    // Initialise statistics manager for theme APIs
    $statisticsManager = new Statistics($sharedConfig, $sharedDB);

    // Modify appropriate statistics
    $statisticsManager->incrementCounterStat("total_page_hits");
    $statisticsManager->incrementCounterStat("bank_page_hits");

    // Define globals for theme API
    $GLOBALS["THEME_GLOBALS"] = [];

    $GLOBALS["THEME_GLOBALS"]["module_manager"] = $moduleManager;

    $GLOBALS["THEME_GLOBALS"]["shared_conf"] = $sharedConfig;
    $GLOBALS["THEME_GLOBALS"]["shared_db"] = $sharedDB;

    $GLOBALS["THEME_GLOBALS"]["statistics_manager"] = $statisticsManager;

    $theme = new Theme($fileName, $dirName, $useTheme);
    $theme->loadTheme();
    $theme->displayTheme();

    $sharedDB->safeDisconnect();
}
