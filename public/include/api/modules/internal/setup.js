/*
 * Module API setup
 *
 * Global variables and/or state required for DSJAS APIs
 */

const dsjas = {
  util: {}
}

const api_path = "/include/api/modules/remote/";

dsjas.util.makeApiRequest = function(api, extract)
{
  var result = [];
  var done = false;

  var xmlhttp = new XMLHttpRequest();
  const fullApi = api_path + api + ".php";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4) {
      var response = JSON.parse(this.responseText);

      for (i = 0; i < extract.length; i++) {
        result[extract[i]] = response[extract[i]];
      }
    }
  };
  xmlhttp.open("GET", fullApi, false);
  xmlhttp.send();

  return result[extract];
}