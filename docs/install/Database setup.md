# Database setup

So, you've now verified your ownership of the server DSJAS is running on. Now, you have full permissions to make changes and configure the site.

Before we can go any further, DSJAS will need a database to write to.

## Setting up a database server

With the recommended packages mentioned in *Getting started*, a compatible SQL server is bundled automatically. With other server distributions, you will need to install an SQL server, start it and install the SQL extension for PHP (if it isn't there already).

Once you have started the server, you need to configure it.

### Configuring the database server

For DSJAS to run, we need a user account with the *Full control* or *Administrator* permissions.

> **Important:** For some of the bundled packages mentioned earlier, there will already be built-in accounts to use. **However:** We recommend that you set up another user for security and compatibility. Some of these accounts have restricted or removed permissions (or are locked down for security).

The exact steps for setting up a user vary from distribution to distribution. If you are using one of the recommended packages, you can use PHPMyAdmin. This tool will allow you to manage the database from a web application. In PHPMyAdmin's home page, you will see a tab called "User accounts".

![PHP my Admin](https://i.imgur.com/oVxA9xa.png)

Click on it and follow the steps for creating a new account. Note the password and username; you will need it later.

### Setting up a database

> **Do not create any tables in the new database:** DSJAS will automatically create all the required tables for you. *The database should start empty, or errors may be encountered!*

The next step is to create a database. This database should be accessible from the account you just created and should have a recognizable name.

In PHPMyAdmin, you can create a database using the create tool at the top of the databases panel.

![PHP my Admin - create database](https://i.imgur.com/p538Ajx.png)

Please note the name - as you will (once again) need it later.

## Setting up DSJAS

> **WARNING:** If you *do not* have file system access to the server at this point, you **must** test your configuration before continuing. Recovery actions from broken database configuration require filesystem access.

We left off on our magical installation journey when we reached this screen

![Database setup screen](https://i.imgur.com/2p5XMXN.png)

In here, you can see that DSJAS is asking for the information that we have just gathered. Below are details on what you should put where:

1. **Server hostname:** The hostname of the database server. If have the database on the same machine as the webserver, you should enter either *"localhost"* or *"127.0.0.1"*. If you've hosted your database separately, you need to enter either an IP or hostname of the server.

1. **Database name:** The name of the database that you just set up in the "Setting up a database" section

1. **Username:** The name of the user you set up in the "Configuring the database server" section

1. **Password:** The password of the user which you set up in the "Configuring the database server" section

### The other options

#### Manual setup

Due to the fact that DSJAS performs certain setup actions on the behalf of the user when installing the program. Some users may have a special use case for the DSJAS program. So, the option exists to continue without configuring the database.

> **Important:** It's important to note that this option differs from the "Run without a database" option due to the fact that, as far as DSJAS is aware, a database is present and functional. This means that you are responsible for setting up the database yourself and DSJAS will not attempt to account for errors

#### Run without a database

This option will force DSJAS to attempt to run without a database server functioning. This will result in user and bank account, transaction and some admin dashboard functionality being disabled.

This option is useful for when you wish to get together a quick demonstration or test run without rich functionality. Themes, modules, extensions and custom branding (such as name, URL and icons) will still work.

However, you will be unable to sign in to the administrator dashboard to reverse the setting. You will need to access the server file system to edit configuration files on disk. Therefore, if your server is hosted remotely and you don't have file system access **the action is essentially irreversible**.

### Verifying your settings

> **Tip:** We recommend that you always run the verification tool before moving on. Recovering from errors at this stage is somewhat difficult

As a convenience, the installer provides a tool which allows you to test your configuration before submitting it. This allows for you to be able to make sure that what you have entered is correct before moving on. You can do this by clicking on the "Test configuration" button. The credentials supplied will then be sent to the server for verification.

Depending on the outcome, a different response will be given

| Outcome | Meaning | Actions |
| ------- | ------- | ------- |
| ![Success](https://i.imgur.com/6H9lQzP.png) | **Success** | You should be ready. Click on "Confirm" and you will be done with the database. |
| ![Failure](https://i.imgur.com/8ZiHu5x.png) | **Failure** | Either your account credentials were incorrect, the server was not responding or the database was configured/created incorrectly. Check your password and username are both correct and that the account permission levels are adequate. In addition, you might want to check that the server is responding using a tool such as PHPMyAdmin |

## You're done!

At this point, the most complicated part of the install is complete. Well done! If your sanity is still intact, there is one more stage. We promise this one doesn't include lots of technical details or tasks!

## Common issues

### I can't get the database to work no matter what I try

Please make sure that you turned the database on in whatever control panel/system you are using.

If you still can't get the database to work, reset the password for the user account in the control panel.

As a last resort, the developer console will contain information about the technical details of what went wrong on the server. If you absolutely can't fix the issue yourself, you can reach out to us on GitHub with the info in the console. Common messages are displayed below

| Error message (along the lines of) | Meaning |
| ---------------------------------- | ------- |
| Could not connect: Unknown server host | The server hostname is incorrect |
| Error while selecting database: Unknown database | The database name is incorrect |
| Error while selecting database: Access denied for user | The user account you made doesn't have the required permissions |
| Could not connect: Access denied for user | The account password is incorrect |
| Nothing displays after "Testing configuration" | You have lost connection to the web server or the web server is not responding. Refresh the page |
| Nothing displays at all | Have you clicked the "Test configuration" button? If so, there may be an internal issue. Please contact us on GitHub |
| Something else | A miscellaneous error message may suggest an issue with either DSJAS or the database server. Please contact us on GitHub |

### If clicked on confirm and got an error message!

A few things could have happened

1. **The configuration is wrong:** Before moving on, we automatically verify the configuration. If it is wrong, we can't go on to the next step
1. **DSJAS couldn't write the configuration:** Please make sure that DSJAS is able to write to the directory it is installed in
1. **DSJAS was unable to connect to the server to perform additional actions:** This could be related to the first reason, but could also be due to a connection outage on the server-side. Please check the connection on the server and try again

The exact error message will most likely give instructions on what to do. If the message states that the database isn't working, you will need to perform a recovery. Please follow the instructions in the advanced section of this guide for more info on recoveries.

### Clicking on submit doesn't do anything!

Don't worry, there is something happening. DSJAS needs to contact the server a few times to perform this section in the install. Sadly, some servers and internet connections are faster than others. Nothing will seem to be happening for the time where DSJAS is contacting the server. Just sit tight and we'll handle it for you.
