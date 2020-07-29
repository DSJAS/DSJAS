# DSJAS - *Dave Smith Johnson & Son*

![DSJAS logo](https://dsjas.github.io/assets/scammer-logo-sm.jpg)

**Welcome to DSJAS family bank! Keeping your money somewhat safe since 2005!**

DSJAS is a simple PHP tool designed for "scambaiting". The site is designed to mimic the behavior and feel of a real banking site in order to fool scammers attempting to infiltrate online banking. The site does not handle real money and is therefore ideal for tricking scammers.

The site is highly customizable, with full support for plugins, themes and other trickery. Seemingly large-scale things such as the name and branding of the site can be changed with the click of a button.

In addition to all that, it's totally free and open source. More specifically, being licensed under the MIT licence.

## Requirements

> If you don't have experience with web technology and don't want to configure your own webserver and database, an all-in-one in package such as XAMP or MAMP will do this for you. Both of these packages are free for download.

To run DSJAS, you will need **either**:

A working install of the Docker Engine

**Or the following:**

* A working install of the Apache webserver
* The PHP Apache module (usually bundled by default)
* A working install of an SQL database server

## Installation

To install the program, please refer to either the **docker** or **install** folder in the docs.

## Release bands

### Stable releases

When an official version is available, it will be pushed to GitHub releases. These releases are the tested and verified releases straight from the developers. If you are not interested in bleeding-edge features or developing a theme/extension, this is the recommended install method.

When you go to GitHub releases, a zip archive will be available to download. This contains all the files required to run the site.

### Bleeding edge/pre-releases

Bleeding edge releases refer to releases that are currently being developed and that are not complete yet. If you wish to get features early, this is the way to go. These releases are marked as pre-releases in GitHub releases.

If you experience any bugs in these versions, it is even more important to report them to the developers before the next stable release to ensure that other users do not have to experience them.

When using this version, you should expect a lot more frequent updates. If too many updates annoys you, you may wish to use the stable release channel instead. In addition to this, some themes and plugins may break when using a bleeding-edge release. This means that if stability is very important to you, bleeding-edge is not the way to go.

### Development releases

If you wish to develop content for the site (such as a theme, extension or just contribute to the core of the site), you will have to build the app yourself. Luckily, the site is powered by PHP, which does not require compilation. All you need to do is configure and install the required files using the tools provided.

To get started, clone the repository with:
```git clone https://github.com/OverEngineeredCode/DSJAS.git```

This will create a folder called "DSJAS" on your local machine and place the required files inside of it.

After you have done this, you will notice that the site still doesn't load and throws an error while attempting to start. This is normal and is due to the fact that you have not configured the site yet. In order to prepare for the web installer, you should navigate to the *scripts* directory and run ```Install.py```.

After you have done this and followed any instructions the script gives you, you should be ready for the web installer. Configure your webserver so that the *public* directory is the server root (**not** the root of the repository) and navigate to ```localhost``` in your browser. You should see the installer welcome page.

You're set up and ready to go!
