# Lifecycle of a request - *core documentation*

DSJAS HTTP requests are much more complicated than a standard PHP web application. Before they are even processed, requests have to go through a two stage rewrite process and some Apache magic to make theme permalinking possible.

So, this guide will document the lifecycle of a DSJAS request, from start to end, documenting everything from how Apache rewrites URLs and how DSJAS detects a permalink load requests to how the anchor points are applied and how the load routine works. We will also go over how legacy versions of DSJAS tackled these problems (and why those methods aren't good enough today).

So, let's jump into exploring the lifecycle of a DSJAS request.

## Stage 1: Apache handling

So, an HTTP request has comes in from a client. Before we even jump into DSJAS code, we have logic and complications to handle. In the root of the DSJAS code, there is a *.htaccess* file. This file contains instructions that Apache will follow when a request comes in. It is loaded and compiled at server start, meaning that there is minimal overhead for this method.

It's important to note that there are multiple htaccess files around the DSJAS project, but the global one is always loaded. The files lower down in the directory structure are usually to deny access to sensitive files or change PHP configuration in real time.

So, let's take a look at the main htaccess file:

```apache
# Main .htaccess file for the bank
# Copyright 2019 - Ethan Marshall

# Turn on rewrite engine
RewriteEngine on

# Pretty permalinks support
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php?permalink=1 [L]

# Deny access to certain areas
<Files ~ "\.ini$">  
Order Allow,Deny
Deny from All
</Files>

<Files ~ "setuptoken.txt$">  
Order Allow,Deny
Deny from All
</Files>

# Custom error pages
ErrorDocument 400 /error/Error.php?code=400
ErrorDocument 401 /error/Error.php?code=401
ErrorDocument 403 /error/Error.php?code=403
ErrorDocument 404 /error/Error.php?code=404
ErrorDocument 500 /error/Error.php?code=500
```

Let's talk about some of the more obvious things first. If an unhandled 404 error happens (we'll talk about how we handle 404 requests later), we redirect to the custom 404 page. And, we do that with a whole host of other error codes also (but not all of them, we don't expect themes to provide content for some rare error message).

Ok, so we've got one huge block out of the way. Next, you will see that we deny access to the setup token. This is used in the install process and it is vital that it cannot be accessed remotely. You can see another deny directive above. This is to prevent configuration files (which contain database credentials, bear in mind) from being accessed remotely.

Ok, that slims down what we need to talk about quite a bit. We are now left with this:

```apache
# Main .htaccess file for the bank
# Copyright 2019 - Ethan Marshall

# Turn on rewrite engine
RewriteEngine on

# Pretty permalinks support
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php?permalink=1 [L]
```

This is the logic which makes permalinks possible. You may think, what are the use of permalinks? What do we need to be generating these for? The answer is theme files. The theme can provide custom content, as well as overriding anchor points we provide. When this is the case, it is really important that we spoof the URL so that the theme's root is essentially mapped to the server's root. Let's step through an instruction at a time and explain how this is achieved:

1. ```RewriteEngine on``` - In this instruction, we order the Apache rewrite module to activate. This allows us to tell Apache to dynamically rewrite the URL in certain circumstances.
1. ```RewriteBase /``` - Tell Apache that we want all rewritten URLs to be based on the server root
1. ```RewriteRule ^index\.php$ - [L]``` - In this instruction, we're telling Apache to ignore the permalinking engine when we are requesting Index.php. This is because we are sending all permalinks to Index, so we **do not** want Index itself to be treated as a permalink.
1. ```RewriteCond %{REQUEST_FILENAME} !-f``` - This rule prevents permalinks from being processed if the URL points to a valid file. This is what I was referring to earlier when I spoke of handling 404 requests. If the URL doesn't point to a valid file, this will activate before we return a 404, meaning that permalinks don't actually have to exist as a real file in the place Apache expects. We can rewrite the URL to handle it.
1. ```RewriteCond %{REQUEST_FILENAME} !-d``` - This is exactly the same as the previous condition, but instead for directories.
1. ```RewriteRule . /index.php?permalink=1 [L]``` - Finally, if all the setup passed properly, we redirect the request to the index page with a GET flag to enable the permalink engine.

So, we're finally there! Important things to note from this process is that URLs which point to files or directories which *actually do* exist are not processed by the permalink engine. This means that admin panel URLs and anchor points are unaffected. This is what allows anchor points to work and prevents us from having to permalink the admin panel.

## Stage 2: DSJAS load

Ok, we've finally made it to the first line of DSJAS code being executed. The Apache server will have encountered a PHP file and so calls upon the PHP Apache module to process the file for it. PHP willingly obliges and runs all of the DSJAS code in the process.

Now, at this point, the code running could be any of these three locations:

1. **Index.php** - The index file in the root. This will either load the front page or the permalink as requested by Apache while rewriting the URL.
1. **An arbitrary anchor point** - This anchor point will run some PHP before loading a file in the theme specific to it. If the theme doesn't provide the file for that specific anchor point, the PHP code above still runs (making it behave like an API endpoint), but a 404 message will be returned (more on this later).
1. **A real PHP file** - This is most common for the admin dashboard. As stated earlier, if a real file is encountered, we load it as though this were a normal HTTP request.

Ok, let's ignore the third option (as that's not related to the load process) and step through each of the different load routines.

