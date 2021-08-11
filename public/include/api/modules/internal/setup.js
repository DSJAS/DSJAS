/*
 * Module API setup
 *
 * Global variables and/or state required for DSJAS APIs
 */

const dsjas = {
  accounts: {},
  login: {},
  util: {}
}

const api_path = "/include/api/modules/remote/";
const sess_cookie = "PHPSESSID";

dsjas.util.makeApiRequest = function (api, extract) {
  var result = [];

  var xmlhttp = new XMLHttpRequest();
  const fullApi = api_path + api + ".php";

  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4) {
      var response = JSON.parse(this.responseText);

      for (i = 0; i < extract.length; i++) {
        result[extract[i]] = response[extract[i]];
      }
    }
  };
  xmlhttp.open("GET", fullApi, false);
  xmlhttp.send();

  if (extract.length > 1) {
    return result;
  } else {
    return result[extract[0]];
  }
}

dsjas.util.makeGetRequest = function (api, headers) {
  var xmlhttp = new XMLHttpRequest();
  var fullApi = api_path + api + ".php?";

  for (var i = 0; i < headers.length; i++) {
    fullApi += headers[i] + "&";
  }

  xmlhttp.open("GET", fullApi, true);
  xmlhttp.send();
}

dsjas.util.makePostRequest = function (api, headers, values) {
  var result = [];

  var xmlhttp = new XMLHttpRequest();
  const fullApi = api_path + api + ".php?";
  var data = "";

  for (var i = 0; i < headers.length; i++) {
    data += headers[i] + "=" + values[i] + "&";
  }

  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4) {
      result = JSON.parse(this.responseText);
    }
  };

  xmlhttp.open("POST", fullApi, false);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(data);

  return result;
}