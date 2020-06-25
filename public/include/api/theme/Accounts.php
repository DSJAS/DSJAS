<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

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

function shouldAppearLoggedIn()
{
    return isLoggedIn();
}

function getDisplayName()
{
    return $_SESSION[LOGIN_USERNAME_STR];
}
