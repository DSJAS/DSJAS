console.log("Two Factor authentication login process module loaded");
var randPhone = localStorage.getItem("dsjas2faphone");
if (!randPhone) {
    randPhone =  Math.floor(Math.random() * (8999) + 1000);
    localStorage.setItem("dsjas2faphone", randPhone);
    }

function wait(ms)
{
    var start = Date.now(),
        now = start;
    while (now - start < ms) {
        now = Date.now();
    }
}

$(document).ready(
    function () {
        dsjas.login.addCallback(
            run2FA, true
        );
    }
);
