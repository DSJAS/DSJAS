<!--
DSJAS - Default theme
Navbar component file
-->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light">
    <div class="navbar-brand">
        <img src="/assets/logo.png" width="30" height="30" alt="logo">
        <a class="navbar-brand" href="/"><?php echo (getBankName()); ?></a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAbout" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    About us
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownAbout">
                    <a class="dropdown-item" href="/About">About us</a>
                    <a class="dropdown-item" href="/Mission">Our mission</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownServices" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Services
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownServices">
                    <a class="dropdown-item" href="/services/current">Current accounts</a>
                    <a class="dropdown-item" href="/services/savings">Savings accounts</a>
                    <a class="dropdown-item" href="/services/shared">Shared accounts</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/services/loans">Loans</a>
                    <a class="dropdown-item" href="/services/mortgages">Mortgages</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/services/deposit">Security deposit boxes</a>
                    <a class="dropdown-item" href="/services/transfer">Secure transfer</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownOnline" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Online banking
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/user/Dashboard.php">Go to My Bank Online™</a>
                    <a class="dropdown-item" href="/user/Options.php">View online banking options</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/user/About">About My Bank Online™</a>
                    <a class="dropdown-item" href="/services/online">Our online banking options</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Help and support
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/support/Support">Visit support</a>
                    <a class="dropdown-item" href="/support/Online">Online banking help center</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/support/Contact">Contact support</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/support/Contact">Contact us</a>
            </li>
        </ul>
    </div>