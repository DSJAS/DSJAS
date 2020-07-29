<?php

/**
 * This file is part of DSJAS
 * Written and maintained by the DSJAS project.
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

/*
    THEMING API
    ===========

    This file contains the functions and APIs required to write a theme
    for DSJAS.

    It does nothing on its own, but does provide useful utility functions
    for theming scripts and provides a way for a theme to be consistent
    in behaviour to the rest of the site.

    For more information on the theming API, please refer to the API
    documentation.

*/

require_once ABSPATH . INC . "Users.php";


define("LOGOUT_API_SUCCESS", -1);
define("LOGOUT_API_FAILURE", -2);


function shouldProvideLoginFeedback()
{
    return isset($_GET["error"]);
}

function getLoginErrorTitle()
{
    $code = $_GET["error"];

    switch ($code) {
    case -1:
        return "Invalid username";
    case -2:
        return "Invalid password";
    default:
        return "Login failure";
    }
}

function getLoginErrorMsg()
{
    $code = $_GET["error"];

    switch ($code) {
    case -1:
        return "The entered username was not found";
    case -2:
        return "The password entered was not correct";
    default:
        return "There was an unknown error while attempting to sign you in. Error code: " . $code;
    }
}

function shouldProvideLogoutFeedback()
{
    return isset($_GET["success"]) || isset($_GET["signout_fail"]);
}

function getLogoutFeedback()
{
    if (isset($_GET["signout_fail"]) || (isset($_GET["success"]) && !$_GET["success"]))
        return LOGOUT_API_FAILURE;

    return LOGOUT_API_SUCCESS;
}

function shouldAppearLoggedIn()
{
    return isLoggedIn();
}

function getDisplayName()
{
    return $_SESSION[LOGIN_USERNAME_STR];
}
