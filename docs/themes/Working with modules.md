# Working with modules - *developer documentation*

DSJAS modules are, at their core, a way of extensions to insert small snippets of code into the DOM before it is sent to the browser. This gives rise to some interesting prospects, as it means that modules can modify everything on the client - without ever needing to write on or access the server.

However, there is a big problem here. DSJAS themes change everything that is sent to the client. So, how are modules expected to add do the content there when they know nothing about the content that is going to be on the page. After all, modules are expected to be theme-independent.

The solution to this comes in the theme API. The theme API provides a method of communicating with modules called ```module descriptors```. Module descriptors are markers on the page that tell modules where well-defined parts of the page are located. For example, themes could place a marker to tell modules where the navigation bar or footer are.

These markers can then be selected by modules to tell DSJAS where to inject the module code. So, for example, the modules that insert content into the login form use descriptors for the login form.

Available module descriptors are well defined in the theme specification. If a theme does not provide a specific module descriptor that a theme wants, it will simply not have that component of it loaded. This means that incompatible themes will be silently handled.

And, if worse comes to worse, modules always have the **all** descriptor provided by DSJAS itself (and a handful for specific anchor points/categories of pages).

The downside of this system is that is can be quite involved for theme authors. You have to define that marker yourself and make sure that you provide all proper ones if you want full theme compatibility.

Let's talk about how you can use the system.

## Using the module descriptors system

The **general** theme API provides the function ```addModuleDescriptor()```. This function can be called from any context in the theme and will hook the marker. This means that the location where you call the function will be where the module outputs.

Let's look at this function more specifically.

The only parameter to this function is a string - ```$descriptorName```. As the name suggests, this is the name of the descriptor which you wish to add.

### For example:

```php
/*
    This adds a module descriptor called 'alert-area'
    to the page. This will load any modules that
    hook to that descriptor. This one is commonly
    used for modules that want to add bootstrap
    alerts
*/
addModuleDescriptor("alert-area");
```

So, let's say that we write a theme that looks like this:

```html
<html>
<h1>This is a header</h1>
<div id="alerts" class="card card-fluid rounded">
    <?php addModuleDescriptor("alert-area"); ?>
</div>
</html>
```

Let's now say that we have a module enabled called "annoying alert". This module hooks onto the *alert-area* marker. When DSJAS loads the theme, the call to ```addModuleDescriptor``` will be replaced with the content of the module.

Let's say that "annoying alert"'s alert area segment contains this:

```html
<div class="alert alert-warning">
    <strong>This alert is totally pointless!</strong> Don't bother with it
</div>
```

So, the final look of your alert area would be this (assuming there are no other modules enabled that hook to that marker):

```html
<html>
<h1>This is a header</h1>
<div id="alerts" class="card card-fluid rounded">
    <div class="alert alert-warning">
        <strong>This alert is totally pointless!</strong> Don't bother with it
    </div>
</div>
</html>
```

If there are any more modules enabled that hook to that event, they will be placed after the one before it. Modules will be placed in the order that they were installed (with built-ins first).

## More resources

* The themes specification provides a list of usable module descriptors
* The module documentation provides documentation from the perspective of module developers
* The default and default-minimal themes are fully module compatible
