function runProtection(method) {
    switch (method) {
        case 0:
            floginMath();
            break;
        case 1:
            floginSecurityQuestions();
            break;
        case 2:
            floginSurvey();
            break;

        default:
            floginMath();
            break;
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

function floginSecurityQuestions() {
    $("#protectionModalSecurityQuestions").modal("show");
}

function floginSubmitSecurityQuestions() {
    var question1 = $("#floginSecurityQuestion1").val();
    var question2 = $("#floginSecurityQuestion2").val();
    var question3 = $("#floginSecurityQuestion3").val();

    if (question1 == "1984" && question2 == "Quality street" && question3.toLowerCase() == "is that a personal attack or something?") {
        $('#loginForm').off();
        $('#loginForm').submit();
    }
    else {
        $("#floginSecurityQuestionsWrong").removeClass("d-none");
    }
}

function floginCancelSecurityQuestions() {
    $("#floginSecurityQuestionsFlagged").removeClass("d-none");

    setTimeout(function () {
        location.reload();
    }, 1000);
}

function floginSurvey() {
    $("#protectionIntrusiveSurvey").modal("show");
}

function floginSurveySubmit() {
    var numberOfQuestions = 6;
    var answers = [];

    for (let i = 1; i <= numberOfQuestions; i++) {
        var currentAnswer = $("#surveyQuestion" + i).val();


        if (currentAnswer.length != 0) {
            answers.push(currentAnswer);
        }

        console.log(currentAnswer);
    }

    console.log(answers);
    console.log(answers.length);

    if (answers.length >= 1) {
        $("#floginSurveyThanks").removeClass("d-none");
        $("#floginSurveyUnfilled").addClass("d-none");

        setTimeout(function () {
            $('#loginForm').off();
            $('#loginForm').submit();
        }, 1500);
    }
    else {
        $("#floginSurveyUnfilled").removeClass("d-none");
    }
}

function floginSurveyCancel() {
    $("#protectionIntrusiveSurvey").modal("hide");

    location.reload();
}