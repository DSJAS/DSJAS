# Managing user state - *developer documentation*

In DSJAS, the majority of functionality is centered around user management - in other words, logging in and out.

DSJAS provides two endpoints for achieving this functionality, as well as APIs for querying user state and protecting pages based on it.

## Basics of user state/management

For any given request, there are certain key pieces of info that DSJAS has about the client which sent it. Most of these are stored based on a cookie (or multiple cookies) planted on their device, the main one being *phpsessid*.

The user state information attached to this session ID is the following:

| Info | Data type | Description | Technical identifier |
| ---- | --------- | ----------- | -------------------- |
| LoggedIn | bool | A boolean which indicates if this session token has a login state associated with it. Cannot be accessed by themes or the client (only the server is able to modify this value) | LOGIN_SESSION_STR |
| Username | string | A cached value of the username for the user. In practice, this is only really used in locations such as getting the username on the dashboard, as we don't want to rely on this being up to date | LOGIN_USERNAME_STR |
| UserID | integer | The integer user identifier used to query the database. This **must** be up to date at all times, or we will get the wrong user's info and we will end up with some very bad consequences. Luckily, logging in and out is the only real time in which we will modify this value | LOGIN_USERID_STR |

Although it's useful to know about this, your theme doesn't need to know about or interact with any of this. DSJAS automatically loads and parses the session when a request is made and fills out the required info in memory ready for the theme. From your point of view, the sessions system is just a black box that contains information about the current user.

## Querying user state

### Checking login state

Checking login state can be accomplished using the APIs exposed in the source file **api/theme/Accounts.php**. You can access this API using code similar to the following:

```php
require THEME_API . "Accounts.php";
```

After that, the contents of the API is ready for use.

The function we are interested in is ```shouldAppearLoggedIn();```. This function, as the name suggests, tells your theme how it should appear: logged in or not. The code returns a boolean, which means that you can simply wrap it in an if clause and add custom functionality from there. For example:

```php
require THEME_API . "Accounts.php";

if (shouldAppearLoggedIn())
{
    echo("You appear to be logged in!");
}
else
{
    echo("You appear to logged out!");
}
```

#### Example

The default theme uses this API to show different content on the homepage depending on if the user is logged in. If they are logged in, a link to the dashboard and to logout will be displayed. If they are not, links to login and apply are displayed.

