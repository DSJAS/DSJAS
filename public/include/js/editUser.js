$(
    function () {
        $('[data-toggle="popover"]').popover()
    }
)

function onPasswordHover()
{
    $("#password").popover("show");
}

function onPasswordHoverEnd()
{
    $("#password").popover("hide");
}