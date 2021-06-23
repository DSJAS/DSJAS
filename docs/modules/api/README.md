# Module API Documentation - *DSJAS Developer Handbook*

This folder contains the documentation for the DSJAS module API and its associated usage guidance.

When looking for assistance, open the API section which contains the API you are looking for documentation in. Then, search for the function name of which you wish to have.

## Usage of the API

DSJAS modules are provided with access to a client-side JavaScript interpreter. However, as a result of the code running client side, there is *literally* no way for the module to access anything that DSJAS does not hand to it. This means that you *will need* the DSJAS API in order to access the full feature set of modules.

## API Sections

The DSJAS module API is divided into two sections:

- Remote
- Local

The remote API is provided through endpoints on the server. Your module does not need to interact with these endpoints directly, but will have all interactions handled for you by the client API. The local API provides JavaScript ES6 functions which can be called to obtain information about the DSJAS instance, use utilities and get useful tools to make module programming much easier.

You will be interacting directly *only* with the local API, so that is what is documented here.

## Getting started

Unlike the theme API, the module API is implicitly imported by DSJAS: you do not need to import the API JavaScript modules. You can directly get started by calling the functions which are a property of the ``dsjas`` object.
