# A rather overactive security mechanism

    In order to protect your security and privacy (and to make it annoying for fraudsters to get into your account), DSJAS will end your session every three-five minutes if you are inactive.

## About

Scammers usually take quite a bit of time to do their work on your bank account or computer. Luckily, you have this module to make that prospect of the scam quite annoying.

This module will "detect inactivity" and erase your session cookie if successful - effectively signing you out from all pages and causing any in-progress changes to be lost.

> **Danger:** This module **erases all sessions, from the admin dashboard too!** If you're actively making changes from the same browser (which you shouldn't be), this module may erase your session and cause *changes to be lost.* If you wish to use this module, you **must not** configure DSJAS, install modules/themes or generally use the admin dashboard from the same browser as the running scam.

## What does this module do?

Every minute, there is a 1/3 chance that you will be logged out due to "inactivity". On switching pages, this timer is reset. This will not run on the first minute in order to prevent you from being signed out almost immediately.

The page which is displayed when you have been signed out due to "inactivity" is displayed below:

![Signed out due to inactivity](https://i.imgur.com/q5qvKPy.png "This will display after you are signed out for inactivity")

Notice that the content is still present in the background. Although this content is still here, all event handlers are removed by the module. In addition, dynamic content will be unavailable due to the session token being erased. This means that the content behind the overlay is essentially destroyed.

## How do I use this module?

This module will be applied automatically to all online bank pages. *However*, this includes the login pages. Unfortunately, this could mean that you are "signed out for inactivity" while on the login page.

You don't really need to give any input, but do need to click on the "Go to login page" link in order to get back to the login page.

**Please,** head the warning mentioned above about the admin dashboard. This module **will erase all your unsaved changes, without advance notice**. Please don't use the admin dashboard on the same browser as this module. This module only affects browsers which have an online bank page open. Before opening any of these pages *save your changes and close the admin dashboard*.

## Usage risks

1. **Combined sessions** As mentioned several times before, please don't use this module and the admin dashboard together, as it will erase the session token for the admin dashboard.
1. **Large numbers of dead sessions** This module will not delete the PHP session token on the server. DSJAS will automatically clean these up occasionally with the help of PHP's garbage collector. Your disk space may increase in usage before the garbage collection.
1. **Server load** This module doesn't make any requests to the server, but does cause you to need to reload the pages and login quite often. This increases server load.

## Possible giveaways

This module could be a little over the top and ridiculous. Some scammers may get angry and quit attempting to infiltrate the bank after they have been locked out a few times.

In addition, the minimum time you could be kicked out from is two minutes. This isn't very much time at all and could cut off some interesting things they are trying to do on the site. If you want to observe the interesting tactics they might employ or try to see the scam in its entirety, this module probably isn't for you.

Finally, the module doesn't actually check for inactivity and instead just goes through the random number routine mentioned earlier. Therefore, there is a slight chance you will have your session ended while actively doing something on the site.
