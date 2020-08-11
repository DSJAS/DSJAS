<!--
DSJAS - Default theme
Support navbar component file
-->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light">
    <div class="navbar-brand">
        <img src="/assets/logo.png" width="30" height="30" alt="logo">
        <a class="navbar-brand" href="/support/Support"><?= getBankName(); ?><i> - Support</i></a>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/support/Support">Support home</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="getHelpDropdown" data-toggle="dropdown">
                    Get help
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/support/Online">Online banking</a>
                    <a class="dropdown-item" href="/support/Fraud">Fraud</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/support/Finances">Finances</a>
                    <a class="dropdown-item" href="/support/Transfer">Transfers</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/support/Contact">Contact</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/support/Fraud">Fraud prevention</a>
            </li>
        </ul>
    </div>

    <div class="justify-right form-inline">
        <a class="btn btn-outline-primary" href="/" style="margin-right: 25px">Return to home</a>
    </div>
</nav>