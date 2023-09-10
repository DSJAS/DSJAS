# Performing a manual update - *user manual*

At regular intervals throughout development, DSJAS will have updates rolled out to users. When an update occurs, DSJAS will detect it and inform administrators on the site that a new version is available. When this happens, it is very important to upgrade your version as soon as possible - allowing you to get the latest features and important security improvements.

Most of the time, this can be performed automatically by DSJAS, which will obtain the update archive and extract it into the live server directory, thereby upgrading all files which reside in there. However, should this process fail for whatever reason, your site may be left in an intermediate or unusable state and will require intervention to upgrade. Additionally, some breaking changes may require additional actions, such as resetting the database or configuration files, which must be performed manually.

Should any of this occur, your best bet is to back up your data and perform a manual update.

## Upgrading the site

Upgrading the site is only supported to be done automatically by the auto update system. Simply click "Update Now" on the update screen and follow any on screen instructions. If the update succeeds, proceed to the following steps.

If you are recovering from a partial upgrade, manually download the archive and extract the files into the DSJAS install directory. This will reset your site configuration but will recover a broken site from a partial upgrade. Should you wish to preserve your configuration files, simply copy the files you wish to preserve out of the DSJAS install folder before extracting the archive.

## Upgrading your database

DSJAS will require that you reset your database such that no tables exist under the install database before resetting from an upgrade. This means that, without manual intervention, all data will be lost. This **will never** happen automatically and data is never erased by default. However, failure to upgrade the database of your site after an upgrade may result in malfunctions and loss of operation in hard to debug scenarios.

To backup your database, use a tool such as PHPMyAdmin to backup the data from existing tables and save this as an SQL file on your computer. Then, drop all tables in the database. Save the SQL file from the first step for later reference. Do not yet do anything with this file.

## Upgrading your configuration

It is unlikely that any configuration need be reset other than the configuration in the root. Simply delete this file and copy the default configuration from [here](https://github.com/DSJAS/DSJAS/blob/master/scripts/install/Config.ini). Any of the other configuration files in that directory on GitHub can also be used to reset the configs to defaults.

When re-navigating to your site through a browser, it will have been reset to the first stage of installation. Simply follow through the installer and your upgrade will be complete.

## Restoring your database

Execute the SQL backup scripts you saved in the database upgrade stage for each table you backed up. This should restore any data you had in the tables before the upgrade to the database.

If you encounter any issues doing this, the schema may have changed to the point where your data is no longer valid in the database. This is rare but, unfortunately, could occur occasionally. This is most likely to occur across major releases, but by no means always.
