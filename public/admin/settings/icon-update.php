<?php

require "../AdminBootstrap.php";

function err()
{
    header("Location: /admin/settings?iconError");
    die();
}

// Do we actually have a file?
if (!isset($_FILES["icon"])) {
    err();
}

// Is it a real image?
$info = getimagesize($_FILES["icon"]["tmp_name"]);
if ($info == false) {
    err();
}

if (!move_uploaded_file($_FILES["icon"]["tmp_name"], FAVICON)) {
    err();
}

// Success!
header("Location: /admin/settings?iconSuccess");
