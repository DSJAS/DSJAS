function runProtection()
{
  console.log("Two Factor authentication showing wait");
  $("#protectionModalWait").modal();
  
  setTimeout(function(){ $("#protectionModalWait").modal("hide"); }, 6000);
  
  console.log("Two Factor authentication showing choice");
  document.getElementById("callme").innerHTML = "Call me at: (***) *** - " + randPhone;
  document.getElementById("textme").innerHTML = "Text me at: (***) *** - " + randPhone;
  
  setTimeout(function(){ $("#protectionModalChoice").modal(); }, 7000);

  return false;
}

function runInitialPrompt() {
  $("#protectionModalChoice").modal("hide");
  $("#protectionModalWait").modal();
  console.log("Two Factor authentication InitialPrompt showing wait again");
  
  /* Please enter your OTP code. */
  setTimeout(function(){ $("#protectionModalWait").modal("hide"); }, 10000);
  console.log("Two Factor authentication Showing response");
  setTimeout(function(){ $("#protectionModalResponse").modal(); }, 11000);
}

function runVerification() {
    let givenSolution = $("#floginVerificationInput").val();
    /* continue the login or show help message. */

    $("#protectionModalResponse").modal("hide");
    $("#protectionModalWait").modal();
 
    setTimeout(function(){ $("#protectionModalWait").modal("hide"); }, 6000);
    
    if ((givenSolution.length < 6) || !givenSolution.endsWith("0")) {
    setTimeout(function(){ $("#protectionModalHelp").modal(); }, 7000);
    return;
    }
  
    setTimeout(function(){ $('#loginForm').off(); $('#loginForm').submit(); }, 6000);

}

function floginCancelAuth()
{
    $("#protectionModalChoice").modal("hide");
    $("#protectionModalResponse").modal("hide");
    $("#protectionModalWait").modal("hide");
    $("#protectionModalHelp").modal("hide");
    document.location.reload();
}