![Displayed when logged out](https://i.imgur.com/C5f9D8b.png "Shown to logged out clients")

The above is shown to logged out clients. The below is shown to clients which are authenticated as a bank user:

![Displayed when logged in](https://i.imgur.com/TIfLzRq.png "Shown to logged in clients")

#### Notes

This isn't designed to be used to restrict pages to users only (that is done using anchor points). However, if you really need to restrict a specific page to logged in users only, you could use this API to quit the theme and display an error. For example:

```php
require THEME_API . "Accounts.php";

if (!shouldAppearLoggedIn())
{
echo ("<p><strong>You are not logged in!</strong> Please <a href='/user/Login.php'>login</a> to use this page</p>");

// When we return from the getTheme function, anything after that line will not be loaded
return;

}
```

In the majority of cases, you will only need to use this to handle showing content such as links to login pages or dashboards, as DSJAS automatically handles redirection based on login state for the majority of anchor points. For example, the login page will automatically redirect users who are logged in.

### Querying user information

DSJAS provides the API ```getDisplayName()``` for your theme to display info based on the user's name.

DSJAS will try several different user data fields to determine which it should return (based on if they are blank or not).

#### Default theme example

In the default theme, we display the user's name in their dashboard with the following code:

```html
<p><strong>Hi there, <?= getDisplayName(); ?>!</strong> Below is your account summary</p>
```

As you can see, we display the user's name, along with other text. This is generally how you will wish to use this API, in locations which refer to the user by name.

In practice, this line will end up looking similar to the following:

```markdown
Hi there, Bob Parr! Below is your account summary:
```

If a real name field is not available, it may look like the following:

```markdown
Hi there, bob.parr#1234! Below is your account summary:
```

## Logging the user in

Logging the user in is the most important piece of functionality that your theme will provide. In order for your theme to work with DSJAS, you will need to follow these rules:

### Login page specification

1. **Login page** Your page **will** override the anchor point *user/Login.php*. This means that the path from your theme directory must be *user/Login.php*
1. **Login form naming/ID** You login form **must** have the ID *loginForm*
1. **Submitting info** All login information **must** be sent using the *HTTP POST* method. It will be submitted to the anchor point URL. **Do not** mix this up with the theme URL. Essentially, the action value for your form will be */user/Login.php*. **Do not** omit the extension
1. **Required fields** In order for the request to be a valid login request, you **must** submit fields with the names *username* and *password*. No other info is required or should be provided. Both fields **must** be sent, or the login request is invalid and will be ignored.

In short, to make a login request, you will submit a POST request to the anchor point */user/Login.php*. This request must contain the POST data fields *username* and *password*, and DSJAS will handle the rest.

### The request

#### Technical specification

* **Endpoint URL:** /user/Login.php
* **Accepted method(s):** POST only
* **Returns:** A status code and a redirect header

| Request field | Field name (case sensitive) | Data type | Required? | Description |
| ---- | ---- | ---- | ---- | ---- |
| Username | *username* | string | Yes | The username provided by the user |
| Password | *password* | string | Yes | The plain-text password provided by the user |

Any other provided fields **will be ignored**. Feel free to provide them, but it's really just a waste of bandwidth - we're not going to use them for anything.

#### The aftermath

After the request goes through, two things will happen:

1. If required, DSJAS will adjust session tokens (or generate them) and change memory cached values to match the new session. Your theme doesn't need to do anything about this and will simply have to submit the required info.
1. A redirect header will be returned (***Location:** /user/Dashboard.php*, for example)

Unless you are doing something fancy with JavaScript or are trying to login the user from a page other than the overridden anchor point, the redirect will work out of the box. This is the reason for the specification stating that you **must send the login request from the overridden anchor point!** Without doing that, the browser will ignore the redirect header from the POST request and you will just be stuck back on the login page without any user feedback.

This redirect either sends the user to a logged in homepage (*/user/Dashboard.php*), or will redirect back to the login page with a GET header indicating a failure and failure reason (your theme can use this later, see **Giving login feedback**).

After you submit the info, you don't need to do anything.

#### Debugging problems

If you are having difficulty with getting your login page to work, try the following debug mechanisms:

* **Browser network explorer** Check in your browser's network explorer (usually under developer tools) and see if the required headers are being sent **and to the right place**. Please also ensure that the browser is correctly identifying that they should be form encoded. If not, set that attribute explicitly using the appropriate HTML attribute
* **Using XDebug and DSJAS server side** You can set XDebug breakpoints and debug DSJAS out of the box. You may wish to set breakpoints on the function ``` shouldAttemptLogin() ```. Step through it and see why DSJAS determined that your request was invalid. You could also just debug the *Login.php* file itself, stepping through each line to see what happens and where the code flows
* **Check the PHP error log** Something might be going wrong internally with DSJAS or your theme on the server side. In this case, you will find details in the PHP error log
* **Check it works with Postman** Postman is an HTTP client which you can use to debug API endpoints. Try sending a POST request to the DSJAS login endpoint and seeing what is returned. If you get a redirect header (**Location:** *somewhere*), you've done it. There should be no content apart from some thing automatically added by the bootstrapper (stylesheet links and meta content).
* **Double check the specification** Double check that you're sending everything to the right place and with the right name. Remember, all the POST fields are **lower case and case sensitive!**

### Getting feedback

You may wish to display feedback to the user once the login request has been processed. For this, you can use the ```shouldProvideLoginFeedback()``` API.

At this point, you've probably got login requests working. After the request goes through, you will have one of two things happen: you will land on the dashboard as an authenticated user or you will be redirected back to the login page.

You may notice that, if the login fails, a GET header called ```error``` will be set by DSJAS. This header is automatically set with any one of the following values if an error occurs:

* **-1** The user entered a username which does not exist
* **-2** The user entered a username which *does* exist, but the password was not correct for that user
* **Something else** A miscellaneous error occurred which prevented the login. The database might be down or DSJAS might have encountered bad input.

When handling this feedback, you can either setup some custom PHP logic to create your own messages, or you can use our preset messages to save you some trouble.

To get the preset messages, use the APIs ```getLoginErrorTitle()``` and ```getLoginErrorMsg()```. These APIs are available in the same file as the rest of the account APIs.

These functions will return *"Login failure"* if an input which is not recognized is provided. So, you will need to check if DSJAS *actually* wants you to provide feedback with the ```shouldProvideLoginFeedback()``` function. Alternatively, you can check for the error GET header yourself.

For example, you might opt to do something like this:

```php
if (shouldProvideLoginFeedback())
{
    $errorTitle = getLoginErrorTitle();
    $errorMsg = getLoginErrorMsg();

    echo("<strong>$errorTitle</strong>");
    echo("<p>$errorMsg</p>");
}
```

This will result in something which looks like this:

```markdown
**Login failure**

There was an unknown error while attempting to sign you in. Error code: -5
```

The message will automatically adjust to the provided error header and will only display if required (due to the check with ```shouldProvideLoginFeedback()```).

## Logging the user out

Logging the user out is, ironically, slightly more complicated than signing them in. In order to be customizable for themes, there are a few pieces of functionality which may slip you up if you aren't careful while adding this to the theme.

### The process

The logout process is more of a multi-step process than logging in. Whereas DSJAS core will automatically handle everything for you in the login process (mainly for security), the logout process will only handle the updating of session tokens and redirection, but all user interaction is handed over to you.

So, here is a breakdown of what happens and what you need to provide/do:

1. **You link to */user/Logout.php* from somewhere in your theme** This could be a link or button on the dashboard, for example. Once the user visits this URL, the process starts
1. **Confirmation/pre content starts** When this page loads, you will notice that nothing happened and any content you provided in the theme is just there, seemingly normally. This is because a specific GET header must be set to trigger the sign out. On this page, you can include a button which confirms the user's wish to sign out or JavaScript which redirects with the required GET header. This GET header will be discussed later
1. **Actual sign out** When the required GET header is set, DSJAS will perform the sign out. It will then redirect to another page with information for your theme to provide rich feedback (similar to the login)

### The logout request

* **Endpoint URL:** /user/Logout.php
* **Accepted method(s):** GET only
* **Returns:** A redirect header which contains a status code

### The magic header

You will have read above that, before the sign out is triggered, you need to send a specific GET header. This get header is, simply, *logout*. It must be set to true (or equivalents, such as 1). When this is present, the DSJAS core will spring into action and perform the logout operation.

We recommend that, throughout your theme, you either link directly to the logout page with the GET header present (AKA */user/Logout.php?logout=1*) or add content which confirms the wish to logout.

### Getting logout feedback

Getting feedback from the logout process is slightly more complicated than in the login process, as the data is sent to two different places.

Logout feedback will be sent to two locations in different circumstances, as a convenience to both you and the user. The specific locations are shown below:

| Location/Endpoint | Condition to be sent | Header/API | Action required |
| ---- | ---- | ---- | ---- |
| **/user/Logout.php** | The logout has completed successfully | Condition can be checked with users API | No, but strongly recommended |
| **/user/Login.php** | The logout failed because the user is not signed in | Condition can be checked with users API | No |
| **/user/Login.php** | The logout failed for an unknown reason | Condition is handled internally and does not need to be handled by the theme | No |

#### The logout feedback API

The logout feedback API allows you to place code at the locations that feedback is sent to and add content to the page depending on what the feedback was.

##### Checking if feedback is required

You can check if feedback is required with the API ```shouldProvideLogoutFeedback()```. This function returns a boolean which tells you if DSJAS has sent logout feedback.

You should place an if clause with this function at each of the locations mentioned above.

##### Getting the logout feedback

The API ```getLogoutFeedback()``` will return an integer which determines what the feedback was from the DSJAS core. The values and associated conditions are stored in constants. Below, the available constants are shown:

| Condition | Associated constant | Friendly name | Real value |
| ---- | ---- | ---- | ---- |
| The logout succeeded | ```LOGOUT_API_SUCCESS``` | Logout API success constant | -1 |
| The logout failed because the user is not logged in | ```LOGOUT_API_FAILURE``` | Logout API failure constant | -2 |
| The logout failed for an unknown reason | ```LOGOUT_API_FAILURE``` | Logout API failure constant | -2 |

You can compare these values to the value returned by ```getLogoutFeedback()``` to determine what feedback should be displayed. For example, in the default theme:

**In the file Logout.php:**

```php
if (shouldProvideLogoutFeedback() && getLogoutFeedback() == LOGOUT_API_SUCCESS) {
    echo ("You were signed out successfully, blah blah...");
}
```

**In the file Login.php**

```php
if (shouldProvideLoginFeedback()) {
    // Some content was here for login feedback
} elseif (shouldProvideLogoutFeedback() && getLogoutFeedback() == LOGOUT_API_FAILURE) { ?>
    echo ("Failed to sign out: You need to be signed in to sign out. Please login first!");
}
```

## Summary

Logging the user in and out, querying user state and retrieving user session information is quite simple - it basically consists of passing GET and POST headers around and listening for events.

Feedback is by far the most difficult thing to get right in this API, but it is also one of the most important things. DSJAS relies on the theme to create a believable experience, so the feedback tools are as rich as they could be and the login method is as similar to real methods as I can make it.
