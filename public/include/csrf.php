<?php

/**
 * Welcome to Dave-Smith Johnson & Son family bank!
 * 
 * This is a tool to assist with scam baiting, especially with scammers attempting to
 * obtain bank information or to attempt to scam you into giving money.
 * 
 * This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
 * is free to use and change for all users. Scam bait as much as you want!
 * 
 * This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
 * I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!
 * 
 * Please, waste these people's time as much as possible. It's fun and it does good for everyone.
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
