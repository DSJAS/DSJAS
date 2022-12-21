/* DSJAS - Client Side JS */

/* Common code */
$(document).ready(
	function() {
		$('[data-toggle="popover"]').popover();
		$('[data-toggle="tooltip"]').tooltip();
	}
);

function showSpinner() {
	$("#saveProgress").removeClass("d-none");
}

/* Variables */
var ongoingConfigTest = false;

/* ==================== [INITIAL CONFIGURATION] ==================== */

/* ====================     [VERIFICATION]      ==================== */

/* ==================== [DATABASE CONFIGURATION] =================== */
function confirmAndSetup() {
	showSpinner();

	let form = {
		hostname: $("#servername").val(),
		database: $("#dbname").val(),
		username: $("#username").val(),
		password: $("#password").val(),
	}

	const formdata = JSON.stringify(form);
	const postdata = "config=" + encodeURIComponent(formdata);

	req = new XMLHttpRequest();
	req.onreadystatechange = function() {
		if (this.readyState == 4) {
			// API returns non-200 on setup error
			if (this.status != 200) {
				console.log("Setup failed! Please provide the following in technical support requests:");
				console.log(this.responseText);
				$("#failure-msg").removeClass("d-none");
				return;
			}

			console.log(
				"Sent required information, redirecting to next stage. If everything worked out, we should get to final setup."
			);
			location.reload();
	}
	};

	req.open("POST", "/admin/install/database", true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	req.send(postdata);
}

function testConfiguration() {
	if (!ongoingConfigTest) {
		console.log("Testing configuration");
		ongoingConfigTest = true;

		doTestConfig();

		ongoingConfigTest = false;
	} else {
		console.log("Test ongoing, ignoring click event");
	}
}

function doTestConfig() {
	// Show loading popup
	cleanupTest();
	$("#configCheck").popover("show");

	let form = {
		hostname: $("#servername").val(),
		database: $("#dbname").val(),
		username: $("#username").val(),
		password: $("#password").val(),
	}

	const formdata = JSON.stringify(form);
	const postdata = "test=" + encodeURIComponent(formdata);

	let req = new XMLHttpRequest();
	req.onreadystatechange = function() {
		if (this.readyState == 4) {
			if (this.status != 200) {
				$("#configCheck").popover("dispose");
				$("#configCheck").attr("title", "Check completed: failure");
				$("#configCheck").attr(
					"data-content",
					"The server encountered an error while attempting to connect to the database using the data you provided. Please verify that the details are correct and that your database is available."
				);
				$("#configCheck").popover("show");

				setTimeout(
					cleanupTest, 5000
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
					cleanupTest, 7500
				);
			}

			console.log("If receiving technical support, please provide the following response data:")
			console.log(this.responseText)
		}
	};

	// NOTE: We could send the JSON directly as the request body here, but
	// for legacy reasons, it is best to keep the API using a URL-encoded
	// form with a JSON parameter.
	req.open("POST", "/admin/install/dbtest", true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	req.send(postdata);
}

function handleServerCheckResponse(response) {

}

function cleanupTest() {
	$("#configCheck").popover("dispose");

	$("#configCheck").attr("title", "Checking configuration...");
	$("#configCheck").attr(
		"data-content",
		"Sending configuration to server. Please wait..."
	);
}
