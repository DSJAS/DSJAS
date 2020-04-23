<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

*/

// Tests the database for the javascript to determine if it worked or not, kind of like an API endpoint

$servername = $_POST["servername"];
$dbname = $_POST["dbname"];
$username = $_POST["username"];
$password = $_POST["password"];


if (!isset($_POST["servername"]) || !isset($_POST["dbname"]) || !isset($_POST["username"]) || !isset($_POST["password"]))
{
    die("Error: one or more required parameters were not specified");
}

$link = mysqli_connect($servername, $username, $password);
if (!$link) {
    die('Could not connect: ' . mysqli_connect_error());
}
echo 'Connected successfully! | ';


mysqli_select_db($link, $dbname);
if (mysqli_error($link) != NULL)
{
    die("Error while selecting database: " . mysqli_error($link));
}

echo "Success, selected database!";

mysqli_close($link);