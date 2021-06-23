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

var callbacks = [];
var correctWanted = [];

/*
 * Callback to handle calling registered listeners for form submit
 *
 * TODO: This needs to ensure we call ALL functions, not just the first
 * ones until one which yields. Perhaps timeouts could work here?
 *
 * A system where each callback yields or fails at the end using a function
 * in the API could allow us to control this better. For now, this is what we
 * are stuck with.
 */
dsjas.login._login_callback = function () {
    var enteredUsr = document.querySelector("input[name=\"username\"]").value;
    var enteredPass = document.querySelector("input[name=\"password\"]").value;

    var verify = dsjas.util.makePostRequest("verify", ["username", "password"], [enteredUsr, enteredPass])["attempt"];

    var state = true;
    for (var i = 0; i < callbacks.length; i++) {
        if (correctWanted[i]) {
            if (verify) {
                state = false;
                callbacks[i]();
            }
        } else {
            state = false;
            callbacks[i]();
        }
    }

    return state;
}

dsjas.login.addCallback = function (callback, needsCorrect) {
    $("#loginForm").submit(dsjas.login._login_callback);

    callbacks.push(callback);
    correctWanted.push(needsCorrect);
}