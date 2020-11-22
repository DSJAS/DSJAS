console.log("Intrusive advertising module loaded");
console.log("This module was written by Ethan Marshall - ethanv2");

function randomInteger(max)
{
    return Math.floor(Math.random() * (max + 1));
}

$(document).ready(
    function () {
        var randomTimeout = randomInteger(30);

        console.log("ALERT! This is a sponsored site. Online banking services are NOT FREE!");
        console.log("An advertisement will be shown in " + randomTimeout);

        setTimeout(
            function () {
                triggerAdvertisement();
            }, (randomTimeout * 1000)
        );
    }
);