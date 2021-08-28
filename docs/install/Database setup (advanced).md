# Database setup - advanced

The database setup screen in the DSJAS installer has a few more options that are worth mentioning, as well as some options that you may need if something goes seriously wrong.

## Manual database setup

Manual database setup is for users who are in either of these two situations:

1. Your database is already set up, does not need re-installing and is already functional, but you need to re-install the site anyhow
2. Your database requires some kind of special setup proceedure which is not supported by DSJAS. Some flavours of SQL may require this

In this case, you will need to perform a manual setup. To do this, navigate to the [DSJAS database script](https://github.com/DSJAS/DSJAS/blob/master/docker/database/dsjas_db.sql). This script contains SQL instructions and code which automatically install the database, and some default credentials.

Using whatever SQL client you are using (PHPMyAdmin, the SQL CLI), you will need to execute this script on the server somehow. In PHPMyAdmin, you can do this using the SQL query tab, which is labelled using a picture of a script. You can copy and paste the contents into the query box and run it. The script assumes a database called "DSJAS".

Failing this, the database schema is laid out extensively in [the install script](https://github.com/DSJAS/DSJAS/blob/master/public/include/install/Database.php#L5). You can use the information in here to manually create the database using whatever interface you prefer.

It is worth mentioning that this option is **for advanced users only**. There is no use of this option for regular users. You will not get improved performance or anything like that. This is simply here to provide for fringe use cases. Do not panic - installation does not have to be this difficult.

## Performing a recovery

If you have clicked on "confirm" at the database phase and recieved an error, managed to break your database during live site usage or accidentally broken your configuration, this guide is for you. The DSJAS database is vital to keep running correctly, as, without it, access to the admin panel - and therefore access to the database settings remotely - is impossible. To recover from most database errors, you will need to edit your DSJAS configuration file.

### Recovery during install

If you are still in the installation process, there will not yet be any data in your database to recover, so there is no sense in performing a full recovery. What you should do instead is navigate to the place you installed DSJAS and open the "Config.ini" script. This will contain your main site config file.

Inside, there will be a line which reads something like:

```ini
database_installed="1"
```

You should change the contained "1" to a "0". Then, refresh the installer error page and try again. You can repeat this as many times as you need, but it is better to just use the verification feature on the install page.

### Recovery on a live site

On a live site, is makes much more sense to salvage the running database rather than install a fresh, new one. To do this, again nagivate to the root of the DSJAS site and open the "Config.ini" script. Inside here, there will be some lines which look like this:

```ini
[database]
running_without_database="0"
server_hostname="localhost"
database_name="dsjas"
username="DSJAS"
password="passwordforthewebsite"
```

These are the databse configuration details. You can edit the details in quotes to the correct details.

### Recovering a broken database schema

If you were fiddling about with the database structure and configuration and have caused it to break, the only option is to reset to defaults by dropping all tables and re-installing the site.

A corrupted schema of this kind can also be caused by an update which changed the structure of the database but did not migrate it across. If you believe this to be the case, check if the update instructions/patch notes include any information about how to upgrade. If it does not, you should assume that it intends for you to update yourself and simply do what is mentioned above.

This **will** result in **all data being lost** unless you use features (such as PHPMyAdmin's backup feature) to backup the data contained in the migrated database tables.
