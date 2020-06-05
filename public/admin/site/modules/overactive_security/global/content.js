console.log("A rather overactive security mechanism");
console.log("This module was written by Ethan Marshall - OverEngineeredCode");

function randomInteger(max) {
    return Math.floor(Math.random() * (max + 1));
}

function triggerInactive() {
    document.cookie = "PHPSESSID=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    $("#osInactivity").removeClass("inactivity-timeout-initial");
    $("#osInactivity").addClass("inactivity-timeout");
}