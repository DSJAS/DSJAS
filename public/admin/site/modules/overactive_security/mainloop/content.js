setInterval(
    function () {
        console.log("Checking for inactivity...");

        var random = randomInteger(3);

        console.log("Inactivity rating: " + random);

        if (random == 3) {
            console.log("You have been detected inactive and will be signed out for security");

            triggerInactive();
        }
    }, (60000 * 1)
);