<?php

/*
FELICITY BANK PORT - DSJAS
==========================

Welcome to the DSJAS theme port of Felicity Bank!

Felicity bank is an open source and free template for making
fake banking sites. This port is an exact replica of the site
ported to the DSJAS theme API, allowing for dynamically changing
the name, branding etc.

Almost nothing has been changed about the original, but some features
had to be removed due to their conflict with existing modules or
other plugins.

Please check out the Felicity Bank GitHub page here: https://github.com/0xB9/Felicity-Bank-Inc.


For more information of theming and creating your own themes, please refer to the
API documentation for themes and plugins.
*/

require_once THEME_API . "General.php";
require_once THEME_API . "Appearance.php";

function getBootstrap()
{
    setTitle("Welcome | " . getBankName());
    ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo getThemeContent("dogecoin.png", "assets/"); ?>" />
    <link rel="stylesheet" href=<?php echo (getThemeContent("style.css", "styles/")) ?>>
<?php }
