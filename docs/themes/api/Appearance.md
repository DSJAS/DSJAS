# Appearance - *DSJAS Theme API Documentation*

* **API Name:** Appearance
* **Description/use:** Used for dynamically changing/querying the appearance of the page in PHP
* **File Name:** Appearance.php
* **Full Path:** /include/api/theme/Appearance.php
* **Anchor point specific?** No
* **Provides/exposes:** Utility/convenience functions

## ```setTitle()```

> [!IMPORTANT]
> This function writes to DOM but does not return a value. **Don't** echo the output.

Queue an HTML title tag to be placed in the head section of the page. Usually, themes only have access to the body section of the page. However, if themes wish to keep the code clean, they can use this function. This is classed as a utility function.

* **Parameter - ```string $title```:** The new title to be used (rather than the default of the user provided bank name)
* **Returns:** ```void```

### Example

```php
setTitle("Welcome to " . getBankName()); // Will cause the title to be sent to the browser as "Welcome to <DSJAS>", for example
```

## ```setMeta()```

> [!IMPORTANT]
> This function writes to DOM but does not return a value. **Don't** echo the output.

Formats and queues a meta tag to be outputted to the head section of DOM. By default, DSJAS will automatically call this method twice for *title* and *description* meta tags. You can override these values.

* **Parameter - ```string $metaTag```:** The name of the meta property you wish to set
* **Parameter - ```string $content```:** The contained content of the meta tag
* **Returns:** ```void```

### Example

```php
setMeta("google_site_verification", "<your-token-here>");
```

## ```getThemeContent()```

> [!CAUTION]
> This function should not be used to link users to pages, but should **only** be used for retrieving static resources from the confines of the theme directory.

Returns a browser-friendly URL which points to a script or static resource in the theme's install/assets directory. This function allows you to avoid having to include stylesheet links, for example, to */admin/site/UI/[theme-name]/assets/thing.png*. This is bad due to the fact that all links will break if DSJAS's asset storage location changes for whatever reason. In addition, the link looks very ugly and is inconvenient to write.

* **Parameter - ```string $resourceName```:** The file name of the static resource. This should include the file extension but not the relative directory path
* **Parameter - *optional* ```string $themeRelativeLocation```:** The relative directory path. This should end in a trailing slash. Defaults to the theme's root.
* **Returns:** ```string``` The formatted browser-friendly URL to the static resource

### Example

```HTML
<img src="<?= getThemeContent("image.png", "assets/"); ?>">
```

## ```getRawThemeContent()```

> [!CAUTION]
> This function should not be used to link users to pages, but should **only** be used for retrieving static resources from the confines of the theme directory.

> [!WARNING]
> This function returns a filesystem path which could expose information about the server you are running on. This value should **never** be sent to the client.

Returns a server-side filesystem path to a static resource in the theme directory. This will not work on the client and is designed for themes which wish to split their codebase into separate files and include them server-side. This value should **never** be sent to the client, as it will expose the filesystem structure of the server. On some systems, this could include usernames.

* **Parameter - ```string $scriptName```:** The file name of the static resource. This should include the file extension but not the relative directory path
* **Parameter - *optional* ```string $themeRelativeLocation```:** The relative directory path. This should end in a trailing slash. Defaults to the theme's root.
* **Returns:** ```string``` The path to the resource using the server's path scheme - relative to the root of the DSJAS install

### Example

```PHP
// Includes another PHP file on the server without having to hard-code the theme directory
require(ABSPATH . getRawThemeContent("Functions.php", "include/"));
```
