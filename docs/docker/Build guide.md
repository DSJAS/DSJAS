# DSJAS docker build guide - *user manual*

A DSJAS docker image can be build using the DSJAS source tree and the provided *docker-compose.yml* file in the root of the project.

## Step 1: Build the container

You can build a copy of the required containers and add them to the local docker index by running ```docker-compose build```.

This command will take a while, but shouldn't require any input.

## Step 2: Run the container

You can start the container in two ways:

### Method 1 (recommended): Using docker compose

In the root of the project, run ```docker-compose up```. This will start all required containers and start setup.

### Method 2: Using raw docker

You can use docker to start the required containers individually. You can use this to separate logs into two different terminal instances.

The two images build are called:

* DSJAS_server
* DSJAS_database
