dsjas.test = function()
{
  console.log("DSJAS API Loaded");
  return true;
}

dsjas.getBankName = function()
{
  return dsjas.util.makeApiRequest("bank-name", ["name"])["name"];
}

dsjas.getBankUrl = function()
{
  return dsjas.util.makeApiRequest("bank-url", ["url"])["url"];
}