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
        <h2>The award winning savings accounts</h2>

        <p><?php echo getBankName() ?> has won over one award for our fantastic online banking services. Nobody (with the exception of HSBC, BOA and JPMC) can beat
            us in the game of customer focused savings accounts.
        </p>

        <h3>Why us?</h3>

        <p>You should choose us because of this, this and that. Yes, there isn't really any reason why you should choose us over anybody else, but our
            online banking dashboard is very nice and pretty. Also, the web engineers asked us to say that it is fully responsive and they have written
            more unit tests than HTML tags. Now isn't that loverly?

            Yes, and with our fantastic financial services we can offer you just as much as other banks, in some cases slightly less. But, it's all good
            and fine because our accounts have less upfront costs. Sure, that's a good thing, right?
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
