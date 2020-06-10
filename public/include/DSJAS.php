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

require_once(ABSPATH . INC . "Module.php");
require_once(ABSPATH . INC . "Module.php");

require_once(ABSPATH . INC . "Customization.php");

function dsjas($fileName = "Index.php", $dirName = "/", $moduleCallBack = null, $defaultModuleHook = "all", $additionalModuleHooks = [])
{
    $fileFilterName = pathinfo($fileName, PATHINFO_BASENAME);
    $fileFilterName = strtolower(explode(".", $fileFilterName)[0]);

    $moduleManager = new ModuleManager($fileFilterName);
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

    // Define globals for theme API
    $GLOBALS["THEME_GLOBALS"] = [];

    $GLOBALS["THEME_GLOBALS"]["module_manager"] = $moduleManager;

    $theme = new Theme($fileName, $dirName, $useTheme);
    $theme->loadTheme();
    $theme->displayTheme();
}
