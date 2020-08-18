# DSJAS Developer Containers - *developer documentation*

DSJAS contains support for [Visual Studio Code DevContainers](https://code.visualstudio.com/docs/remote/containers) - which make it very easy to develop DSJAS themes, modules or even changes to the core without worrying about perquisites.

DSJAS requires the Apache Webserver with PHP installed just to get started with a *basic* working site. To do any more, you need a database server too! This is annoying to setup and can be considered intrusive to a person's system setup (Apache makes changes to your system and creates a public server).

So, DSJAS solves this by containing support for a great VSCode feature called DevContainers. DevContainers (officially called Developer Containers) are a way of building a docker container from VSCode and developing with tools and VSCode features pre-configured. We automatically build and download required tools, install recommended extensions and setup a server to test on. This server will be mapped to your host and can be accessed by navigating to *localhost* in your browser.

## Getting started

> **Important** You *will* still need to install the program when using a developer container. The developer container is *not* the same container used to distribute the program

To get started with these containers, download Visual Studio Code from <https://code.visualstudio.com> and install the [Remote Containers Extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers).

Clone the DSJAS repository. From the *master* branch, press **Ctrl-Shift-P** and type **Reopen in container**. Press enter or select the first option that appears in the command palette. VSCode will build the container and reload itself inside the container.

When VSCode has completed loading the container, you will be able to see and edit the DSJAS source files. At this point, the server should have started also. You can navigate to DSJAS in a browser at <http://localhost>.
