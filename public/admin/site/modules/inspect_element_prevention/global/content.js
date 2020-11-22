console.log("Inspect element prevention module loaded");
console.log("This module was written by Ethan Marshall - ethanv2");

$(document).ready(
    function () {
        document.body.oncontextmenu = function () {
            return false;
        }

        /*  Return default behaviour if the magic chord is used
        The magic chord is ALT+SHIFT+O (for overlay)

        When this is pressed, inspect element will function as the default in the browser
        This should allow for inspect element to be used again from the context menu
        */
        document.onkeydown = function (e) {
            if (e.altKey && e.shiftKey && e.code == "KeyO") {
                document.body.oncontextmenu = function () {
                    return true;
                }
            }
        }
    }
)