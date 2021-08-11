# How modules work - *developer documentation*

Modules work in mysterious ways. Some would even call them random. But I just like to explain how they *actually* work, because I wrote the code behind them, so I should know.

Modules are essentially static pieces of code which are inserted into every page in either any location or a specified location. You can further filter by page name and type or if an error has ocurred or not.

So, in this guide, I will demystify the workings of modules and explain how your module is loaded.

## The anatomy of a module

Modules are essentially a configuration file. Yep, that's it: just a JSON file. But, this isn't any old JSON file; this tells DSJAS where to load content for the module from.

Essentially, a module contains several different organized units called *routes*. This makes it so that you can apply different behavior to different parts of the page or different pages entirely.

Routes always have an associated *triggerEvent*. This allows them to he loaded on certain events in the page's processing or certain locations in the theme. For example, all user pages provide the *user* trigger event. And, a theme may provide the *nav)bar* trigger event for adding UI content to the navbar on the page.

Let's look at the configuration of the example theme in some detail to understand more:

```json
{
    "name": "Example module",
    "description": "This is an example module which is shipped with the development version of DSJAS. It adds a label to any menubar and footer exposed by the theme",
    "version": "1.0.0",
    "hooks": {
        "bar": {
            "triggerEvent": "nav_bar",
            "loadCSS": false,
            "loadJS": false,
            "loadHTML": true
        },
        "footer": {
            "triggerEvent": "footer",
            "loadCSS": false,
            "loadJS": false,
            "loadHTML": true
        },
        "all": {
            "triggerEvent": "all",
            "loadCSS": false,
            "loadJS": true,
            "loadHTML": false
        }
    }
}
```

At the top of the configuration, we see the keys *name*, *description* and *version*. Some modules may optionally include an *information-link* property. This is used for the **More info** button in the admin dashboard. All of these properties are cosmetic and for the admin dashboard. Version is used in the process of updating, however.

What is interesting is the *hooks* JSON object. These *hooks* are the routes we were talking about earlier. Each sub-key is an individual route. The name of the sub-key is the name of the directory we are discussing. Sure enough, if we look at the directory structure for the example module, we see *all*, *bar* and *footer*.

Inside of each route key, we can see four properties. The first is the trigger event we discussed earlier. The next three are the instructions on how to load the route.

Routes can contain three files:

1. content.html
1. content.css
1. content.js

Each of these will be padded with appropriate tags (for example, script for JS and style for stylesheets). These will be added to the output sent to the client when the event requested by the module is hit.

It is **very important** to mention that if the requested event is invalid or never fires, the route will be silently ignored. This is to prevent errors when a page is loaded which the module expressly does not want to be loaded on.

---

You may also, sometimes, notice that modules include a *fileFilter* array. This is an array which is passed to the ```FileFilter``` engine in DSJAS. This allows modules to determine what files they wish to be loaded on.

The FileFilter engine will perform a case insensitive regular expression search on the current path. If a match is found, the module will be loaded. If not, the module, and *all* its routes, is ignored. The path provided to the engine is always relative to the DSJAS install directory - for instance: "user/login.php", not "/srv/http/user/login.php".

As a working example, the "Frustrating Login Process" module, which should only be loaded on the login page has a file filter of the following:

```json
    "fileFilter": [
        "login"
    ]
```

The characters "login" appear in the sequence "user/Login.php", so the module will be loaded. A more complicated example is the following:

```json
    "fileFilter": [
        "user\\\/(?!.*Login\\.php).*$"
    ]
```

In this case, the regex states that any file in the "user" directory can load the module, except "Login.php", which is negated with a negative lookahead. There are literally hundreds of other possible patterns which can be used to precisely match the file(s) needed. A regex pattern can be hyper-specific (load only for a single file or path) or very broad (any path with an "A" in the title). But, remember, all regex patterns need to be escaped suitable for both JSON and regex encoding/parsing - and DSJAS regex operations are **always** done in lower case.

## The lifecycle of a module

If you have read the guide on the lifecycle of a request, you will know that modules are loaded **before** the theme is displayed but after the styles, configuration and database have been bootstrapped. Basically, this means that your module will be sent to the client before the bulky theme.

Modules, despite common belief, are loaded in chronological order. Basically, the order they are configured in. In addition, the routes in each module are loaded in the order that they appear in the configuration file. Both of these are due to the fact that we just cycle through each module as they appear. The browser may re-arrange the tags on the client to "fix" our code, but that, sadly, can't be helped.

So, in the code, you will notice a class called ```ModuleManager```. This class represents all of the modules and their state. It is in charge of loading and displaying the combined result of all modules to send to the client.

So, let's drop in on line 49 of *Index.php*:

```php
$moduleManager = new ModuleManager(strtolower(pathinfo($info[0], PATHINFO_FILENAME)));
```

Here we see DSJAS setting up the ModuleManager class with the required info. The only argument passed is the current basic file name. This is used to configure the FileFilter later on.

Ok, now the module manager is set up, we continue doing our thing until lines 57-60, where you see the callback being set up.

```php
$moduleCallbackFunction = function (string $callbackName) {
    global $moduleManager;
    $moduleManager->getAllByCallback($callbackName);
};
```

DSJAS has to set up a custom callback for when a module requests to be loaded each time. The reason for this is so that features such as the verifier and the installer can run the module in a sandboxed environment, without actually sending module content. Essentially, we can override how the module is handled when a load is requested.

The callback used by most anchor points just uses the ```getAllByCallback``` function to load all modules for a specified callback name. This is essentially just the requested trigger event.

On line 62, we see the ```processAllModules``` function, which loads and prepares all modules for execution.

```php
$moduleManager->processModules($moduleCallbackFunction);
```

In reality, this function is cycling through every module and attaching hooks and filters to each route and module individually, ready for those hooks to be fired by either the theme or the core later.

Finally, the first module-facing event is fired, *all*:

```php
\gburtini\Hooks\Hooks::run("module_hook_event", ["all"]);
```

Some other pages may choose to run other initial events, such as the user pages, which run *user*. In this case, the line will look like this:

```php
\gburtini\Hooks\Hooks::run("module_hook_event", ["user"]);
```

After this, most anchor points pass control over to the theme and exit. The theme is then responsible for firing the events to load the rest of the modules via *Module descriptors*. These are designed to allow the theme to communicate to the module where certain elements on the page are in the source. DSJAS will load modules at the module descriptor (or event) they requested.

## Still confused?

I don't blame you! Modules are complex - and need to be by design.

If you need some hands-on examples, the example and built in modules are great places to start. They are built, not only as a genuinely useful tool for users to improve DSJAS, but also to demonstrate modules for developers.

We can recommend the "Especially Frustrating Login Process" and "Rather Overactive Security Mechanism" as good places to grasp the basics, especially the latter. These modules are both quite simple, but powerful and add useful and fun aspects to the site.
