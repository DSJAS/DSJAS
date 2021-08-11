function run2FA()
{
  console.log("Two Factor authentication showing wait");
  $("#protectionModalWait").modal();

  setTimeout(function () { $("#protectionModalWait").modal("hide"); $("#protectionModalChoice").modal() }, 6000);

  console.log("Two Factor authentication showing choice");
  document.getElementById("callme").innerHTML = "Call me at: (***) *** - " + randPhone;
  document.getElementById("textme").innerHTML = "Text me at: (***) *** - " + randPhone;

  return false;
}

function runInitialPrompt() {
  $("#protectionModalChoice").modal("hide");
  $("#protectionModalWait").modal();
  console.log("Two Factor authentication InitialPrompt showing wait again");

  /* Please enter your OTP code. */
  setTimeout(function () { $("#protectionModalWait").modal("hide"); $("#protectionModalResponse").modal(); }, 10000);
  console.log("Two Factor authentication Showing response");
}

function validateCode() {
  let givenSolution = $("#floginVerificationInput").val();

  /* Solution must be 6 digits or more and must end in a zero */
  if ((givenSolution.length < 6) || !givenSolution.endsWith("0")) {
    $("#protectionModalHelp").modal();
  } else {
    $("#protectionModalWait").modal("hide"); dsjas.login.callbackYield();
  }
}

function runVerification() {
  /* continue the login or show help message. */
  $("#protectionModalResponse").modal("hide");
  $("#protectionModalWait").modal();

  setTimeout(function () { $("#protectionModalWait").modal("hide"); validateCode(); }, 6000);
}

function floginCancelAuth()
{
    $("#protectionModalChoice").modal("hide");
    $("#protectionModalResponse").modal("hide");
    $("#protectionModalWait").modal("hide");
    $("#protectionModalHelp").modal("hide");
    document.location.reload();
}