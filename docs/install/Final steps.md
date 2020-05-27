# Final steps - *you're almost done*

> **Short on time?** This step is optional (but recommended). Although the site is fully functional without these settings, we recommend that you set them to your preferred values.

DSJAS is now installed and functional on your system. Congratulations! If you've made it so far, the program is fully functional as it is. However, this final step allows you to change some general settings of the site for convenience.

## The primary administrator

DSJAS requires a login in order to access to admin panel. The primary administrator is the first account created on the site - comparable to *root* on linux systems. At this point, you are given the opportunity to set the credentials to this account. As you can see, you can set a username and email as well as a password and hint.

> **Note:** Please don't set your password hint to something which gives away your password to an outsider. The hint will be displayed publicly if an incorrect password is provided to your account. The hint should only make sense to you!

### Password guidelines

DSJAS will enforce certain password guidelines by refusing to accept any password which does not:

1. Include at least 5 letters
1. A mix of alphanumeric numbers

We recommend that you set a very strong password for this account, as anybody gaining access could get access to your database credentials. This means that your personal information and hosting server could be compromised.

DSJAS will also refuse to accept overly common passwords, such as:

1. password
1. 1234
1. password1234

## Site configuration

> **Important:** All the configuration options in this section can be changed later in the *general* section of the DSJAS settings menu

### Bank name

This is the name which DSJAS will treat as the name of the bank you wish to spoof/pretend to be logging in to. The default value is functional and may fool some scammers, but we recommend creating your own.

These names should not be longer than 25 characters long. Themes display it in full in the most prevalent position on the page.

### Bank URL

If you have set up URL spoofing using the */etc/hosts* file, DSJAS can directly link to that domain using this field.

**If you have not done this, you must change the value to *<http://localhost>*** If you don't do this, issues may arise with dynamic links and permalinks. We recommend, however, that you do spoof a URL with the hosts file, as a raw *localhost* URL is a dead giveaway that this isn't a real banking site.

### Allow access to admin areas

> **WARNING:** This setting can only be reversed with file system access to the server, for security

This will allow you to make the admin panel more stealthy. All requests to the panel will result in a *404 - not found* message. This is great if a scammer appears aware that this bank may not be legitimate and is trying to discover signs of DSJAS being installed. The admin panel is a massive giveaway - it literally says DSJAS all over it. Therefore, this setting will make that giveaway impossible to use.

We recommend that you leave this setting on by default, and change it later if required. It's also worth mentioning that this setting works in real time; you can change it at the first sign that a scammer is becoming suspicious of this "bank" and the requests will be blocked.

## Skipping this step

If you don't want to change any settings or don't want to set up any primary user accounts, you can skip this ste by clicking the "skip" button underneath the confirm button.

Be warned, however, that you will not be able to access the admin dashboard until you create a user account.

## Finishing

Once you click confirm, DSJAS will create the account and write the settings to disk. **You have now completed installation.**
