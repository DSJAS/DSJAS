# Information for developers

Developer installs of DSJAS are slightly different to install to others. Rather than downloading releases of the program

## Preparing a developer install

To prepare a developer install, you will need to initialise the default configs. To do this, navigate to the "scripts" directory in the root of the source tree. Then, run the script called "Install.py". This will initialise (or reset) the default configs. After you have done this, you will need to set the document root of your webserver to the "public" directory in the source tree. **Do not** set the document root to the root of the source tree: this **is not** the proper source code for DSJAS.

## DSJAS SDK

The DSJAS SDK is available for UNIX-like systems (Linux, BSD, MacOS) and can be used to interact with DSJAS sites from the command line. It can be used to create new themes from boilerplates and change existing ones.

You can get a copy of the DSJAS SDK from the [DSJAS-CLI project homepage](https://github.com/DSJAS/DSJAS-CLI/).
