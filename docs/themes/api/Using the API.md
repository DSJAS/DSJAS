# Using the DSJAS theme API - *developer documentation*

The DSJAS theme APIs are just PHP source files which contain the necessary code to interact with the core of the DSJAS site.

Internally, there is a lot going on, with systems like theme globals and hooks being used to pass information between your theme script and the main load code. However, the API is designed to allow you to completely forget about all of that and just write a theme - no worries about backend security or interactions with DSJAS. We handle everything for you - and that's the theme API.

## Including DSJAS theme APIs

To include DSJAS APIs, the ```THEME_API``` constant is provided. This constant automatically generates the correct API location for each request. So, essentially, we can automatically fill in the absolute path for you - all you need to do is append the filename which you need to include.

For example, if you wanted to include the **users** API file, your require statement would look something like this:

```php
require THEME_API . "Users.php";

echo(getDisplayName());
```

### Include safety

To be safe (and prevent collisions with your bootstrapper or other code), we recommend always using the ```require_once``` statement over the ```require``` statement when including theme APIs. The effect of this is that, if you have already included this API file somewhere, PHP will ignore the include statement. Essentially, it prevents errors like this:

```bash
PHP Fatal Error on Line 1234: Class of name 'Theme' already declared on line 4321 of /home/user/code/DSJAS/Theme.php
```

## Theme API functions

Theme API functions are placed in the global scope and can be called like regular functions from anywhere. No namespaces are used, so you may need to be careful if your theme declares custom functions.

## Reserved names and naming collisions

The DSJAS theme API does not, as yet, utilize namespaces. This means that declaring a function that the theme API contains may cause a PHP error. Obviously, nobody wants a PHP error in their script, so it's important to make an effort not to declare functions that are clearly included in the theme API.

In addition, some global variables and constants are reserved when using the theme API. The most important reserved variable name is...

```php
$THEME_GLOBALS
```

This variable is a global variable injected by the theme loader and is used to pass info to the theme. It is an associative array which is queried by the API functions. If corrupted or overwritten, some functions will cause errors and others will fail to run at all.

## Conclusion

For a quick TLDR style conclusion, to include DSJAS API files, use the ```THEME_API``` constant and append the required API source file to it. Some variable and function names may be reserved when including theme APIs. Please be aware of that.
