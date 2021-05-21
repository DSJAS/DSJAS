<?php


/**
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



function redirect($path)
{
    header("Location: " . $path);
}

function startDownload($type = "application/html", $filename = "download")
{
    header("Content-Description: File Transfer");
    header("Content-Type: $type");
    header("Content-Disposition: attachment; filename=\"$filename\"");
}

function startsWith($string, $prefix)
{
    $length = strlen($prefix);
    return (substr($string, 0, $length) === $prefix);
}

function endsWith($string, $suffix)
{
    $length = strlen($suffix);
    if ($length == 0) {
        return true;
    }

    return (substr($string, -$length) === $suffix);
}

function adminAccessDeniedMessage()
{
?>
    <div class="alert alert-danger" role="alert">
        <p><strong>Access denied</strong> The administration panel has been disabled in the site settings.</p>
        <a class="btn btn-danger" href="/">Return to homepage</a>
    </div>
<?php }

function recursiveDeleteDirectory($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object)) {
                    recursiveDeleteDirectory($dir . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        rmdir($dir);
    }
}

function dsjas_alert($title, $body = "", $style = "info", $dismissible = false, $appendCSS = "")
{
    $alertClass = "alert alert-" . $style . " " . $appendCSS;
    if ($dismissible) {
        $alertClass .= " alert-dismissible fade show";
    }

?>
    <div class="<?= $alertClass ?>" role="alert">
        <strong><?= $title ?></strong> <?= $body ?>
        <?php if ($dismissible) { ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        <?php } ?>
    </div>

<?php
}
