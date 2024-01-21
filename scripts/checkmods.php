<?php
/*
 * checkmods.php - shows which modules are detected as missing by DSJAS
 *
 * This file is part of DSJAS
 * Written and maintained by the DSJAS project.
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


/*
 * Modified version of verifyPHP which returns which of the modules is not
 * present.
 */
function verifyPHP()
{
    /* mysqli */
    if (!function_exists("mysqli_connect"))
        return "myqsli";

    /* exif */
    if (!function_exists("getimagesize") || !function_exists("exif_imagetype"))
        return "exif";

    /* curl */
    if (!function_exists("curl_init"))
        return "curl";

    /* intl */
    if (!class_exists("NumberFormatter"))
        return "intl";

    /* zip */
    if (!class_exists("ZipArchive"))
        return "zip";

    return "";
}

if (verifyPHP() === "") { ?>
    <p style="color: green"><strong>All Modules Present and Installed</strong> You can now remove this script from your server.</p>
<?php } else { ?>
    <p style="color: red"><strong>The module <?= verifyPHP() ?> is missing</strong> Please install or enable this module.</p>
<?php }

?>