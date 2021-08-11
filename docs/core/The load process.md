# The load process - *core documentation*

At the end of pretty much all anchor points and theme loading endpoints, you will find a call to a function that looks quite like this:

```php
// Jump to main DSJAS load code
dsjas(
    "some-file.php",
    "some-directory/",
    function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    }
);
```

This call jumps to the main load routine, which is some centralised logic for loading and bootstrapping the theme, modules and initialising the API. Essentially, this is very critical code which does the main part of the DSJAS logic.

In the "Lifecycle of a request" documentation, we left off this topic on this sentence:

*We load the site (which is a whole other story in itself).*

So, this documentation will talk about the "whole other story" of the load routine.

## The load file/function

All of the core DSJAS load code is located in */include/DSJAS.php*. This file contains load functions for different parts of the site. The routine we are interested in is:

```php
function dsjas($fileName = "Index.php", $dirName = "/", $moduleCallBack = null, $defaultModuleHook = "all", $additionalModuleHooks = [])
```

Let's take a look at each of the parameters in more detail:

1. ```$fileName``` - This is the name of the file to be loaded. This is used to load the file relative to the theme directory - as well as for the module *FileFilter* engine.
1. ```$dirName``` - This is the name of the directory, relative to the theme directory. In theory, this could be multiple directories deep, but current load code only accounts for the last directory in the structure.
1. ```$moduleCallback``` - The callback which will be called when the theme requests for a module to be loaded based on events. This exists so that custom mechanisms can do different actions on module load. It **must**, however, load all modules based on the callback name passed to it.
1. ```$defaultModuleHook``` - The default hook which will be used to load modules which wish to be loaded on page load (rather than in a specific location). The load routine automatically fires this event after the hooks have been initialised.
1. ```$additionalModuleHooks``` - Additional events to be fired on page load. This is used, for example, in the *user* folder endpoints to tell themes that they were loaded in this special context. This allows modules to filter by the category of page.

From hereon out, we will be stepping through the code in this function exclusively.

## Step 1: Parse and initialise the FileFilter engine

In order for modules to tell us what page they wish to be loaded on, we use an engine called the *FileFilter* engine. This takes in the path to the current file to be loaded and outputs a regular expression which will be checked against the value the module provides. It allows modules to have granular control over where they are loaded but doesn't have any overhead for the permalinking system.

This parsing is, in reality, quite simple, and is handled exclusively in this line:

```php
$fileFilterPath = str_replace(ABSPATH . "/", "", $fileName);
```

As you can see, a string replace operation has been ran on the string provided, intent on removing the contents of the "ABSPATH" constant from the path. This will convert an absolute path to one which is relative to the installation root of DSJAS. This value will then be fed through a case-insensitive regex engine to determine if the module author intends for the module to be loaded here.

## Step 2: Load and process modules

Modules have to be loaded before the theme so that they can be placed physically higher in the page (and in the HEAD section). So, we get started parsing and loading modules now.

This happens in these two lines:

```php
$moduleManager = new ModuleManager($fileFilterPath);
$moduleManager->processModules(($fileFilterPath);
```

In the first line, we construct a ```ModuleManager``` object. This object loads module configuration and parses important fields for quick use later. After it has done that, it cycles through every entry in the module configuration and loads it into memory. We do this now so that the load code can make quick use of it later.

In the ```processModules``` function, we attach an event to the hooks system. This event will be called when we need to load a module from a theme event. All *module descriptors* fire using this event, with the actual event name being passed as a parameter. This allows multiple modules to attach to the same event.

## Step 3: Fire initial module events

```php
\gburtini\Hooks\Hooks::run("module_hook_event", [$defaultModuleHook, $moduleManager]);

foreach ($additionalModuleHooks as $hook) {
    \gburtini\Hooks\Hooks::run("module_hook_event", [$hook, $moduleManager]);
}
```

In the first line, you can see we fire the "module_hook_event" like before with the arguments of the default hook event. Next, we cycle through the additional events and fire all them.

This has the effect of loading any modules which have requested to be loaded at page load. So, essentially, this is the point where the majority of modules are actually loaded.

## Step 4: Load and parse configuration

This is the point where we begin to actually load the theme. The first step towards this is finding out which theme to load. To do this, we parse the configuration.

```php
$config = new Configuration(true, true, false, false);
if ($config->getKey(ID_THEME_CONFIG, "config", "use_default")) {
    $useTheme = DEFAULT_THEME;
} else {
    $useTheme = $config->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
}
```

Here, we check if the default theme is enabled. If so, we ignore the setting for the current theme and load the default.

## Step 5: Initialise theme globals

In this block of code:

```PHP
// Define globals for theme API
$GLOBALS["THEME_GLOBALS"] = [];

$GLOBALS["THEME_GLOBALS"]["module_manager"] = $moduleManager;
```

We initialise a global variable called ```$THEME_GLOBALS``` as an empty array. Next we pass a reference to the current module manager object so that it can be used by the API.

## Step 6: Load the theme

Finally:

```php
$theme = new Theme($fileName, $dirName, $useTheme);
$theme->loadTheme();
$theme->displayTheme();
```

We load the theme and display it. After the finally call to ```loadTheme()```, control is handed over to the theme and DSJAS's core code ends.
