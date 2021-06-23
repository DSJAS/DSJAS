/* DSJAS - Client Side JS */

$(document).ready(function () {

    // Update clock
    setInterval(function() {
        var hours = $("#hours");
        var minutes = $("#minutes");
        var seconds = $("#seconds");

        var hoursTime = parseInt(hours.text());
        var minuteTime = parseInt(minutes.text());
        var secondTime = parseInt(seconds.text());


        var newSeconds, newMinutes, newHours;

        var newSeconds = secondTime + 1;
        if (newSeconds >= 60) {

            newSeconds = 0;
            newMinutes = minuteTime + 1;
            if (newMinutes >= 60) {
                newMinutes = 0;
                newHours = hoursTime + 1;
            }else{
                newHours = hoursTime;
            }
        }else{
            newMinutes = minuteTime;
            newHours = hoursTime;
        }

        if (newSeconds < 10) {
            $("#secL0").removeClass("d-none");
        }else{
            $("#secL0").addClass("d-none");
        }
        if (newMinutes < 10) {
            $("#minL0").removeClass("d-none");
        }else{
            $("#minL0").addClass("d-none");
        }
        if (newHours < 10) {
            $("#hourL0").removeClass("d-none");
        }else{
            $("#hourL0").addClass("d-none");
        }

        seconds.text(newSeconds);
        minutes.text(newMinutes);
        hours.text(newHours);
    }, 1000);

    // Update page hits
    setInterval(function() {
        var xmlhttp = new XMLHttpRequest();
        var endpoint = "/admin/stats-update.php?hits";

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var myArr = JSON.parse(this.responseText);

                if (myArr.error != undefined) {
                    console.log("API returned an error. Refusing to update...");
                }

                $("#hitsCounter").text(myArr.totalHits);
            }
        };

        xmlhttp.open("GET", endpoint, true);
        xmlhttp.send();
    }, 10000);

});