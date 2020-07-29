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

define("CSRF_TOKEN_LEN", 32);
define("CSRF_SESSION_KEY", "csrf_token");
define("CSRF_FORM_NAME", "csrf");

require_once "Util.php";


function regenerateCSRF()
{
    $randBytes = generateRandomString(CSRF_TOKEN_LEN);

    $_SESSION[CSRF_SESSION_KEY] = $randBytes;
}

function getCSRFToken()
{
    return $_SESSION[CSRF_SESSION_KEY];
}

function verifyCSRFToken($givenToken)
{
    return hash_equals(getCSRFToken(), $givenToken);
}

function getCSRFFormElement()
{
    ?>
    <input id="csrf" type="text" style="visibility: hidden; position: absolute" name="<?php echo (CSRF_FORM_NAME); ?>" value="<?php echo (getCSRFToken()); ?>">
<?php }

function getCSRFSubmission($method = "post")
{
    if ($method == "post") {
        return $_POST[CSRF_FORM_NAME];
    } else {
        return $_GET[CSRF_FORM_NAME];
    }
}

function getCSRFFailedError()
{
    ?>
    <div class="alert alert-danger">
        <p><strong>Security error:</strong> We had some trouble making sure that the action you're trying to perform was initiated by you. If you clicked a link, you should close this page. If you were trying to perform an action on the site, please refresh the page or submit the form again. <i>The operation was cancelled</i></p>
    </div>
<?php }
