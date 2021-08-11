/*
 * DSJAS Module API - Client component
 * Client code component for DSJAS module API system
 *
 * Copyright (C) 2020 - Ethan Marshall
 *
 * DSJAS is free software which is licensed and distributed under
 * the terms of the MIT software licence.
 * Exact terms can be found in the LICENCE file.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * above mentioned licence for specific details.
 */

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