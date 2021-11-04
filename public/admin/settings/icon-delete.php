<?php

require "../AdminBootstrap.php";

function err()
{
    header("Location: /admin/settings?iconError");
    die();
}

// Do we actually have a file?
if (!file_exists(FAVICON)) {
    err();
}

// Delete favicon
unlink(FAVICON);

// Success!
header("Location: /admin/settings?iconSuccess");