### The index file

Ok, so we've made it to the Index file. At this point, either one of two environments will be encountered:

1. The *permalinks* GET header is set and we must process and load permalinks
1. We have no permalinks to load and just act like an anchor point which does nothing special

Stepping through the file, we first see a call to include the bootstrap file:

```php
require "include/Bootstrap.php";
```

This file contains code which starts sessions, includes bootstrap CSS and checks the installation state and if we need to jump to the installation page. We will ignore this file for now.

Skipping past the rest of the includes, we see these lines:

```php
$url = $_SERVER["REQUEST_URI"];

if (shouldRedirectToReal($url)) {
    redirectToReal($url);
}
```

These lines are a way to catch strange errors caused by badly written themes. If an anchor point is linked to without an extension (which should never happen), we will end up loading the theme file through this index file, but not the anchor point itself. That means that the dynamic code in that file will not be run and we will just be serving a static version of that page. This line is a safety net for themes which fail to recognise this and follow specification. It checks if we are trying to permalink to a file which actually exists (but with an extension/in another case) and set a redirect header to tell the browser to make another request to the correct file.

It's important to note that this can't forward POST headers, meaning that we aren't able to save themes that send their data to the wrong place. Sadly, those themes will just be broken.

Next, we come to these lines:

```php
$splitUrl = explode("?", $url);

if (count($splitUrl) > 1) {
    fixGetHeaders($splitUrl[1]);
}

$usableUrl = stripGetHeaders($url);
```

First, we're getting the GET headers in the URL by splitting on the character which denotes the start of the GET headers. If the split found that there were headers present, we fix them by assigning the PHP superglobal ```$_GET``` to each of the values. This is a fix for an issue that permalinks cause. It occurs because Apache overrides the URL that is loaded, so all that PHP would normally be able to see is that the *permalink* GET header is set. This is bad, because we need for GET headers to be accessible at all times, no matter if they came through the permalinks system.

We then strip the headers from the URL so that we don't later treat them as part of the file URI in the load process. Essentially, we don't go looking for a file called */user/Login.php?test* when we want to look for a file called */user/Login.php* with the test GET header set.

The next code block reads:

```php
if (shouldProcessPermalink()) {
    $info = processPermalink($usableUrl);

    $page = $info[0];
    $dir = $info[1];
} else {
    $dir = "/";
    $page = __FILE__;
}
```

This code is in charge of determining the file which we want to load. You can see that we use the ```shouldProcessPermalinks()``` function to determine if we should be using the permalink provided to attempt to load from the theme directory. If not, we load the homepage. If so, we process and parse the permalink and use the provided info to load the file from the theme. The function ```processPermalink()``` uses the ```$usableUrl``` variable we obtained earlier to get the basename and dirname to load.

Finally...

