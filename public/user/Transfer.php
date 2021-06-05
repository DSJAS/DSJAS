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

require "../include/Bootstrap.php";

require ABSPATH . INC . "DSJAS.php";

require ABSPATH . INC . "Banking.php";
require ABSPATH . INC . "csrf.php";

require_once ABSPATH . INC . "Stats.php";

require_once ABSPATH . INC . "Customization.php";
require_once ABSPATH . INC . "Users.php";
require_once ABSPATH . INC . "Util.php";

require_once ABSPATH . INC . "Theme.php";
require_once ABSPATH . INC . "Module.php";


$stats = new Statistics();

if (isset($_GET["performTransfer"])) {
    $csrf = verifyCSRFToken(getCSRFSubmission($method = "get"));
    if (!$csrf) {
        die(getCSRFFailedError());
    }

    if (!isset($_GET["amount"]) || !isset($_GET["originAccount"]) || !isset($_GET["destinationAccount"])) {
        header("Location: /user/Transfer.php?transferError=1");
        die();
    }

    if ($_GET["amount"] == null || $_GET["originAccount"] == null || $_GET["destinationAccount"] == null) {
        header("Location: /user/Transfer.php?transferError=1");
        die();
    }

    if (!userOwnsAccount($_GET["originAccount"], getCurrentUserId())) {
        header("Location: /user/Transfer.php?transferError=1");
        die();
    }

    if ($_GET["amount"] < 0) {
        header("Location: /user/Transfer.php?transferError=1");
        die();
    }

    if (isset($_GET["description"]) && $_GET["description"] != null) {
        performTransaction($_GET["originAccount"], $_GET["destinationAccount"], $_GET["amount"], $_GET["description"]);
    } else {
        performTransaction($_GET["originAccount"], $_GET["destinationAccount"], $_GET["amount"]);
    }

    $lastGreatest = $stats->getStatistic("highest_transfer");
    $currentTotal = $stats->getStatistic("total_transferred");

    $nowTotal = $currentTotal + $_GET["amount"];
    $stats->setNumberStat("total_transferred", $nowTotal);

    $stats->incrementCounterStat("total_transactions");

    if ((int)$_GET["amount"] > $lastGreatest) {
        $stats->setNumberStat("highest_transfer", (int)$_GET["amount"]);
    }

    header("Location: /user/Transfer.php?transferSuccess=1&originAccount=" . $_GET["originAccount"] . "&amount=" . $_GET["amount"]);
    die();
} else {
    regenerateCSRF();
}


if (!isLoggedIn()) {
    redirect("/user/Login.php");
    die();
}


regenerateCSRF();


// Jump to main DSJAS load code
dsjas(
    __FILE__,
    "user/",
    function (string $callbackName, ModuleManager $moduleManager) {
        $moduleManager->getAllByCallback($callbackName);
    },
    "all",
    ["user"]
);
