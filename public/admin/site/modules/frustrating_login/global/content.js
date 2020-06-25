console.log("Frustrating login process module loaded");
console.log("This module was written by Ethan Marshall - OverEngineeredCode");

function wait(ms)
{
    var start = Date.now(),
        now = start;
    while (now - start < ms) {
        now = Date.now();
    }
}

function randomInteger(max)
{
    return Math.floor(Math.random() * (max + 1));
}

$(document).ready(
    function () {
        var randomNum = randomInteger(5);
        console.log(randomNum + " will be the verification method used");

        $('#loginForm').submit(
            function () {
                return runProtection(randomNum);
            }
        );
    }
);