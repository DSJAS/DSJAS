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

require "../AdminBootstrap.php";

require ABSPATH . INC . "Users.php";
require ABSPATH . INC . "Administration.php";
require ABSPATH . INC . "Banking.php";
require ABSPATH . INC . "csrf.php";

if (!isset($_GET["id"])) {
    header("Location: /admin/bank/transactions.php");
}

$csrf = getCSRFSubmission("GET");
if (!verifyCSRFToken($csrf)) {
    die(getCSRFFailedError());
}

reverseTransaction($_GET["id"]);

header("Location: /admin/bank/transactions.php?transactionReversed");
