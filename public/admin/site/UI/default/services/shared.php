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

/*
    DEFAULT THEME - DSJAS
    =====================

    This is the themeing files included in the default installation of DSJAS.
    It contains HTML and PHP files required to load and display the default theme.

    This file should never be accessed directly, and instead should only be
    required by a file which has already bootstrapped the site.
    This means that your script must have defined the ABSPATH constants
    and preformed other required bootstrapping tasks before the page
    can be displayed.


    For more information of theming and creating your own themes, please refer to the
    API documentation for themes and plugins.
*/

require_once(ABSPATH . INC . "api/theme/General.php");
require_once(ABSPATH . INC . "api/theme/Accounts.php");
require_once(ABSPATH . INC . "api/theme/Appearance.php");

// Theme entry point
function getTheme()
{ ?>
    <?php require(ABSPATH . getRawThemeContent("Nav.php", "components/")); ?> </nav>

    <?php require(ABSPATH . getRawThemeContent("ServicesHeader.html", "components/")); ?>

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
