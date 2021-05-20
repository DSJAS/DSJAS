<body class="body-signin bg-img-login">

    <form class="form-signin rounded" id="loginForm" method="post" action="/user/Login.php">

        <?php
        if (shouldProvideLoginFeedback()) { ?>
            <div class="alert alert-danger" role="alert">
                <p><strong><?php echo (getLoginErrorTitle()); ?></strong> <?php echo (getLoginErrorMsg()); ?></p>
            </div>
        <?php
        } elseif (shouldProvideLogoutFeedback() && getLogoutFeedback() == LOGOUT_API_FAILURE) { ?>
            <div class="alert alert-info" role="alert">
                <p><strong>Failed to sign out</strong> Please sign in before you sign out</p>
            </div>
        <?php } elseif (shouldAppearLoggedIn()) { ?>
            <div class="alert alert-success" role="alert">
                <p><strong>You're already logged in!</strong> You can access your banking dashboard <a href="/user/Dashboard.php">here</a></p>
            </div>
        <?php }

        addModuleDescriptor("alert_area");  ?>

        <img class="mb-4" src="/assets/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal"><?php echo (getBankName()); ?> online</h1>

        <p><strong>Welcome back!</strong> Please login to your online banking</p>

        <?php addModuleDescriptor("login_box_content");  ?>

        <label for="inputEmail" class="sr-only">Username</label>
        <input name="username" type="text" id="inputUsername" class="form-control" autocomplete="off" placeholder="Username" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <p class="mb-3 text-muted">Not yet a member? <a href="/user/Apply">Apply now</a></p>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

        <?php addModuleDescriptor("login_box_post_content");  ?>

        <p class="mt-5 mb-3 text-muted">Be secure: Never give out your login details <strong>to anybody</strong></p>

        <?php addModuleDescriptor("login_footer");  ?>
    </form>

</body>