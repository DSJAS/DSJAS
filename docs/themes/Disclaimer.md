# **All theme developers should read** the following disclaimer before writing any theme code

DSJAS themes give you as much freedom and control over a user's site as physically possible to give to you with PHP. This is great for you, because you can create a theme that does pretty much anything you want: dark style theme, vampire theme or dark net theme; the choice is yours!

However, in the words of Uncle Ben:

> With great power comes great responsibility

So, please use your power responsively by considering the following guidelines.

## Scope of operation

When a user installs your theme, they're not expecting any changes to happen to their computer, server or environment in general: they're expecting the look and feel of their site to change **and that's it**.

Your theme is **not** meant to be changing settings, installing programs or really doing anything other than outputting HTML which can be sent to the browser. This is the sole scope of operation for DSJAS themes - to change the look of the bank.

Further, the admin panel is strictly off limits to installed themes. Modifying or attempting to override DSJAS admin panel files should be considered malicious behaviour and will not be tolerated.

As a rule of thumb, if you wouldn't expect a theme to be doing something to your server, *don't make your theme do it to other people's servers!*

## Giveaways

Generally, in DSJAS development, we refer to a "Giveaway" as some element of the program or theme which could give away to the scammers that this is not a real bank. For example, the DSJAS login page says in bold lettering **Login to DSJAS** in the header. If a scammer makes their way onto this page, that is a massive *giveaway*.

Your theme should **never**...

1. Intentionally give away to the scammers that this is not a real bank, unless the theme is expressly designed to include a way for the user to reveal. Themes which intentionally give away to the scammers without the user's permission should be considered malicious and should not be published. Please don't try and ruin people's day by making a theme that is intentionally designed to ruin their baiting session.
1. Link to the admin panel. The admin panel is private and never referenced by the default theme *for a reason*. As far as any user of the bank is concerned, there is no admin panel, DSJAS settings or separate login page. If you include a link to the admin panel and the scammer clicks on it, being curious, it's game over. Don't do it; it's too much of a risk.
1. Be intentionally dysfunctional. Themes should always at least function to the point where the page will load and be navigable. Modules can add this kind of behavior, but themes should not be created with the express intention of crashing the browser, breaking the site or not loading correctly. You can pretend to have broken behavior (such as DSJAS's default theme pretending there was a technical fault in the application page), but your theme should not actually break on purpose

## Monetization and advertising

DSJAS is free and open source software partially in order to respect the privacy and security of those who are using it. We collect *zero* usage data and *zero* data in general. By extension, our users expect that themes should also be respecting their privacy in a similar way.

DSJAS themes should be free of things such as injected advertising, from providers such as Google AdSense or Microsoft Ads. This is not only because of the fact that they do not follow our privacy policy but also because that's not what themes should be doing. Your theme should contain your own content. If you want to add advertising into the site, please add it yourself for comedic value or to make the site look more authentic.

In addition, themes should **never** contain paywalls or content which is blocked and requires you to pay to use it. If you want to monetize your theme, might we suggest that you simply charge for copies.

Basically, if you want to monetize, charge for a copy. No bloat, no data collection - nothing nasty like that.
