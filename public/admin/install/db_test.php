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

// Tests the database for the javascript to determine if it worked or not, kind of like an API endpoint

$servername = $_POST["servername"];
$dbname = $_POST["dbname"];
$username = $_POST["username"];
$password = $_POST["password"];


if (!isset($_POST["servername"]) || !isset($_POST["dbname"]) || !isset($_POST["username"]) || !isset($_POST["password"])) {
    die("Error: one or more required parameters were not specified");
}

$link = mysqli_connect($servername, $username, $password);
if (!$link) {
    die('Could not connect: ' . mysqli_connect_error());
}
echo 'Connected successfully! | ';


mysqli_select_db($link, $dbname);
if (mysqli_error($link) != null) {
    die("Error while selecting database: " . mysqli_error($link));
}

echo "Success, selected database!";

mysqli_close($link);
