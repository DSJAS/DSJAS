# Anchor points - *developer documentation*

DSJAS themes are, by definition, client-sided applications. Your code, when you write a theme, will be delivered to the client in a static way and your code running on the server not designed for interacting with the bank. So, your theme can't really have very much server-sided behaviour.

So, DSJAS provides files called *Anchor Points* to allow for interaction with the bank.

DSJAS anchor points are PHP files placed in real paths on the server (in other words, they are real files - not created by permalinks or theme loading). When a request comes in for one of these files, DSJAS will perform server-sided actions before loading the files for your theme. Usually, the code DSJAS runs first will be looking for things such as POST arguments to perform actions.

On your end, all you have to do is:

1. **Know how your interface should interact with the anchor point:** Your client side code needs to send the correct headers to the correct location in order to interact correctly with the anchor points.
1. **Send information to/provide a user interface for the endpoint:** For example, create a login page for the *Login.php* anchor point.

## Why are they called anchor points?

In DSJAS, it's never really certain if a URL will be present or not, because it's totally up to the theme to provide something at the end of that URL if it wants to.

However, these URLs will always map to a file and will always be present, no matter the configuration of DSJAS. So, they are anchor URLs.

The points part of the name stems from the fact that these act kind of like endpoints in an API.

## An analogy

Imagine a control panel attached to a wall. This control panel has a front with many buttons and control knobs attached to it. These can be used by the user and feedback can also be given on a display on the panel.

Behind it, there are a large assortment of wires which attach to the control knobs and dials on the front of the panel. The knobs are connected at a socket which the panel on front plugs into.

Now imagine that that panel is the theme. It provides a way for the user to communicate, with a nice interface, with the wires and complex machinery behind the panel.

The wires and workings behind the panel are the inner workings of DSJAS's core.

Let's now imagine that you can unscrew the control panel and attach another one. The behind of the panel fits to the wires behind in the same way the last one did but it looks completely different. However, functionally it is exactly the same.

This is an analogy for DSJAS themes. The theme is just a nice interface which the user can use to tell the wires and knobs behind the panel what to do easily. The front panel is completely interchangeable. The sockets the wires behind exposes are the anchor points, ways for the theme to transfer information to the DSJAS core.

Hopefully this analogy cleared up the workings in somebody's mind (and didn't do the opposite and confuse somebody thinking about dials and knobs).

## An example

In order to login to the online banking dashboard, DSJAS will perform actions such as updating the session variables and logging events to the log. In order for this to be themed, you need to create a file called "Login.php" under the "user" folder. This will then be linked to the anchor point at the same path in the server.

When a request comes in for */user/Login.php*, Apache will recognize that there is a file on the server at that path. So, Apache will execute it and send the result to the client. That file is the DSJAS anchor point.

DSJAS will first perform actions (or whatever the point does) and then load your theme as required.

> **Important:** DSJAS will sometimes do things such as redirecting before loading your theme, or may choose not to load it at all. For example, if a user page is requested and the user is not logged in, DSJAS will kill execution after redirecting to the login page

In order to submit information, your theme just needs to POST or GET to the required anchor point. In this example, the login screen will POST to the point */user/Login.php* with the headers *username* and *password*.

It's also important to note that DSJAS will probably not load your theme when sending a response and will instead send basic headers with instructions to the client (such as a redirect or HTTP error code).
