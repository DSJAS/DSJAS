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

?>

<nav class="navbar navbar-fluid navbar-expand-lg navbar-dark bg-dark mb-4">
    <a class="navbar-brand text-light">DSJAS Settings</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#headerNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="headerNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link text-primary" href="/admin/settings/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/settings/update.php">Update</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/settings/accounts.php">Accounts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/settings/mod.php">Modules & Themes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/settings/ext.php">Extensions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-warning" href="/admin/settings/advanced.php">Advanced</a>
            </li>
        </ul>
    </div>
</nav>