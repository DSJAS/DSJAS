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
require_once THEME_API . "Accounts.php";

?>

<nav class="navbar navbar-expand-sm navbar-light bg-light border" style="height: 8vh; width: 100%;">
    <div class="container">
        <a href="/" class="navbar-brand"><?php echo getBankName(); ?></a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ml-auto">
                <li class="nav-link">
                    <a href="/About">About Us</a>
                </li>
                <li class="nav-link">
                    <a href="/Invest">Investing</a>
                </li>
                <li class="nav-link">
                    <a href="https://en.wikipedia.org/wiki/Technical_support_scam" target="_blank">Report Fraud</a>
                </li>
                <li class="nav-link">
                    <a href="/Contact">Contact</a>
                </li>
                <?php if (shouldAppearLoggedIn()) { ?>
                    <li class="nav-link">
                        <a href="/user/Logout?logout=1">Logout</a>
                    </li>
                <?php } ?>

                <?php addModuleDescriptor("nav-items"); ?>
            </ul>

            <?php addModuleDescriptor("navbar"); ?>
        </div>
    </div>
</nav>

<div style="height: 3vh;"></div>