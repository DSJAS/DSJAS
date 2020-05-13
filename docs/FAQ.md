# Frequently asked questions

## What is DSJAS?

DSJAS is a PHP web application designed to be installed on your computer locally. It mimics the look and feel of a fake bank account in order to string scammers along who are attempting to infiltrate online banking or steal money from you.

The design philosophy behind DSJAS is that anything should be able to change. For example, the name, motto or branding of the site can all be changed at the click of a button. In addition, the content, layout and look of the site can be completely changed with themes; the server behavior changed with extensions (or plugins - which are the same thing) and the client behavior (or what happens in your browser) can be scripted with modules.

In short, DSJAS is a simple implementation of a banking system. The rest is a blank slate for you and the community.
*Have fun :)*

## Why should I use DSJAS?

If you're interested in scambaiting, I truly believe that this piece of software can take it to the next level. With the tools DSJAS provides, you can really truly fool scammers.

## What's with the name? Does it stand for something?

DSJAS is an acronym. It stands for:

* **D:** Dave
* **S:** Smith
* **J:** Johnson
* **A:** And
* **S:** Son

Early on in development, I was trying to come up with a name that would mix a subtle reference to the scammers with a real bank-style name. I settled on Dave Smith Johnson as a reference to the terrible fake names a large proportion of call-centers use for their agents. Some highlights include "Dave Smith", "Adam West" and "Andy Lee". I just mixed one of the more common names "Dave Smith" with an official sounding company ending.

And, DSJAS was born.

## People talk about using XAMP and MAMP: what are they and why do I need them?

DSJAS is a website, so it needs a server to run on. XAMP and MAMP are just packaged versions of the Apache webserver with PHP installed. The reason these packages are recommended is because they are tested with DSJAS and just work. Also, they are designed to be easy for local webservers to be configured.

**Interesting fact:** I develop DSJAS using MAMP as the testing server. So, I can guarantee that MAMP works with DSJAS.

## What's even the point in DSJAS? Scammers can just see it's the same program and know it's fake!

The main point of DSJAS is that it can be changed in pretty much every way possible. I mean:

1. **Site name:** CHECK!
1. **Site appearance:** CHECK!
1. **Site behavior:** CHECK!
1. **Site content:** CHECK!

There's not really much more to change. After all, the content, appearance and behavior of a web application are what make it what it is. So, changing these should make the site unrecognizable.

### I'm still not convinced: what about the admin dashboard?

There are settings available which allow the user to hid all DSJAS backend pages. For example, the "Allow access to admin panel" setting allows the site admin to completely turn off the admin panel and replace all pages with a 404 error, mimicking as if they were never there in the first place.

### Ok, what about the URL? Scammers can see that you went to "localhost", not a bank!

DSJAS supports custom domain names, set up through the DNS hosts file. More information on how to do this is available in the install section of the docs.

So, essentially, you can tell your computer to map "https://djohnson.financial" to "https://localhost" and scammers will be none the wiser.

The DSJAS built in feature for this is unable to change your hosts file due to permission issues. You have to do this manually and inform DSJAS of the change through settings. Again, more info in the install section.

## Help! I think I've been scammed. What should I do?

First of all, stay calm. In the modern world, we have all of this great technology to reverse issues such as this. However, you do need to act quickly.

If you allowed somebody access to your computer, end their "support" session there and hang up the phone line on them. After that, uninstall the remote access software they used.

You'll also need to uninstall any malicious software they left behind. Luckily, antivirus software (such as malwarebytes and Windows MSRT) can do this for you. This will just ensure that you don't have any viruses left on the computer.

You should reset all of your online passwords to something completely unrelated to the old ones. If you logged in to your bank account, there is usually a button which reads "End all sessions" or "Log-out everywhere". This button causes anybody without your new password to be signed out. In addition, consider changing your computer's password.

Finally, pretty much all credit cards and banks have a feature to dispute transactions and billings. As long as you act fast, you can probably get your money back.

Only after you have get yourself and your computer to safety with the previous steps, consider reporting the scam to the authorities.

## Is this illegal? Can I get in trouble for this?

Absolutely not. You are well within your right to set up a webserver on your computer - anybody can. DSJAS is far from illegal software and will not get you into any trouble at all.

*However...* Calling scammers could be considered prank-calling by some people (especially video streaming sites - looking at your twitch). If you plan on publicizing what you do, you should apply a great deal of caution. In addition, you have to remember that the people you are calling **are criminals, no matter how inexperienced, unconvincing or stupid they may seem**. You wouldn't call the Russian mafia and give them your exact location, so don't give it to Dave Smith from Supremo Enterprises either, ok?

## What do each of the extension options do?

### Themes

Change the look, feel, content layout... you know what, let's put it this way: *themes change everything* - literally. Themes change what is displayed to your browser when you navigate to the bank. You could think of them to set the *theme* of the page.

Whilst they don't change how the site behaves, they do change pretty much everything else. If you want to change the toolbar color or make the background orange, this is the way to go.

