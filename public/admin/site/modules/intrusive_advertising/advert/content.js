function triggerAdvertisement()
{
    console.log("Playing advertisement...");

    $("#advertModal").modal("show");

    var ad = randomInteger(4);

    switch (ad) {
    case 1:
        raidShadowLegends();
        break;
    case 2:
        squareSpace()
        break;
    case 3:
        mafiaCity();
        break;
    case 4:
        grubHub();
        break;
    default:
        raidShadowLegends();
        break;
    }

    $("#loadingAdvert").addClass("d-none");

    mainloop();
}

function raidShadowLegends()
{
    $("#raidShadowLegends").removeClass("d-none");
}

function squareSpace()
{
    $("#squareSpace").removeClass("d-none");
}

function mafiaCity()
{
    $("#mafiaCity").removeClass("d-none");
}

function grubHub()
{
    $("#grubhub").removeClass("d-none");
}

function mainloop()
{
    var countdownValue = 30;

    var interval = setInterval(
        function () {
            if (countdownValue <= 0) {
                clearInterval(interval);
                $("#skipAd").removeClass("d-none");
                $("#skipCountdown").addClass("d-none");

                return;
            }

            $("#advertisementCountdownValue").text(countdownValue);
            countdownValue--;
        }, 1000
    );
}

function closeAdvert()
{
    $("#advertModal").modal("hide");

    $("#ads").remove();
}