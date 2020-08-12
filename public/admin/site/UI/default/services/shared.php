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
    <?php include ABSPATH . getRawThemeContent("Nav.php", "components/"); ?> </nav>

    <?php include ABSPATH . getRawThemeContent("ServicesHeader.html", "components/"); ?>

    <div class="container">
        <h2>The award winning shared account</h2>

        <p>
            Do you have a partner, spouse or other person you share money with? Yeah, me neither - wait what; you do?!?!?! Well, I guess you'll be
            interested in sharing an account between the two of you.

            Or wait, are there more than two. Hmmm, this is getting complicated.

            Well, luckily, our accounts support up to five people in a shared account.
        </p>

        <h3>Why us?</h3>

        <p>
            In this day and age, standard services are what you should expect. So we have delivered the most bog-standard service you could ever expect.
            Bog standard sharing, online banking and money collaboration.

            Our service is the most standard one you can find, you'll love it.
        </p>

        <h3>Financial protection</h3>

        <p>
            Our multi-award-winning security systems (award pending) are using industry grade PHP default encryption. You can be assured that your data
            is safe with a basic SSL certificate on our site and our security by obscurity being very well used.

            You can rest assured that your data is protected by our perfectly secure basic encryption technology.
        </p>

        <h3>Over the top support</h3>

        <p>
            Not to blow our own trumpet, but we have professional, legally outsourced support representatives working around the clock (for two hours a day)
            to help you out with whatever problems you come up with. You don't need help from anybody else or any other location and you cannot get help
            from anybody except us there is no help for you. Nobody can help you (apart from us).
        </p>

        <a href="/user/Apply.php" class="btn btn-primary mb-2">Apply today</a>
    </div>
<?php }
