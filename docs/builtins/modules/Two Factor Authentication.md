# Two Factor authentication

> Community made module contributed by [SinisterSpatula](https://github.com/SinisterSpatula) - please see [here](https://youtu.be/CUJJY8LZlS4)

    In order do protect your account from fraud and theft, DSJAS now requires mandatory two factor authentication for all customers.

## About

Two Factor Authentication is another example of how DSJAS's minimal login process can be a good place to build for modules and other extensions. This module takes advantage of the login process to add a popup which requires the user to, firstly, select their preferred 2FA method and, secondly, enter a "verification code" to prove their identity.

This module tends to focus much more on realism than modules such as "Frustrating Login Process", thereby being a good option if you want to make the login process a bottleneck to waste scammer time, but don't want to risk some of the over-the-top hijinks as introduced by such modules as "Frustrating Login Process".

## What does this module do?

This module adds a popup to the login screen which will appear before the form can be submitted. You will be require to complete the "verification" before logging in.

These popups can be guarunteed to be dismissed using the instructions below.

## How do I use this module?

This module will be applied automatically to the login screen, and will be triggered when the login form is submitted.

### Passing verification

Regardless of whether you selected to be phoned or texted in the first phase of the popups, you can pass verification by ****entering a code of 6 characters or more which ends with a zero****.

### Overriding the telephone number

By default, the "verification" telephone numbers are randomly generated. To override them, you can use this code in the developer console:

```js
localStorage.setItem("dsjas2faphone", "<INSERT PHONE HERE>");
```

You can also tell DSJAS to reload the number by setting the phone to an empty value, like so:

```js
localStorage.setItem("dsjas2faphone", "");
```

## Possible giveaways

Obviously, this module **does not** communicate with any telephones. Therefore, it is completely on the user to act out having recieved a telephone call or text message.
