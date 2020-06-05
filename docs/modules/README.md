# DSJAS Modules - *Developer documentation*

> **Looking for help with using modules?** This guide is aimed at developers in the process of making a module. If you're looking for help with installing, configuring or using a module, please refer to the *administration* documentation

Hello, and welcome to the world of DSJAS module development! As mentioned on the admin dashboard, DSJAS modules can be one of the most fun and rewarding extension types to develop for DSJAS.

As well as allowing for pretty much unlimited control of what the site does in a user's browser, you can change existing functionality, such as the login form or the navbar links on the main page.

This combined with the ability to add UI elements allows you to add very interesting features to DSJAS.

And the best part is that modules can be swapped in and out of any theme the user chooses; they should work just the same and will modify and compatible theme with the same behavior.

## What modules are useful for

DSJAS modules were designed to change client functionality and be the last piece of the puzzle when it comes to ultimate customization. So far, we have developed ways to customize the overall appearance of the site and the behavior of DSJAS's core code.

So what's missing?

What is missing is what happens in the client: the browser. As far as the user is concerned, they are pretty much limited by what the theme provides and how that said theme communicates with the DSJAS core. Modules allow different behavior to be added in the browser to change how the site behaves toward the user in their browser.

This is important, or all DSJAS sites would be blank-slate banking simulators with no defining features which make it fun/suitable for scambaiting. Theme developers would have to resort to adding funny features to their themes. And, although funny themes are great, they shouldn't really be adding much client behavior: just the appearance and basic interface.

Modules are then added on top of this to change client behavior how the user wants it and we have a complete picture of how DSJAS can be changed in every way possible.

So, in short, modules add on top of themes to change behavior.

## The design: what should modules be used for?

| Behavior | Allowed/recommended | Reason | Example |
| -------- | ------------------- | ------ | ------- |
| Adding UI elements to places on the page | Yes | You are adding/changing the basic interface provided by the theme and building on top of what the theme provides | Adding a button to the navbar which reports fraud |
| Changing what UI elements do when clicked | Yes | You have changed the basic behavior that the theme provides, fulfilling the purpose of the module | Adding a Captcha which displays when a login button is clicked |
| Adding a dynamic behavior to the client | Yes | You are doing the thing modules are meant for, well done! | Making popups appear every few seconds |
| Modifying all the content on certain pages | Questionable | Module interference with the basic, non-interactive content of the site should be kept minimal and should only really be for the purpose of complementing the behavior added by the module | Changing the theme's mission statement page to specific text |
| Adding pages using client routing technology | No! | This is not changing client behavior and is instead going against the theme. How can your module fit with all themes? Modules should never override the theme and replace/remove/add entire pages of content, only modify **existing** content | Stopping the about page from loading and instead loading a different page from the client |
| Changing the style of the theme | Questionable | It depends on how major the impact is. Changing the entire theme to dark mode could be considered to be overriding the theme, but could also be swap-in behavior the user wants. However, changing the fonts all over the theme **is** overriding the theme and is too far | Changing the entire site to dark theme |

In general, please use the following golden rule:

    The theme controls the content, the module controls the behavior. However, the theme should always be given final control.

Basically, as a module developer, you're not trying to add lots of content to the site. Instead, you're trying to add interesting and fun(ny) behavior on **on top of** the provided content.

## Guidelines for sticking with themes' styles

Most DSJAS themes will be using the Bootstrap framework. Therefore, if you want the best chance of sticking to style, use Bootstrap.

Bootstrap provides easy to use CSS classes and components to go along with that. These come with pre-styling and can be dropped pretty much anywhere and look generically good. This means that you will most likely just need to stick to bootstrap for your styling.

However, some themes don't use bootstrap and may look completely different. You might be surprised to know that we *still* recommend using Bootstrap styling. As we said before, most Bootstrap components are designed to look good in a generic way: they can be dropped in pretty much anywhere and still look great.

## Interacting with DSJAS

Because your code will be running client-side, you can't really interact with DSJAS directly. Instead, you need to allow the theme's actions (for example, form post locations and button click actions) to run.

This will allow the theme to continue doing the important interaction with the DSJAS core layer while you can sit in your comfortable JavaScript container adding and removing buttons from forms to annoy scammers.

## Final words

Thanks for making it this far! DSJAS modules are a complex topic and can be quite difficult to master (or even get started in).

Please don't let the waffle scare you though. If you've read and understood the guidelines, all you need to know next is the API and you've done most of the work for creating a module.

All you need next is a little imagination and an inventive new idea to annoy the scammers.
