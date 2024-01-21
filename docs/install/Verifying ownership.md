# Verifying ownership - *The first step*

The first step of your install process is to verify that you have permission to use (or have access to) the webserver the site is installed on.

## Why is this required?

DSJAS must be able to *guarantee* that you have permission to be installing on the webserver. This is a measure which is taken by many CMS frameworks - such as WordPress.

Although DSJAS is not meant to be installed remotely, it is still important to be able to verify ownership before allowing the install to continue.

## The beginnings of the installer

When you navigate to the web installer for the first time, you will probably see a screen similar to this.

![Installer page](https://i.imgur.com/vHurEZB.png)

> **Help! I can't see that!** Did you follow the previous steps? You need to prepare your server for installation before you can get to this screen. Otherwise, you may have a technical issue. Please contact us for more info.

This screen provides instructions on how to complete the following steps. As the instructions state, you now need to prove your ownership of the server to continue.

In the case of DSJAS, we opted to place a file on your file system. You will need to get the contents of this file to prove ownership. The path to the file is shown at the bottom of the screen.

![Server root location](https://i.imgur.com/k92dNnq.png)

You can navigate to this directory with a command line (or terminal) or through a file explorer. In that directory, you will find a file called *setuptoken.txt*.

Inside of this file, you will find a long, randomly-generated string of characters. You should copy this. Then, click on **Continue to verification**.

> **Important:** If you don't see the token in the expected location (or the file is blank/contains something other than a random string), click on *Regenerate token*. The page will reload and the issue will be solved.

## Using the verifier

You should now see a screen which looks like this:

![Owner verification](https://i.imgur.com/BEGsQSc.png)

Paste the code in this box and click on **Confirm**. You should now be forwarded to the next stage in the installer.

**Verification is done!**

*NB:* The verification code will automatically be deleted by DSJAS after install completes. There's no need to delete it. If the token is still there, chances are we still need it for something.

## Common issues

### I can't find the token/the token is missing

If you can't find the token, you can use the file explorer paths again. If you take the path given by the installer and append */setuptoken.txt* to the end, you should open the token in the default editor.

However, if you're sure that the code is missing, something bad could've happened with DSJAS internally. Before doing anything else, make sure that you have clicked *Regenerate token* on the first stage of the installer.

If you still get nothing, make sure that the directory DSJAS is installed into is writable. In other words, make sure that your account (and the account PHP is running as, if applicable) has permission to write to the directory DSJAS is installed into.

If you still have issues, please contact us using GitHub issues to discuss the problem further.

### The installer says the token is invalid/the token was wrong!

Make sure that you aren't putting any spaces after or before the token. In addition, make sure that the token hasn't changed since the last time you copied it. DSJAS will regenerate the token from time to time to prevent attempts at guessing it.

If you're absolutely sure that you can't verify, even with a correct token, you can bypass the step by setting the value of *ownerVerified* in the *config.ini* script to 1. This bypasses the step and provides you access.

### DSJAS says the token was deleted and I can't continue!

Don't delete the token file after you copy it. We still need access to it to verify you! If you already deleted it, just make a file called *setuptoken.txt* and paste the code into it. Then, try again.

### I clicked continue before copying the code!

**Don't panic!** The token is actually still there until verification completes. You can just copy it again and continue with the process. The instructions state the following:

![Retrieval instructions](https://i.imgur.com/dkqehzq.png)

---

[< Previous](https://github.com/DSJAS/DSJAS/blob/master/docs/install/Getting%20started.md)  |  [Next >](https://github.com/DSJAS/DSJAS/blob/master/docs/install/Database%20setup.md)
