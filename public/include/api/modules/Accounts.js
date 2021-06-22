/*
 * DSJAS Module API - Client component
 * Client code component for DSJAS module API system
 *
 * Copyright (C) 2020 - Ethan Marshall
 *
 * DSJAS is free software which is licensed and distributed under
 * the terms of the MIT software licence.
 * Exact terms can be found in the LICENCE file.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * above mentioned licence for specific details.
 */

dsjas.accounts.isLoggedIn = function () {
    return dsjas.util.makeApiRequest("account", ["state"]);
}

dsjas.accounts.getUsername = function () {
    uname = dsjas.util.makeApiRequest("account", ["username"]);
    if (uname == "") {
        console.warn("Attempted to get username when no session is ongoing");
    }

    return uname;
}

dsjas.accounts.getBankAccounts = function () {
    if (!dsjas.accounts.isLoggedIn()) {
        warn("Attempted to get accounts when no session is ongoing");
        return [];
    }
    return dsjas.util.makeApiRequest("banking", ["accounts"]);
}