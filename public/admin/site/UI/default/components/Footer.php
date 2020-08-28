<?php

require_once THEME_API . "General.php";

?>

<!--
DSJAS - Default theme
Footer component file
-->

<div id="footer" class="footer bg-dark">
    <div class="">
        <div class="footer-text">
            <pre class="lead small text-light">&copy 2018 Black Mesa Inc. All rights reserved</pre>
            <?php addModuleDescriptor("footer");  ?>
        </div>

        <div class="footer-links">
            <a href="/" class="btn btn-primary">Home</a>
            <a href="/support/Support" class="btn btn-secondary">Help</a>
            <a href="/support/Contact" class="btn btn-warning">Contact support</a>
        </div>
    </div>
</div>