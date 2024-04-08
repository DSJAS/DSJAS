# DSJAS - *Dave Smith Johnson & Son*

[![Downloads](https://img.shields.io/github/v/tag/dsjas/dsjas)](https://github.com/dsjas/dsjas/releases) [![Donations](https://img.shields.io/liberapay/patrons/ejv2.svg?logo=liberapay)](https://liberapay.com/ejv2/donate) [![Sponsors](https://img.shields.io/github/sponsors/ejv2?logo=github)](https://github.com/sponsors/ejv2)

![DSJAS logo](https://dsjas.github.io/assets/scammer-logo-sm.jpg)

**DSJAS: A fully featured bait bank with all the customization you could ask for**

DSJAS is a PHP application designed to mimic a real banking site on your computer in order to waste the time of criminals attempting to infiltrate your real banking site. DSJAS is designed to be a fully featured banking experience, indistinguishable from a real bank, which can change identities at any time - with the name and URL being changeable with the click of a button and the look and feel of the site able to be changed by themes. Notable features include:

* Full support for common banking activities such as transfers, managing transaction history, accounts and disputing transactions
* Theme framework for changing how the site looks and disguising the use of DSJAS
* Name switching capabilities, which allow you to change the name of the site with a click
* Admin dashboard which allows you to manipulate the bank, changing things like the details of transactions, users, disputes and support requests easily with a nice web interface
* Built-in themes which emulate popular existing solutions, such as Felicity Bank
* Extension support - for adding additional features via extensions by other users like you

And, **it's free** with no charge for downloads or any additional content. It's also free as in freedom, being licensed under the very permissive MIT licence. We welcome and encourage contributions and forks, and we are always grateful for all the support from the community.

If you wish to financially support the project, please feel free to make a donation to the developers via their GitHub donation links (or via <https://paypal.me/ejv2m>). Of course, direct support via code contributions are equally appreciated and we thank everybody for their kind support.

**Happy scambaiting!**

## Requirements

> If you don't have experience with web technology and don't want to configure your own webserver and database, an all-in-one in package such as XAMP or MAMP will do this for you. Both of these packages are free for download.

To run DSJAS, you will need **either**:

A working install of the Docker Engine

**Or the following:**

* A working install of the Apache webserver
* The PHP Apache module (usually bundled by default)
* A working install of an SQL database server

## Installation

To install the program, please refer to either the **docker** or **install** folder in the docs for more details.

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
```git clone --recursive https://github.com/DSJAS/DSJAS.git```

This will create a folder called "DSJAS" on your local machine and place the required files inside of it.

After you have done this, you will notice that the site still doesn't load and throws an error while attempting to start. This is normal and is due to the fact that you have not configured the site yet. In order to prepare for the web installer, you should navigate to the *scripts* directory and run ```Install.py```.

After you have done this and followed any instructions the script gives you, you should be ready for the web installer. Configure your webserver so that the *public* directory is the server root (**not** the root of the repository) and navigate to ```localhost``` in your browser. You should see the installer welcome page.

You're set up and ready to go!
