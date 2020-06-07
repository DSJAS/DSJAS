# Access management - *user manual*

This section in the user manual will detail how to log in to your admin dashboard account, log out and erase your session.

## Logging in

Navigating to *<http://localhost/admin/user/SignIn.php>* will display the DSJAS login page. You will notice that there is a "Log In" button, as well as two fields.

In the top field, enter your assigned username and your assigned password into the bottom box.

> **Note:** If you ever forget your password, another administrator can reset it with the push of a button. Just ask them to reset your password and then change it once you log in.

Clicking on "Log In" will cause one of two things to happen

1. You will be redirected to the admin dashboard
1. An incorrect credentials error will be displayed

The incorrect credentials error means that either your username or password was incorrect. DSJAS will not give away which was incorrect for security.

## Logging out

> **Note:** Your session for the admin dashboard is stored separately to the bank site. Signing out from the admin dashboard will not sign you out from the bank
> **Tip:** There is a link to the log out page on the admin dashboard side bar

Navigating to *<http://localhost/admin/user/Logout.php>* will result in your admin dashboard login being ended. You will need to log in again before accessing any admin dasboard pages.

## Ending your session

> **Warning:** This will also end your session for the bank for the browser this method is used in. Please take care and don't end your session while you have unsaved changes

In your browser's cookie settings, deleting the cookie **PHPSESSID** will result in your DSJAS session being erased and you being logged out from all DSJAS pages.

This is great for ensuring that you don't leave traces of yourself on untrusted computers.