```php
// Jump to main DSJAS load code
dsjas(
    $page,
    $dir,
    function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    }
);
```

we load the site (which is a whole other story in itself).

### An anchor point

Anchor points essentially do the same as the index file, but without processing permalinks. For this reason, you cannot load permalinks through any anchor point - it must be the index page.

The simplest possible anchor point would look like this:

```php
dsjas(
    __FILE__,
    "/",
    function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    }
);
```

Just jump immediately to the load routine with the current filename as the file to search for in the root.

Most anchor points exist to add some dynamic core behavior, however. This means that you will commonly see code like this...

```php
if (shouldAttemptLogin()) {
    $success = handleLogin($_POST["username"], $_POST["password"]);
    if ($success[0]) {
        redirect("/user/Dashboard.php");
    } else {
        redirect("/user/Login.php?error=" . $success[1]);
    }
    die();
}
```

before jumping to the load code. This exact snippet was taken from the *Login.php* endpoint.

### Shared code

It's important to note that the behavior of the load routine is *exactly* the same for all pages that call it. The only thing that differs is the code that runs before it and how the load routine's arguments are obtained. For example, the index page obtains them through headers, and anchor points always pass the same value.

In addition, the bootstrap file is the same everywhere, which is how we make all pages redirect to the install page if required (and similar behavior).

## The legacy way

Before pretty permalinking was added, DSJAS would only configure Apache to serve requests to real files. While making this legacy system, the thought struck me that the system was flawed: we would either have to have every theme stick to a defined structure and add nothing - or we would have to somehow dynamically create files on disc every time a theme was enabled which would forward the request to those files.

In the end, I came up with - what I thought was - an ingenious system which is kind of like the worst way of permalinking ever.

Essentially, the Index page contained logic checking for a header called *content*. This magic header contained a relative path to the theme file to be loaded. The index page would then do the routine we have today (minus certain aspects) with loading from the theme directory and everything else.

This was where the legacy API (which is now either defunct or removed, depending on your version) ```getContentURL($file, $localDir)``` came from. It allowed themes to get a link to the URL that they could use for custom theme content. Today, the APIs ```getThemeContent()``` are used in a similar way, but more for client side request to static files in the theme directory. As those don't matter as to how they look, they just link directly to the theme directory.

So, this worked fine for a few weeks while I was working hard at making the default theme good. You have to bear in mind that this was only just after I moved all the default theme code out of the files we now call anchor points (yes, it was that early on in development). I went about replacing all the references to theme files with the new API. It worked great!

But it looked horrible.

It just looked really bad. On top of that, it was vulnerable to arbitrary file read vulnerabilities - without being any real way to fix it. It was insecure in design and it looked horrible. Clearly something had to change.

So, I did some research on how WordPress achieves pretty permalinking and tried to replicate it. To this day, the DSJAS htaccess file contains snippets from WordPress's default one (by which I mean the entire rewrite section - with modifications).

At first, I was disheartened with a tidal wave of bugs and broken theme pages - with edge case after edge case cropping up. But, I figured out how to fix all of it in one go. The one thing that was causing the issues was missing headers and misuse of links. The default theme was making heavy use of a now-removed feature of the htaccess file that removed the extension from URLs. The reason it has been removed now is because this is handled in theme load code automatically. Linking to this would cause headers to be all screwed up and the anchor point being loaded incorrectly. This is where the first block of the index page comes from. And, missing headers, which is a whole story in itself (complete with a montage of me trying everything I could to fix the problem).

The point is though, in the end, I figured it out. Everything works and we have the nice system that is in use today.

## Summary

The DSJAS load process is multi-stage and complicated. Before we even run any code or do anything DSJAS related, Apache runs a whole mini-program in itself!

The great thing about all of this, however, is perspective. From the perspective of us, the developer, we can make a certain endpoint dynamic by just adding a page. From the perspective of a theme developer, their pages will magically become the root of the server on request and links magically correct themselves if required. From the perspective of the user, the site looks real and functions like a real bank site would.

Just be glad that I scrapped the old system so early on in development.
