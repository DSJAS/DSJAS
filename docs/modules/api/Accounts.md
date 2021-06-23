# Accounts - *DSJAS Module API Documentation*

* **API Name:** Accounts
* **Description/use:** Used for querying and changing current user session information
* **File Name:** Accounts.js
* **Anchor point specific?** No
* **Exposed object:** Under ``dsjas.accounts``

## ```isLoggedIn()```

> **Remote interactions!** This API will reach out to the remote API for information on being called. Results **are** cached.

Returns true if there is currently an ongoing login session, false otherwise.

* **Parameters:** None
* **Returns:** ```boolean``` True if session ongoing, false otherwise

## ```getUsername()```

> **Warning!** Will return ```undefined``` if there is currently no ongoing session, along with a debug warning in the developer console. Check login state before calling.

Returns the current username from the ongoing login session.

* **Parameters:** None
* **Returns:** ```string``` Current user's username

## ```getBankAccounts()```

> **Remote interactions!** This API will reach out to the remote API for information on being called. Results are not cached.

Returns an array containing objects which represent the current user's accounts. For information on the contents, see the bank accounts database schema.

If there is currently no ongoing session (or the request failed), a zero length array will be returned.

* **Parameters:** None
* **Returns:** ```array``` of ```objects``` containing information on each accounts retrieved

## ```logout()```

Destroy the current **bank** login session. **Does not** destroy any sessions relating to the admin panel.

* **Parameters:** None
* **Returns:** ```undefined```