In short, themes change how the bank looks on your computer.

### Modules

We said a second ago that themes change how the site looks. Modules do a similar job, but they change how the bank behaves. For example, if you want to add a button to the toolbar that brings up a support dialog, modules can do that. If you want to make it so that the login process is long and boring for scammers, modules can do that. If you want to make everything on the page spin when you press F2, modules can do that (I know because I made one that does just that).

And, if you want to make the server load pages intentionally slowly, yes... wait no, modules can't do that. That job goes to our next member of the team...

### Extensions

Extensions allow custom code to load at certain times on the server to perform server-sided actions. Whereas modules change how the site behaves on the client, extensions work on the server.

So, for example, if you want to force all transactions to take an hour to process, you can do that. The special thing about extensions is that they are also able to modify the data on the site. For example, the name-randomizer is a plugin I wrote which changes your bank name to a randomly generated one every 2 days.

With extensions, DSJAS provides the framework for a truly dynamic scambaiting experience.

### Plugins

> See extensions

The terms "Plugin" and "Extension" are used interchangeably, both by me and the site's code (and to some extent UI).

All you need to know is that when we talk about a plugin or extension, that is code running server-side. So, it can modify user details, but not the nav-bar color or form layout.

## Is DSJAS secure?

While DSJAS, admittedly, does not provide bank-grade security, we do provide just enough security so that you are not at any real risk from running this on your computer.

Generally, our security philosophy goes as follows:

> If a user can be exploited by an attack simply by running the program on *their computer*, we consider this a security flaw.

One major effect of this guideline is that we do not recommend exposing a running DSJAS instance to the wider web. DSJAS is designed for **local network use only**. There are some features of the site (such as allowing remote configuration of the database through the admin dashboard) which would be just too risky for applications such as WordPress, which need to stand up to anything.

As a rule of thumb, we recommend hosting the site on your local network and disabling all port forwarding for that machine through your router.

## Why is DSJAS so slow?

The short answer is, it isn't: it's probably something else. But, there are a few things we can say on the subject:

* **DSJAS is a heavy program:** DSJAS consists of thousands of lines of PHP code - which, in many cases, is very verbose and descriptive. PHP is known to become slightly slower with heavy scripts. Take WordPress for an example...

* **Having many plugins or modules active at once can degrade performance:** All plugins have to be loaded from disk into memory by DSJAS *before anything can even by sent to the client!* This means that, if you have several gigabytes worth of modules which are all enabled, performance will degrade a lot.

* **Slow disks = slow site:** Before anything can be sent to the browser, several operations on your storage disk need to take place. First, the DSJAS script needs to be loaded. Then, that script loads, possibly many, modules and plugins. Then, we need to load and process the theme, the configuration and much more. In short, DSJAS is very disk bound, so slow disks will really slow the entire site down.

* **Slow internet = slow site:** As with all websites, a slow internet connection will lead to poor load times.

* **Remote database:** If your connection is poor and you set your database server up remotely, it could take a few seconds for DSJAS to read from or insert to the database. If your connection is poor, we recommend hosting your database server on the same host as your webserver to reduce the network activity required.

* **PHP:** PHP is an interpreted language, meaning that it has to be processed in its entirety each time it is run. This can be a slow process, especially with large programs such as DSJAS. Refer to our comments on slow disks for more info on disk bottlenecks.

* **Your theme:** Some themes include many high definition photographs or JS libraries. The browser has to download these before it can show the webpage to you. If your theme is known to cause poor load times, maybe contact the developer to see if they could consider using minified or less JS libraries or consider compressing photographs. But, at the end of the day, there is little we can do about this. That issue is down to theme developers.

## My screen is white!?! What do I do?

A *whitescreen* (as we call it) happens when an error occurs in a script running on the site which causes PHP to stop executing the code. This could be, for example, a missing configuration file or badly edited script. Usually, this suggests a fatal error that DSJAS did not account for happening.

Sadly, without access to error logs, the only other advice we can give is to reinstall the site. Make a backup of settings and modules etc. Then, download the installer archive, copy the configuration across and you should be good to go.

If you still get a white screen after re-installing, re-install again but don't restore plugins and keep your configuration as default; they were likely the cause of the issue.

If you get a timeout error, make sure your webserver **and database server** are running. If you get connection refused error, make sure Apache (which is the DSJAS webserver) can access the internet and your firewall isn't blocking it. Also, refer to the previous step about servers being on.

Finally, sometimes PHP has an annoying habit of failing to load a script properly if the webserver has been idle for an extended period of time. If you have left the server idle (in other words, without accepting any requests) for a while, refresh the page as second time. If the page refreshes with the content loading correctly, you can take out your frustration on PHP.

## How can I write themes and extensions?

This documentation details all that and much more. We can't even scratch the surface in a short FAQ paragraph, so I'll just refer you to the theme folder in the GitHub docs. *Happy building :)*

---

*Got an FAQ you want added? Submit them to us by GitHub PRs or Issues* *Thanks, and happy scambaiting!*
