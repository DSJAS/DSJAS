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

var called = 0;

/*
 * Callback to handle calling registered listeners for form submit
 *
 * EXPERIMENTAL SUPPORT for multiple callbacks being registered should
 * allow for multiple modules to change the login process at any time
 */

dsjas.login.callCallback = function (id) {
    var enteredUsr = document.querySelector("input[name=\"username\"]").value;
    var enteredPass = document.querySelector("input[name=\"password\"]").value;
    var verify = dsjas.util.makePostRequest("verify", ["username", "password"], [enteredUsr, enteredPass])["attempt"];

    if (correctWanted[id]) {
        if (verify) {
            callbacks[id]();
        } else {
            called++;
            dsjas.login.callbackYield();
        }
    } else {
        callbacks[id]();
    }
}

dsjas.login._login_callback = function () {
    dsjas.login.callCallback(called);
    called++;

    return false;
}

dsjas.login.addCallback = function (callback, needsCorrect) {
    var form = document.getElementById("loginForm");
    form.onsubmit = dsjas.login._login_callback;

    callbacks.push(callback);
    correctWanted.push(needsCorrect);
}

dsjas.login.callbackYield = function () {
    if (called == callbacks.length) {
        dsjas.login.callbackEnd();
        return;
    }

    dsjas.login.callCallback(called);
    called++;
}

dsjas.login.callbackEnd = function () {
    document.getElementById("loginForm").onsubmit = null;
    $("#loginForm").submit();
}

dsjas.login.callbackReset = function () {
    called = 0;

    var form = document.getElementById("loginForm");
    form.onsubmit = dsjas.login._login_callback;
}