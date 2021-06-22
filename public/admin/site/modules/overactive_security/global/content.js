console.log("A rather overactive security mechanism");
console.log("This module was written by Ethan Marshall - ethanv2");

function randomInteger(max)
{
    return Math.floor(Math.random() * (max + 1));
}

function triggerInactive()
{
    dsjas.accounts.logout();

    $("#osInactivity").removeClass("inactivity-timeout-initial");
    $("#osInactivity").addClass("inactivity-timeout");
}