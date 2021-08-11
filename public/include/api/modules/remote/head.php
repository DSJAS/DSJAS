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

header("Content-Type: application/json"); // Set correct API encoding

define("NOLOAD_BOOTSTRAP_HEAD", 1); // Remove styling contamination
require $_SERVER["DOCUMENT_ROOT"] . "/include/Bootstrap.php";

require_once ABSPATH . INC . "DB.php";
require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Banking.php";
require_once ABSPATH . INC . "Stats.php";

$conf = new Configuration();

$dbhost = $conf->getKey(ID_GLOBAL_CONFIG, "database", "server_hostname");
$dbname = $conf->getKey(ID_GLOBAL_CONFIG, "database", "database_name");
$dbuser = $conf->getKey(ID_GLOBAL_CONFIG, "database", "username");
$dbpass = $conf->getKey(ID_GLOBAL_CONFIG, "database", "password");

$db = new DB($dbhost, $dbname, $dbuser, $dbpass);
if (!$db->validateConnection()) {
    die("{\"error\": \"Error connecting to database\"}");
}

$stats = new Statistics($conf, $db);
