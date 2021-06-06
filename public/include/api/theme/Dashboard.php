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

require_once ABSPATH . INC . "DB.php";
require_once ABSPATH . INC . "Users.php";
require_once ABSPATH . INC . "Banking.php";


function getAccountsArray()
{
    $userId = getCurrentUserId();

    $query = new SimpleStatement("SELECT * FROM `accounts` WHERE `associated_online_account_id` = $userId");
    $GLOBALS["THEME_GLOBALS"]["shared_db"]->unsafeQuery($query);

    return $query->result;
}

function getRecentTransactionsArray($loadAmount)
{
    $userId = getCurrentUserId();

    $accountsQuery = new SimpleStatement("SELECT * FROM `accounts` WHERE `associated_online_account_id` = $userId");
    $GLOBALS["THEME_GLOBALS"]["shared_db"]->unsafeQuery($accountsQuery);

    $whereText = "";

    $iteration = 0;
    foreach ($accountsQuery->result as $account) {
        $whereText .= "`origin_account_id` = ";
        $whereText .= $account["account_identifier"];

        $iteration++;
        if ($iteration < count($accountsQuery->result)) {
            $whereText .= " OR ";
        };
    }

    $whereText .= " OR ";

    $iteration = 0;
    foreach ($accountsQuery->result as $account) {
        $whereText .= "`dest_account_id` = ";
        $whereText .= $account["account_identifier"];

        $iteration++;
        if ($iteration < count($accountsQuery->result)) {
            $whereText .= " OR ";
        };
    }

    $query = new SimpleStatement("SELECT * FROM `transactions` WHERE $whereText ORDER BY `transaction_id` DESC LIMIT $loadAmount");
    $GLOBALS["THEME_GLOBALS"]["shared_db"]->unsafeQuery($query);

    if ($query->result === false) {
        return [];
    }

    return $query->result;
}

function censorAccountNumber($number, $stripChars = 5, $censorChar = "*", $pad = true)
{
    if ($pad && strlen($number) < $stripChars) {
        $useNumber = str_pad($number, $stripChars * 2, "0", STR_PAD_LEFT);
    } else {
        $useNumber = $number;
    }

    $array = str_split($useNumber);

    $counter = 0;
    foreach ($array as &$digit) {
        if ($counter < $stripChars) {
            $digit = $censorChar;
        }

        $counter++;
    }

    return implode("", $array);
}

function isPricePositive($priceString, $regionalCurrencySymbol = "$")
{
    if ($priceString[0] == $regionalCurrencySymbol) {
        return $priceString[1] != "-";
    } else {
        return $priceString[0] != "-";
    }
}

function getDisplayBalance($accountID)
{
    $query = new PreparedStatement("SELECT `account_balance` FROM `accounts` WHERE `account_identifier` = ?", [$accountID], "i");
    $GLOBALS["THEME_GLOBALS"]["shared_db"]->prepareQuery($query);
    $GLOBALS["THEME_GLOBALS"]["shared_db"]->query();

    $balanceValue = $query->result[0]["account_balance"];

    return "$" . $balanceValue;
}

function getDisplayAccountNumber($accountID)
{
    return censorAccountNumber(getAccountNumber($accountID));
}
