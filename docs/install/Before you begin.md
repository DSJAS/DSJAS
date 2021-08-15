# Before you begin

**Welcome to DSJAS!** If you're reading this, you're probably interested in using DSJAS to bait scammers and waste their time. Well done to you, you're doing a good service.

DSJAS is here to help with you in your efforts to do this by providing a useful tool to trick scammers. Essentially, when a scammer connects to your computer, they generally want you to log in to your online banking. They do this so that they can do some nasty things in your account. So, if you want to waste their time, you need a bank account to sign in to.

This program mimics a real banking site in order to safely sign scammers in to a bank account - but with no real money, credentials and no real bank.

In order to get started, you will need to download and install the program. This process is as simple as possible, but you do need to do a few things before you get into the install process...

## For docker users

**Stop!** You don't actually need to install the program! DSJAS docker images come working out of the box without and installation of further configuration necessary. To get your default credentials, please refer to the docker section of the documentation.

## Downloading the program

DSJAS is released from a number of locations, but the recommended download location is from GitHub releases. Whenever an update to the program is released, a new release is created there. You can find the releases page here:

[Downloads page](https://github.com/OverEngineeredCode/DSJAS/releases/latest)

> **Important!** *Do not* download from the big green button on the GitHub homescreen looks like a download button. This will download the DSJAS developer source tree, which will be missing configuration files and will break the site. *Do* use the above download page.

From this download page, you will find a download location for a file which is named along the lines of the following:

    DSJAS-stable.zip

This zip archive contains all the code and files required in order to run the program. This is all you need to get started.

## Unpacking the program

Now that you have the program, you will have a zip archive which looks like this:

![Picture of zip archive](https://i.imgur.com/ExLC4C5.png "DSJAS zip archive")

Zip archives are a compressed format. This means that it contains all of the files and folders that DSJAS uses, but squashed down to a smaller size. Before these files can be used, they must be inflated again. To do this, we need an archive manager.

On Windows, Windows Explorer can handle zip files out of the box. You can also use a program such as 7-zip. We recommend 7-zip (but Windows Explorer works just fine).

Before extracting, move the archive to the folder you wish to install the program into. This should be any place on your hard disk which you can access **without administrator permissions**. In other words, do not install in program files or in the default *htdocs* location

Opening the archive in file manager will give a screen which looks similar to this:

![7-zip FM](https://i.imgur.com/5ALyjhe.png "Picture of 7-zip file manager")

You should then extract (or extract all in Windows Explorer) the files.

![Extraction](https://i.imgur.com/4UQILPn.png "Extract method")

If done correctly, you'll now have all the files from the archive in the directory with the zip file. You should now delete the zip file (or move it elsewhere)

Congrats! You've now unpacked the program.

## Settings up your webserver

> **Tip:** Although I won't give direct instructions on how to set your webserver up, I will suggest that you use a package such as XAMP or MAMP to install all the components required automatically

In order to set the site up, you will need to configure your webserver so that the unpacked *"DSJAS-release"* directory is the root of the webserver.
This means that all requests to the site will be directed toward DSJAS scripts.

### Configuring using XAMP

If you're using XAMP, you can use the built in control panel for each of your servers to set the server root.

First, stop your webserver. You can do this by pressing the stop button under the actions panel.

Next, click on "Config" (this is usually located next to the button which reads "Admin"). This will open the configuration file in your default text editor.

You will need to modify the "DocumentRoot" setting. There are a lot of settings in this file, so we recommend using the find feature in your text editor to find the value. In most editors, you can access this find feature by pressing CTRL-F on your keyboard.

Edit the value in quotes next to document root so that it is the folder we just extracted to.

*You're done!*

### Configuring using MAMP

MAMP has the most convenient way of changing your server root location. You can simply use the settings panel and the program will handle the rest for you.

The panel you wish to use looks like this:

![MAMP settings](https://i.imgur.com/ybqoaQY.png "MAMP Server Root Settings")

### Configuring vanilla Apache

You will need to modify your configuration file using the same steps as in the XAMP section. Your apache.conf file could be located in a few locations. Here are two common ones:

* Under the *conf* directory of wherever you installed the server
* /etc/apache2/apache2.conf

### Notes on Apache virtual hosts / HTTPS

Apache has support for "Virtual Hosts", which allow a single server to host on multiple ports/IPs. If you wish to use this capability, you **must** remember to reflect the DSJAS configs (document root, overrides allowed) in **all virtual host configs**, or you will have some vHosts working perfectly and others copletely broken.

If you are planning on using Apache with SSL (HTTPS) support for your site, you **must** remember to reflect changes to your main server configuration file to the one specific to the HTTPS vHost. In XAMPP, this is located in *etc/extra/httpd-ssl.conf*. If you do not, it is likely that you will get HTTP 403 (permission denied) errors on attempting to use your HTTPS-enabled site.

## Preparing to install

Now, the program is unpacked and pretty much installed. Before you can use the program, however, you will need to run the web installer to configure the site.

The next chapter in the install documentation will describe how to run the web installer.

**Well done!** You have completed the "Before you begin" preparation.
