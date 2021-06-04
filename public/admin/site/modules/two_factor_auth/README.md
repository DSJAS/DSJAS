https://youtu.be/CUJJY8LZlS4

# DSJAS-two_factor_auth
A fake two-factor authentication module for [DSJAS - https://github.com/DSJAS/DSJAS/](https://github.com/DSJAS/DSJAS/).
Your passcode is at least 6 digits and ending with zero.

To install:
download the zip file from the releases section, and install it in dsjas admin dashboard under modules. don't forget to enable it.
If the zip fails to install in the web gui, make sure the zip does not hold all files in a single folder in the zip. (files should be at root of the zip).

If you need to change the phone number thats displayed just open chrome developer tools console and execute the below then reload the login page.:
```
localStorage.setItem("dsjas2faphone", "");
```

You can also set your own custom number like this:

```
localStorage.setItem("dsjas2faphone", "3497");
```



