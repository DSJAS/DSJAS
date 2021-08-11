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
 *
 */

require "head.php";

if (!isLoggedIn()) {
    die("{\"error\": \"Authentication failure\"}");
}

$name = getCurrentUsername();
$accounts = getAllAccountsForUser(getCurrentUserId());


printf("{\"username\": \"%s\", \"accounts\": [", $name);

$counter = 0;
foreach ($accounts as $account) {
    printf(
        "{\"number\": %d, \"name\": \"%s\", \"type\": \"%s\", \"balance\": \"%s\"}",
        $account["account_number"],
        $account["account_name"],
        $account["account_type"],
        $account["account_balance"]
    );

    if ($counter++ < count($accounts) - 1) {
        printf(",");
    }
}

printf("]}");
