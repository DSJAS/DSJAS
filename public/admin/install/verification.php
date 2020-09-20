<?php

/* Bootstrapper definitions */
define("STEP_NAME", "DSJAS install verification");
define("STEP_URL", "/admin/install/verification.php");

require "install_bootstrap.php";

if (verificationCodeSent()) {
    handleVerificationCode(ABSPATH . "/setuptoken.txt");
    die();
}

if (isset($_GET["failure"])) { ?>
    <div class="alert alert-danger" role="alert">
        <p><strong>Verification failure</strong> The code entered and the recorded code on the server do not match. Please verify that you have entered the code correctly and re-copy it if necessary.</p>
    </div>
<?php }

?>

<body class="container-fluid">

    <div class="jumbotron-fluid text-center">

        <img class="rounded-circle" src="/assets/scammer-logo.jpg" width=300 height=300>

        <h1 class="display-4">Owner verification</h1>
        <p class="lead">Please use the form below to verify your ownership of the current running site</p>
        <hr class="my-4 bg-dark">
        <p>You should have copied or recorded a verification token from the server root. Please enter the code into the form below to prove your ownership of the server the site is running on.</p>

    </div>

    <form class="form-verification rounded border" onsubmit="/admin/install/verification.php" method="POST">
        <div class="form-ground row">
            <label for="verificationCode">Verification code:</label>
        </div>
        <div class="form-group row">
            <input required class="col form-control" id="verificationCode" name="code" placeholder="Code">
        </div>
        <div class="form-group row">
            <div class="form-button-fill">
                <button type="submit" class="btn btn-primary form-button-fill">Confirm</button>
            </div>
        </div>
    </form>

    <p class="lead small text-center">If you have lost or forgotten your code, you can still access it by opening the file you obtained it from in the previous step</p>
</body>