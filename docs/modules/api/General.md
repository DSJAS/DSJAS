# General - *DSJAS Module API Documentation*

* **API Name:** General
* **Description/use:** Used for obtaining general information about the DSJAS install
* **File Name:** General.js
* **Anchor point specific?** No
* **Exposed object:** Under ``dsjas``

## ```test()```

Logs "DSJAS API Loaded" to the developer console and returns true.

* **Parameters:** None
* **Returns:** ```boolean``` Always true

## ```getBankName()```

> **Remote interactions!** This API will reach out to the remote API for information on being called. Results are not cached.

Returns the current bank's user customised name.

* **Parameters:** None
* **Returns:** ```string``` The current bank's name

## ```getBankUrl()```

> **Remote interactions!** This API will reach out to the remote API for information on being called. Results are not cached.

Returns the current bank's user customized domain/URL. This URL should **not** be used for redirection or link generation, as there is no guarantee that it will be present and/or correct.

* **Parameters:** None
* **Returns:** ```string``` The current bank's URL

## ```getThemeName()```

> **Remote interactions!** This API will reach out to the remote API for information on being called. Results are not cached.

Returns the theme's name under which the current module was loaded. This *does not* necessarily equal the currently configured theme.

* **Parameters:** None
* **Returns:** ```string``` The current theme's name (or the name of the theme under which we were loaded).
