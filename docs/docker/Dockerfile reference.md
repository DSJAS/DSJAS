# DSJAS dockerfile reference - *user manual*

This is a reference for the actions taken by the DSJAS dockerfile when images are being built.

## Server dockerfile

1. First of all, we pull the latest supported PHP image from docker hub. This happens to be 7.2 with bundled apache at the time of writing.
1. Next, we copy the source tree. The entire public distribution source tree is copied. Essentially, the contents of the *public* directory
1. After that, we need to update the container using *apt*. In the next few steps, we need to build PHP modules, which requires the latest versions of tools such as *curl* and *file*, not to mention the build tools such as *automake*.
1. As mentioned previously, this step is dedicated to compiling and installing three required PHP extensions: *mysqli*, *curl* and *fileinfo*. If not already present, these are downloaded and compiled against the PHP source code.
1. This step is quite large: the server configuration. We found that the most reliable way to configure many Apache and PHP variables at once is to overwrite the config files entirely (or add them if they weren't there in the first place). The more important things that we configure are Apache's *httpd.conf* and the virtual host config files. We also configure Apache to load required modules, for example, *mod_rewrite* for permalink support. We also change the configuration for index files to support uppercase entries. Finally, we create (or overwrite) the PHP configuration with a modified version of the stock production defaults
1. Next, it's time to configure DSJAS itself. This step is important for a few reasons:
    1. If the source was configured when we built the container (which is nearly always is), the configuration is pretty much guaranteed to contain sensitive information (such as database passwords). This needs to be erased and made generic
    1. We configure docker containers to work with the packaged database container. This means that we need to supply the configured database credentials for the user.
    1. We need to ensure that any modifications the packager mas made are reversed. For example, all builtins need to be disabled and the name of the site should be the default
1. We're almost done. We now need to make sure that the server directory is writable. If not, changing settings and installing themes/modules will fail. To achieve this, we change the owner of the server directory from *root* to the account Apache (and therefore PHP) is running as.
1. Finally, we expose the HTTP port and exit

## Database dockerfile

This setup is much simpler.

1. We pull the latest available MySQL image from docker hub
1. Next, we copy the setup script into the auto-runs directory. The setup script for MySQL will run this script with a temporary connection to the database server on first start. This allows us to install the DSJAS database schema and default data without running the install script. This script is just a data/schema dump from PHPMyAdmin of the DSJAS database after installation. It's been slightly modified to add default bank accounts for the *edna* account and also installs to the database we setup with environment variables (rather than one called the generic *dajas_db*).
1. At this point, the last instruction in the dockerfile is to expose port 80, but some more things happen after that...
1. The MySQL scripts will automatically create accounts based on the environment variables set in the *docker-compose.yml* file. You can see that these instruct MySQL to create a randomized root password (DSJAS should never be authenticated as root) and to create the DSJAS account. We also override the install command to tell MySQL to use the default authentication plugin (just in case).
1. We're done! MySQL's setup script will handle the rest and we can just connect to the server's hostname with the DSJAS user.
