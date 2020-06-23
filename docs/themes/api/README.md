# DSJAS theme API - *developer documentation*

DSJAS themes have access to the PHP programming language in its entirety. In fact, all the code in your theme **will** be run, no matter what. So you have no real restrictions on your interaction with the user or server (please see the disclaimer on how to use this responsibly).

However, when it comes to interactions with DSJAS itself, your theme is woefully unequipped out of the box.

Luckily, the DSJAS API exists.

The DSJAS API is a set of functions, protocols and chunks of code which allow you to interact with DSJAS and the many features it offers with a simple function call or constant insertion.

For example, do you want to get the current user's bank accounts to display? No problem! Just do something like this:

```php
require(THEME_API . "Dashboard.php");

$accounts = getAccountsArray();
foreach ($accounts as $account)
{
    echo("This user's account name is " . $account["account_name"] . " and contains " . $account    ["account_balance"] . "in funds");
}
```

The above code should **actually** work in a production theme and is similar to the way things are done in the default theme (obviously with a little more styling and actual content).

If that seemed simple, that's because it is. The theme API provides all the tools you will need to make a truly dynamic and immersive theme which can truly feel like a real bank.
