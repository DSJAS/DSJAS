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

/*
    DEFAULT THEME - DSJAS
    =====================

    This is the theming files included in the default installation of DSJAS.
    It contains HTML and PHP files required to load and display the default theme.

    This file should never be accessed directly, and instead should only be
    required by a file which has already bootstrapped the site.
    This means that your script must have defined the ABSPATH constants
    and preformed other required bootstrapping tasks before the page
    can be displayed.


    For more information of theming and creating your own themes, please refer to the
    API documentation for themes and plugins.
*/

require_once THEME_API . "General.php";
require_once THEME_API . "Accounts.php";
require_once THEME_API . "Appearance.php";

// Theme entry point
function getTheme()
{
    ?>
    <?php include "components/Nav.php"; ?> </nav>

    <div class="container-fluid about-page-teaser">
        <div class="about-page-teaser-overlay rounded">
            <h1>About us</h1>
            <br>
            <h2>Although we're just a bank, we're so much more than a bank</h2>
        </div>
    </div>

    <div class="container">
        <h2>Our origins</h2>
        <p>Back in the ancient era of 2007 AD, money was simpler: more elegant. We know, because we were there.
            Sadly, those days are gone. <?php echo (getBankName()); ?> was created by our three founders to make banking simple again. I guess you could say, make banking great again.
        </p>

        <h2>Why we care</h2>
        <p>We care because we've been there. Two of our three founders started out working in mailrooms of their own mansions. Let's just say that they didn't do too well.
            Before long, they were thrown into financial turmoil as they had to fire themselves for being substandard. Yes, our billionaire founders know what it means to be in financial trouble.
        </p>
        <br>
        <p>That is why we care. We care, because we've been there</p>

        <h2>Our fight on fraud</h2>
        <p>You may have noticed that many of our online banking pages contain warnings and notices about fraud and scams. That is not an accident.
            You see, when one of our founders was young, their grandmother was scammed out of 70 cents when they bought a bogus financial product.

            Sadly, she never got back her 70 cents. We vowed to never let anybody go through the trouble that she did. So, we have decided to wage
            a war on fraud, scams and any kind of sketchy operation that's going on around here. After all, when a red spy makes it into the base,
            it's up to the blues to protect the briefcase.
        </p>

        <h2>Get good (at banking)</h2>
        <p>Our support page and handy articles tell you everything you need to know about banking. Our dedicated team of support agents is available
            over 2 hours a day! Let's see somebody else give you that.

            To get to our numerous resources, please use the links below.
        </p>

        <hr>

        <div class="mb-2">
            <a href="/support/Support" class="btn btn-primary">Visit the support center</a>
            <a href="/support/Contact" class="btn btn-secondary">Contact us</a>
            <a href="/support/Fraud" class="btn btn-secondary">Help us fight fraud</a>
        </div>

    </div>


    <?php require ABSPATH . getRawThemeContent("Footer.php", "components/"); ?>
<?php }
