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

// First time including the main site bootstrap file!
require "../../include/Bootstrap.php";

?>

<body class="container-fluid">

    <div class="jumbotron-fluid text-center">

        <img class="rounded-circle" src="/assets/scammer-logo.jpg" width=300 height=300>

        <h1 class="display-4">Success! You're all set up and ready to go</h1>
        <p class="lead">D.S Johnson & Son has been fully set up and should be ready to go. Thanks for installing!</p>
        <hr class="my-4 bg-dark">
        <a class="btn btn-primary" href="/">Go to homepage</a>
        <a class="btn btn-warning" href="/admin/user/SignIn.php?post_install">Admin dashboard</a>

        <hr>
        <a href="https://github.com/DSJAS/DSJAS/blob/master/docs/install/First%20steps%20in%20the%20program.md">Need help getting started?</a>

    </div>

</body>