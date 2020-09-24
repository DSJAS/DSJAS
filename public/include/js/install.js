/* D.S Johnson & Son - Client Side JS */

/* Common code */
$(document).ready(
    function () {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
    }
);

/* Variables */
var ongoingConfigTest = false;

/* ==================== [INITIAL CONFIGURATION] ==================== */

/* ====================     [VERIFICATION]      ==================== */

/* ==================== [DATABASE CONFIGURATION] =================== */
function confirmAndSetup()
{
    var sname = $("#servername").val();
    var dname = $("#dbname").val();
    var uname = $("#username").val();
    var pword = $("#password").val();

    var postdata =
    "submit=1&servername=" +
    sname +
    "&dbname=" +
    dname +
    "&username=" +
    uname +
    "&password=" +
    pword;

    req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4) {
            console.log(
                "Sent required information, redirecting to next stage. If everything worked out, we should get to final setup."
            );
            location.reload();
        }
    };

    req.open("POST", "/admin/install/db_config.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(postdata);
}

function testConfiguration()
{
    if (!ongoingConfigTest) {
        console.log("Testing configuration");
        ongoingConfigTest = true;

        doTestConfig();

        ongoingConfigTest = false;
    } else {
        console.log("Test ongoing, ignoring click event");
    }
}

function doTestConfig()
{
    var sname = $("#servername").val();
    var dname = $("#dbname").val();
    var uname = $("#username").val();
    var pword = $("#password").val();

    var postdata =
    "servername=" +
    sname +
    "&dbname=" +
    dname +
    "&username=" +
    uname +
    "&password=" +
    pword;

    req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(req.responseText);

            handleServerCheckResponse(req.responseText);
        }
    };

    req.open("POST", "/admin/install/db_test.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(postdata);
}

function handleServerCheckResponse(response)
{
    if (response.indexOf("Error") != -1 
        || response.indexOf("Could not connect:") != -1
    ) {
        $("#configCheck").popover("dispose");
        $("#configCheck").attr("title", "Check completed: failure");
        $("#configCheck").attr(
            "data-content",
            "The server encountered an error while attempting to connect to the database using the data you provided. Please verify that the details are correct and that your database is available."
        );
        $("#configCheck").popover("show");

        setTimeout(
            function () {
                $("#configCheck").popover("dispose");
            }, 5000
        );
    } else {
        $("#configCheck").popover("dispose");
        $("#configCheck").attr("title", "Check completed: success!");
        $("#configCheck").attr(
            "data-content",
            "The server reported that it was successful when it attempted to connect to the database using the details you provided. This means that you should be all set up and ready to go!"
        );
        $("#configCheck").popover("show");

        setTimeout(
            function () {
                $("#configCheck").popover("dispose");
            }, 7500
        );
    }
}

/* ==================== [FINAL] =================== */
function showFinalSpinner()
{
    $("#saveProgress").removeClass("d-none");
}

function submitFinal()
{
    console.log(
        "Submitting the information to the server and awaiting a response"
    );

    showFinalSpinner();

    req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        response = req.responseText;

        if (this.readyState == 4 && this.status == 200) {
            // The status code is 200 (success)

            if (response.indexOf("ERROR: WEAKPASS") != -1) {
                document.writeln("Error detected, handling...");

                location.assign("/admin/install/final.php?error=weakpass");
            }
            else if (response.indexOf("ERROR: MISSING") != -1) {
                document.writeln("Error detected, handling...");

                location.assign("/admin/install/final.php?error=missing");
            }
            else {
                document.writeln("Redirecting...");

                location.assign("/admin/install/Success.php");
            }
        }
        else if (this.readyState == 4) {
            // The status code is not 200 (success)

            document.writeln("Unknown error occurred, handling...");

            location.assign("/admin/install/final.php?error=unknown")
        }
    };

    var username = encodeURIComponent($("#usernameInput").val())
    var email = encodeURIComponent($("#emailInput").val())
    var password = encodeURIComponent($("#passwordInput").val())
    var hint = encodeURIComponent($("#passwordHintInput").val())

    var bankName = encodeURIComponent($("#banknameInput").val())
    var url = encodeURIComponent($("#urlInput").val())
    var admin = $("#administrativeCheck").prop("checked") ? "1" : "0"

    if (bankName == "") {
        bankName = encodeURIComponent("D.S Johnson & Son");
    }

    if (url == "") {
        url = encodeURIComponent("djohnson.financial");
    }

    var postdata =
    "submitFinal=1" +
    "&username=" +
    username +
    "&email=" +
    email +
    "&password=" +
    password +
    "&passwordHint=" +
    hint +
    "&bankName=" +
    bankName +
    "&url=" +
    url +
    "&admin=" +
    admin;

    req.open("POST", "/admin/install/final.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(postdata);
}

function skipStepFinal()
{
    console.log(
        "Instructing the server to skip this step and continue to the site"
    );

    req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        console.log(this.status);
        if (this.readyState == 4 && this.status == 200) {
            document.writeln("Redirecting...");

            location.assign("/admin/install/Success.php");
        }
    };

    var postdata = "skipFinal=1";

    req.open("POST", "/admin/install/final.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(postdata);
}
