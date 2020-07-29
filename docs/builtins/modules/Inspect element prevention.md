# Inspect element prevention

    Many scammers will attempt to use the inspect element tool in your browser to edit information and trick you. This module intercepts the mouse click event, making this very difficult to do.

## About

This module is a lot more straightforward than most others: it just prevents the use of inspect element on the page using regular methods (namely the right-click context menu and F12).

The reason this is useful is because a lot of scammers will like to sign in to your "bank" and use inspect element to edit the webpage and try to trick you.

So, if they can't do that, they will have to go a bit off script as they try and continue their scam.

## What does this module do?

This module attaches an event handler to the body of the document which intercepts all right click events and denies them. It does the same to the F12 hotkey and the CTRL-SHIFT-I chord.

This is achieved with an event handler which returns a result which is not true. This instructs the browser to ignore the event entirely.

When active, no context menu will appear on any element on the page.

## How do I use this module?

Sit back and watch the scammers struggle to find out why inspect element isn't working. And, after you've had enough fun...

### Re-enable inspect-element

You may wish to allow the scammers to continue with their scam after you have had enough fun not letting them inspect element.

To do this, press the key combo **ALT+SHIFT+O**. A good way to remember this is because an **o**verlay is used as one of the prevention methods. ALT+SHIFT+**O**verlay.

No visual feedback will be given when the hotkey is used in order to hide the fact it was used. Don't worry when nothing seems to change; it has - you just can't see it yet. You can also take advantage of this by doing it in the middle of the attempt or right before the scammer gives up. Use your imagination!

## Possible giveaways

This module **does** print the usual text in the JS developer console regarding the author and the module name. This is noted as a giveaway on all builtin modules, but more so in this module. When this module is used, scammers will be actively opening the developer tools panel. Therefore, clicking on the developer console could be a giveaway. If a scammer becomes suspicious, you can clear most developer consoles with the hotkey CTRL-L or SHIFT-L. Or you can type ```console.clear()```

As mentioned above, no context menu will appear on any elements on the page. This could be a giveaway, as many scammers will be well informed that a context menu will always appear. If you think that they are getting suspicious of this, use the hotkey as soon as possible.
