# Getting started

> **Important:** Before you do anything with the web installer, you will need to do the steps in the "Before you begin" guide.
> **For developers:** If you are a developer running a development build, you will need to follow certain steps to pre-configure the package for the web installer and build configuration files.

## Starting your webserver

In order for the web installer to run, you need to make sure that your webserver is running.

### Bundled packages

If you are running one of the recommended server packages, you can start all of your servers from the control panel. For example, in MAMP, you can press the start button on the home screen.

![MAMP server start](https://i.imgur.com/KBfuKzE.png "Starting servers in MAMP")

On packages which allow you to start all your servers separately (such as XAMP), please ensure that you have started **both** your webserver and MySQL server. DSJAS will need your computer to have a database and webserver, even in the installer.

### Starting vanilla servers

On most platforms, the Apache webserver will run as a service (or daemon). In order to start the servers, please use your operating system's interface to start the servers.

> The Apache webserver service is usually called either *httpd* or *httpd2*

## Starting the web installer

In order to start the web installer, visit any page on the server in a browser. For example, on the server, visit [localhost](http://localhost).

If you have succeeded in installing the previous steps, you should be redirected to *[domain-name]*/admin/install/install.php. Your screen should look like the following:

![First step install](https://i.imgur.com/g3jHck2.png "Example web installer entry point")

---

Congratulations! You are now ready for the web installer.

---

[< Previous](https://github.com/DSJAS/DSJAS/blob/master/docs/install/Before%20you%20begin.md)  |  [Next >](https://github.com/DSJAS/DSJAS/blob/master/docs/install/Verifying%20ownership.md)
