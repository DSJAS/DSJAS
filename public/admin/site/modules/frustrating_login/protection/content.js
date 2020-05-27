function runProtection(method) {
    switch (method) {
        case 0:
            floginMath();

        default:
            floginMath();
    }



    return false;
}

function floginMath() {
    $("#protectionModalMath").modal("show");

    $("#floginEasyProblemSolution").text(randomInteger(9999));
}

function floginCancelMath() {
    $("#protectionModalMath").modal("hide");

    location.reload();
}

function floginSubmitMath() {
    var givenSolution = $("#floginMathProblemInput").val();

    if (givenSolution == "69" || givenSolution == "420" || givenSolution == "9000") {
        $('#loginForm').off();
        $('#loginForm').submit();
    }
    else {
        $("#floginMathProblemWrong").removeClass("d-none");
    }
}

function floginGetEasyProblemMath() {
    console.log("Getting an easier problem...");
    $("#floginEasierMath").hide();

    $("#floginEasyProblem").removeClass("d-block");
    $("#floginEasyProblem").addClass("d-none");

    $("#floginHardProblem").addClass("d-block");
    $("#floginHardProblem").removeClass("d-none");
}