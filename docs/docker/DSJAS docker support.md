# DSJAS and Docker - *user manual*

DSJAS supports installation and running inside of docker containers. These docker containers can be pulled from GitHub or can be built from the latest source right in the repository.

This is awesome, for a couple of reasons:

1. **It just works** There's no need to go fiddling around with your server's configuration. We configure everything so that it just works. Tested and verified
1. **No install** The server is installed out of the box and has sensible defaults (you will need to get your credentials from us, though). This means that instead of a long installer, you can start the app in two console commands
1. **Security** Even if the site is compromised or somebody gets the IP (or something else dubious), there's no problem, because you can just take the container down and build a new one from the factory image. In addition, the docker container is isolated, meaning that nobody exploiting the site will be able to get out of the sandbox anyway!
1. **Cross platform without effort** If your platform isn't officially supported, chances are docker does support it and you can just run the container. No more platform headaches!

## Getting started

### Building from source

For now, this is the recommended method of running DSJAS in a docker container.

To get started, navigate to the root of the DSJAS source code. In there, you will notice a *docker-compose.yml* file. This file will tell docker compose how to build DSJAS. Next, run the following command:

```bash
docker-compose build --no-cache
```

> **Tip:** If this is your first time building the project, the *--no-cache* can be omitted. We recommend including it otherwise, to prevent strange issues with build configurations

This may take a little while. Once completed, you will have two new docker images in your docker database. These images will be called:

* DSJAS_server
* DSJAS_database

The DSJAS server is a modified version of the Apache webserver. We've added custom modules and configuration to make DSJAS run smoothly, securely and "just work". In addition, we've installed the required PHP extensions and built some extras we need.

The DSJAS database is a standard MySQL install. However, the image contains scripts which will run on the first startup to install the database to match the configuration in the server image.

Once the build has complete, run:

```bash
docker-compose up
```

Alternatively, run:

```bash
docker-compose run [SERVICE NAME]
```

This will allow you to start a single service. For example, if you plan to try running without a database. From there, you should be good to go!

### Pulling from GitHub

The GitHub package registry has an entry for DSJAS docker containers. You **must** pull **both** the *DSJAS_database* and *DSJAS_server* packages. These work hand-in-hand and are configured for each other out of the box.

To run without the aid of the DSJAS source's ```docker-compose``` file, some special docker commands are needed. Please copy these into two terminal sessions:

**To start the webserver, please use the following command:**

```bash
docker run -p 80:80 docker.pkg.github.com/dsjas/dsjas/dsjas-server:<your-version>
```

**To start the database server, please use the following command:**

```bash
docker run -p 3306:3306 -e MYSQL_RANDOM_ROOT_PASSWORD=1 -e MYSQL_DATABASE=dsjas -e MYSQL_USER=DSJAS -e MYSQL_PASSWORD=DSJAS-Default-Password-1234 docker.pkg.github.com/dsjas/dsjas/dsjas-database:<your-version>
```

Replace *your-version* with the tag version you pulled. This is likely to look like *1.0.0-stable* or *1.0.0-beta*. If you don't know what tag you pulled or you didn't specify a tag while pulling, try using *latest*.

Note that the database will not work unless you put the docker containers on a mutual docker network. You can see how to do this and how this works [here](https://docs.docker.com/engine/reference/commandline/network/).

## After building/pulling

When you start the container, it's important to **wait for a few minutes**. On the initial startup, some docker scripts and setup actions need to complete. For example, on first startup, we install a snapshot of the DSJAS default database onto your server. This takes some time. So, we recommend that you wait around ninety seconds before attempting to use the site.

In the console and docker logs, output from the two servers are color-coded and organised onto newlines. This makes it easy to filter and mentally separate output.

## Stopping DSJAS

If you have your terminal attached to DSJAS, you can press CTRL-C (or CMD-C) on your keyboard to halt and shutdown the containers. This will gracefully stop the servers, ending any ongoing operations and requests as soon as possible. Once finished, the servers will be stopped.

### Cleaning up

If you don't want to keep your config or want to wipe the containers, running

```bash
docker-compose down
```

will clean out containers, yet will preserve images. This means that any changes will be lost, but you can just run ```docker-compose up``` the next time and get a factory install back again.

By default, all config changes are kept. This means that you can keep starting up the container and will still have all your users and changes to configuration.

## Common issues

Docker support is still being worked on, meaning that some issues may be encountered. However, there should be substantially less than issues caused by platform/server support because of the magic of docker.

If you have issues with using the database, make sure the you waited until the database fully initialised. Until you see a message similar yo *MySQL Daemon ready to accept connections on /var/sock/mysqld.sock*, the server is still being configured and you **must wait** and not attempt to use the database; all requests will just fail.

If you get 404 pages while using links on the site, we may have not yet migrated the site over to using new link schemes. In older versions of DSJAS, we relied on the Apache module *mod_spelling* to make URLs case insensitive. However, this is now defunct and its removal is being actively worked on. On older servers, this still works - but not in docker.

If you're unable to login to the admin panel, please see our guide on default credentials. For everything else, the default credentials are identical to a fresh install of the site outside of docker containers.
