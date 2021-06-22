dsjas.test = function()
{
  console.log("DSJAS API Loaded");
  return true;
}

dsjas.getBankName = function()
{
  return dsjas.util.makeApiRequest("bank-name", ["name"]);
}

dsjas.getBankUrl = function()
{
  return dsjas.util.makeApiRequest("bank-url", ["url"]);
}

dsjas.getThemeName = function () {
  return dsjas.util.makeApiRequest("theme-name", ["name"]);
}