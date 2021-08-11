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

if (isLoggedIn()) {
    die("{\"error\": \"Already authenticated\"}");
} else if (!isset($_POST["username"]) || !isset($_POST["password"])) {
    die("{\"error\": \"Missing headers\"}");
}

$username = $_POST["username"];
$password = $_POST["password"];

printf("{\"attempt\": ");
if (verifyPassword($db, $password, $username, false)) {
    printf("true}");
} else {
    printf("false}");
}
