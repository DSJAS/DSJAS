# DSJAS Developer Containers - *developer documentation*

> **Running in production?** DSJAS devcontainers are not designed to be as secure as the regular docker distribution. Several production security flaws are present for the sake of convenience (such as the VSCode server running as root and the server directory being writable by all users). If you are planning to use in production, please use the regular docker container distribution.

DSJAS contains support for [Visual Studio Code DevContainers](https://code.visualstudio.com/docs/remote/containers) - which make it very easy to develop DSJAS themes, modules or even changes to the core without worrying about perquisites.

DSJAS requires the Apache Webserver with PHP installed just to get started with a *basic* working site. To do any more, you need a database server too! This is annoying to setup and can be considered intrusive to a person's system setup (Apache makes changes to your system and creates a public server).

So, DSJAS solves this by containing support for a great VSCode feature called DevContainers. DevContainers (officially called Developer Containers) are a way of building a docker container from VSCode and developing with tools and VSCode features pre-configured. We automatically build and download required tools, install recommended extensions and setup a server to test on. This server will be mapped to your host and can be accessed by navigating to *localhost* in your browser.

## Getting started

> **Important** You *will* still need to install the program when using a developer container. The developer container is *not* the same container used to distribute the program

To get started with these containers, download Visual Studio Code from <https://code.visualstudio.com> and install the [Remote Containers Extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers).

Clone the DSJAS repository. From the *master* branch, press **Ctrl-Shift-P** and type **Reopen in container**. Press enter or select the first option that appears in the command palette. VSCode will build the container and reload itself inside the container.

When VSCode has completed loading the container, you will be able to see and edit the DSJAS source files. At this point, the server should have started also. You can navigate to DSJAS in a browser at <http://localhost>.

## Differences to regular docker

The developer container is different to the regular docker distribution in a few ways. The shipped database container is exactly the same (in fact, the same Dockerfile script is used to build it). This means that if you have already built the database, Docker will not build it again. However, the server is completely different.

The regular docker distribution has to be self-sufficient (doesn't rely on the external source tree to run). However, the developer container is the exact opposite situation. The regular container statically copies the source tree into the container's filesystem. However, the developer container mounts the source tree as a volume. This allows the code to be edited inside or outside of the container and have the changes reflected both inside and outside.

Another difference is the lifetime system. The regular docker image will exit if the Apache server exits for any reason. This allows you to use systems which detect the server exiting and recreate it. In addition, this allows us to tell when a fatal error has ocurred with the server configuration. The developer container, however, will have its lifetime tied to the VSCode server's lifetime (rather than Apache). This means that the container can stay up and continue to be edited if the server crashes for whatever reason.

Finally, the developer container will contain the entire source tree - whereas the regular container *only* contains the public source (the contents of the public folder).
