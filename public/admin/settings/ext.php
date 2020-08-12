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

require ABSPATH . INC . "csrf.php";

?>

<html>
<?php require ABSPATH . INC . "components/AdminSidebar.php"; ?>

<div class="content container-fluid" id="content">
    <div class="alert alert-warning d-lg-none">
        <p><strong>Warning:</strong> The admin dashboard is not designed for smaller screens, and some functionality may be missing or limited.</p>
    </div>

    <?php require ABSPATH . INC . "components/AdminSettingsNav.php"; ?>

    <p style="display: none" id="csrfToken"><?php echo (getCSRFToken()); ?></p>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="admin-header col col-offset-6">Extensions</h1>
    </div>

    <div class="alert alert-warning">
        <p><strong>Coming soon</strong></p>
        <p>This version of DSJAS does not yet contain extension support, but don't worry! It's coming very soon.
            Extensions are being worked on as you read this. Please check in soon and update as soon as possible
            to access great built-in and community extensions.
        </p>
    </div>
</div>

</html>