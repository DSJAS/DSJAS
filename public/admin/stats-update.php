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

header("Content-Type: application/json"); // This is an API; we deal with JSON here

define("NOLOAD_BOOTSTRAP_HEAD", 1); // We don't want a head element - don't load it
require "AdminBootstrap.php";

require ABSPATH . INC . "Stats.php";

$stats = new Statistics();

if ($stats->getCurrentStatsState() != STATSTATE_ACTIVE) {
    die("{\"error\": \"This API can only be called in the active state\"}");
}

if (isset($_GET["hits"])) {
    $totalHits = $stats->getStatistic("total_page_hits");
    echo("{
            \"totalHits\": $totalHits
}");
    die();
}else{
    die("{\"error\": \"Required headers absent or invalid\"}");
}
