/* DSJAS - Client Side JS */

var popupShown = false;

$(
    function () {
        $('[data-toggle="popover"]').popover()
    }
)

function onPasswordHover()
{
    if (!popupShown) {
        popupShown = true;
        $("#passwordWarn").popover("show");

        setTimeout(function() {
            popupShown = false;
            $("#passwordWarn").popover("hide");
        }, 5000);
    }
}