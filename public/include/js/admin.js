/* D.S Johnson & Son - Client Side JS */

/* Util */

function encodeString(rawStr) {
    var encodedStr = rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function (i) {
        return '&#' + i.charCodeAt(0) + ';';
    });

    return encodedStr;
}


/* Init code */

$(document).ready(function () {
    // General panel
    var dbCheckbox = document.getElementById("noDatabase");
    if (dbCheckbox != null) {
        disableDatabaseSettings();

        $("#noDatabase").change(function () {
            disableDatabaseSettings();
        });
    }

    // Update panel
});

/* General settings pane */

function generalSaveSettings() {
    var progress = document.getElementById("saveProgress");
    progress.style.display = "inline-block";

    let csrf = $("#csrfToken").text();

    var postHeaders = "doSave=1&csrf=" + csrf;

    postHeaders = postHeaders.concat("&bankName=" + $("#bankName").val());
    postHeaders = postHeaders.concat("&bankURL=" + $("#bankURL").val());
    postHeaders = postHeaders.concat("&adminAccess=" + ($("#adminAccess").prop("checked") ? "1" : "0").toString());
    postHeaders = postHeaders.concat("&dbHost=" + $("#dbHostname").val());
    postHeaders = postHeaders.concat("&dbDatabase=" + $("#dbDatabase").val());
    postHeaders = postHeaders.concat("&dbUser=" + $("#dbUsername").val());
    postHeaders = postHeaders.concat("&dbPass=" + $("#dbPassword").val());
    postHeaders = postHeaders.concat("&noDB=" + ($("#noDatabase").prop("checked") ? "1" : "0").toString());

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.indexOf("ERROR") != -1 | this.responseText.indexOf("CSRF detected!") != -1) {
                document.write("Settings failed to save! Reloading...");
                location.assign(location.pathname.concat("?error"));
            }
            else {
                document.write("Settings saved, reloading...");
                location.assign(location.pathname.concat("?success"));
            }
        }
    };

    request.open("POST", "/admin/settings/Index.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(postHeaders);
}

function resetInstall() {
    console.log("Resetting install process...");

    var postdata = "doResetInstall=1";

    req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.write("Redirecting to install wizard...");
            location.assign("/admin/install/Install.php");
        }
    };

    req.open("POST", "/admin/settings/Index.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(postdata);
}

function resetFactory() {
    console.log("Resetting to factory defaults...");

    var postdata = "doResetFactory=1";

    req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.write("Reloading...");
            location.assign(location.pathname.concat("?factorySuccess"));
        }
    };

    req.open("POST", "/admin/settings/Index.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(postdata);
}

function discardChanges() {
    location.assign(location.pathname); // Reload page without get headers to prevent lingering saved messages
}

function disableDatabaseSettings() {
    currentState = $("#noDatabase").prop("checked");

    if (currentState) {
        $("#dbHostname").prop("disabled", true);
        $("#dbHostname").addClass("disabled");

        $("#dbDatabase").prop("disabled", true);
        $("#dbDatabase").addClass("disabled");

        $("#dbUsername").prop("disabled", true);
        $("#dbUsername").addClass("disabled");

        $("#dbPassword").prop("disabled", true);
        $("#dbPassword").addClass("disabled");
    }
    else {
        $("#dbHostname").prop("disabled", false);
        $("#dbHostname").removeClass("disabled");

        $("#dbDatabase").prop("disabled", false);
        $("#dbDatabase").removeClass("disabled");

        $("#dbUsername").prop("disabled", false);
        $("#dbUsername").removeClass("disabled");

        $("#dbPassword").prop("disabled", false);
        $("#dbPassword").removeClass("disabled");
    }
}

/* Update settings pane */

/* Accounts settings pane */

/* Modules and themes pane */

$(document).ready(function () {
    bsCustomFileInput.init() // Init file upload plugin
})

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

var currentPane = 1;

function switchToThemes() {
    if (currentPane != 1) {
        currentPane = 1;

        $("#themeTabBar").addClass("active");
        $("#themeTabBar").addClass("text-primary");

        $("#moduleTabBar").removeClass("active");
        $("#moduleTabBar").removeClass("text-primary");

        $("#themePanel").css("display", "block");
        $("#modulePanel").css("display", "none");
    }
}

function switchToModules() {
    if (currentPane != 2) {
        currentPane = 2;

        $("#themeTabBar").removeClass("active");
        $("#themeTabBar").removeClass("text-primary");

        $("#moduleTabBar").addClass("active");
        $("#moduleTabBar").addClass("text-primary");

        $("#modulePanel").css("display", "block");
        $("#themePanel").css("display", "none");
    }
}

function saveModuleSettings() {
    var moduleObjects = $("#modulesContainer").children();
    let modules = [];

    for (let i = 0; i < moduleObjects.length; i++) {
        modules.push($("#moduleName" + i).val());
    }

    let csrf = $("#csrf").val();

    var requestHeaders = "changeModuleStates=1&csrf=" + csrf;

    for (let j = 0; j < modules.length; j++) {
        const element = modules[j];

        let enabled = $("#moduleEnableSwitch" + j).prop("checked") ? "1" : "0";

        requestHeaders += "&" + element + "=" + enabled;
    }

    req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        response = req.responseText;

        if (this.readyState == 4 && this.status == 200) {
            document.write("Settings saved! Reloading...");
            location.assign(location.pathname.concat("?moduleSaved"));
        }
    };

    req.open("POST", "/admin/settings/installModule.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(requestHeaders);
}

/* Advanced settings pane */

function advancedSaveSettings() {
    var progress = document.getElementById("saveProgress");
    progress.style.display = "inline-block";

    let csrf = $("#csrfToken").text();

    var postHeaders = "doSave=1&csrf=" + csrf;

    postHeaders = postHeaders.concat("&global=" + $("#global").val());
    postHeaders = postHeaders.concat("&theme=" + $("#theme").val());
    postHeaders = postHeaders.concat("&module=" + $("#module").val());
    postHeaders = postHeaders.concat("&extension=" + $("#extension").val());

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.indexOf("ERROR") != -1 | this.responseText.indexOf("CSRF detected!") != -1) {
                document.write("Settings failed to save! Reloading...");
                location.assign(location.pathname.concat("?error"));
            }
            else {
                document.write("Settings saved, reloading...");
                location.assign(location.pathname.concat("?success"));
            }
        }
    };

    request.open("POST", "/admin/settings/Advanced.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(postHeaders);
}