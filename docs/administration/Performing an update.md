# Performing an update - *user manual*

At regular intervals throughout development, DSJAS will have updates rolled out to users. When an update occurs, DSJAS will detect it and inform administrators on the site that a new version is available. When this happens, it is very important to upgrade your version as soon as possible - allowing you to get the latest features and important security improvements.

However, DSJAS does not have the capability to automatically update. To perform an update, you will need to download and install an update package manually.

## Obtaining an update package

To download an update package for your release band and required version, click the *Download* button in the admin panel's update section. This will take you to the usual download page for DSJAS releases. Download the usual DSJAS package (zip file) from the required band. You can change your update band and opt out of pre-releases by downloading a stable release package. This will automatically opt your DSJAS install out of future pre-releases.

## Backup your configuration

When installing the update package, your configuration will be overwritten and reset to default. Unless an update package specifically states that you **must** re-install the program, you should backup your configuration before continuing to install.

You can do this by copying the following files to a separate, temporary location on your filesystem:

* **In the root of the install:** Config.ini
* **In the folder *admin/site/UI*:** config.ini
* **In the folder *admin/site/modules*:** config.ini
* **In the folder *admin/site/extensions*:** config.ini

You may also wish to backup the contents of the admin data directory (which contains non-essential admin data, such as notifications). This is located in the folder *admin/site/data*.

Please note that it is not necessary to back up your database or custom themes/modules/extensions; the update should not affect either of these in any way.

## Installation

Installing the update package should be relatively simple. After the zip file has completed download, you should extract the contained files to a temporary directory. The contained files will be all you need to update.

After these files have extracted, copy them to the existing DSJAS install directory, ensuring that whatever software you are using to copy them is overwriting the existing files.

**Do not yet navigate to DSJAS in a browser!** Your site will now be broken until you restore the configuration you backed up in the previous step.

## Restore configuration

Now, you can copy back the configuration files you backed up. This will restore all your personal settings. The files should be copied back to the locations you took them from. They **will overwrite something already there** - this is normal and is just overwriting the default settings shipped with the program.

After you have done this, you can navigate to the site in your browser and everything should work fine. In most cases, your login state will be preserved too, meaning you will not have to sign in again.

To verify that everything is working as expected and that there are no more updates to install, it is a good idea to check for updates using the update menu. It will inform you if there are any more updates required to install (sometimes this will happen and you will need to install multiple).
