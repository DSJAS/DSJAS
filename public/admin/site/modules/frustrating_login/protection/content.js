/* Global variables */
var currentCaptchaStage = 1;
var captchaDebounce = false;

function runProtection(method)
{
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
    case 3:
        floginCaptcha();
            break;
    default:
        floginMath();
            break;
    }

    return false;
}

function floginMath()
{
    $("#protectionModalMath").modal("show");
}

function floginCancelMath()
{
    $("#protectionModalMath").modal("hide");

    location.reload();
}

function floginSubmitMath()
{
    var givenSolution = $("#floginMathProblemInput").val();

    if (givenSolution == "69" || givenSolution == "420" || givenSolution == "9000") {
        $("#protectionModalMath").modal("hide");
        dsjas.login.callbackYield();
    }
    else {
        $("#floginMathProblemWrong").removeClass("d-none");
    }
}

function floginGetEasyProblemMath()
{
    console.log("Getting an easier problem...");
    $("#floginEasierMath").hide();

    $("#floginEasyProblem").removeClass("d-block");
    $("#floginEasyProblem").addClass("d-none");

    $("#floginHardProblem").addClass("d-block");
    $("#floginHardProblem").removeClass("d-none");
}

function floginSecurityQuestions()
{
    $("#protectionModalSecurityQuestions").modal("show");
}

function floginSubmitSecurityQuestions()
{
    var question1 = $("#floginSecurityQuestion1").val();
    var question2 = $("#floginSecurityQuestion2").val();
    var question3 = $("#floginSecurityQuestion3").val();

    if (question1 == "1984" && question2 == "Quality street" && question3.toLowerCase() == "is that a personal attack or something?") {
        $("#protectionModalSecurityQuestions").modal("hide");
        dsjas.login.callbackYield();
    }
    else {
        $("#floginSecurityQuestionsWrong").removeClass("d-none");
    }
}

function floginCancelSecurityQuestions()
{
    $("#floginSecurityQuestionsFlagged").removeClass("d-none");

    setTimeout(
        function () {
            location.reload();
        }, 1000
    );
}

function floginSurvey()
{
    $("#protectionIntrusiveSurvey").modal("show");
}

function floginSurveySubmit()
{
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

        setTimeout(
            function () {
                $("#protectionIntrusiveSurvey").modal("hide");
                dsjas.login.callbackYield();
            }, 1500
        );
    }
    else {
        $("#floginSurveyUnfilled").removeClass("d-none");
    }
}

function floginSurveyCancel()
{
    $("#protectionIntrusiveSurvey").modal("hide");

    location.reload();
}

function floginCaptcha()
{
    $("#protectionAnnoyingCaptcha").modal("show");
}

function floginCaptchaInputHandler()
{
    if (captchaDebounce) return;
    captchaDebounce = true;

    $("#floginCaptchaInput").val("");

    if (currentCaptchaStage < 3){
        $("#floginCaptchaFailure").removeClass("d-none");

        setTimeout(
            function() {
                $("#floginCaptchaFailure").addClass("d-none");
                captchaDebounce = false;

                const currentImageId = "floginCaptchaImage" + (currentCaptchaStage).toString();
                const nextImageId = "floginCaptchaImage" + (currentCaptchaStage + 1).toString();

                $("#" + currentImageId).addClass("d-none");
                $("#" + nextImageId).removeClass("d-none");

                currentCaptchaStage++;
            }, 2000
        )
    }else{
        $("#floginCaptchaInput").addClass("d-none");
        $("#floginCaptchaSubmit").addClass("d-none");

        $("#floginCaptchaSuccess").removeClass("d-none");

        setTimeout(
            function() {
                $("#protectionAnnoyingCaptcha").modal("hide");
                dsjas.login.callbackYield();
            }, 2000
        )

    }

}