# Outbound Transfers

This module allows users to collect scammer details by replacing the existing "Transfers" navigation with a dropdown. This dropdown enables the scammer to select 'Send Money' in addition to 'Transfer between my accounts'.

Money will be deducted from your account and transaction will show up in the transactions log with a description of ACCOUNT_NUMBER/SORT_CODE/INITIALS.

## Retrieving Scammer details

There are two ways to retrieve the scammers inputs to the form

### Local Storage

The details are captured and stored in the local storage, within a log array created under the key 'logs'.

Go to your console and type the follow to see the stored details

```js
JSON.parse(localStorage.getItem("logs"));
```

### Webhook call back

Additionally you can perform a POST request to a URL of your choosing by adding a localStorage key `transferWebhookUrl` in your browsers console like so:

```js
localStorage.setItem(
    "transferWebhookUrl",
    "http://www.perhaps.discordwebhook.com/somepath"
);
```

A callback would then take place before the main form send to DSJAS with a JSON payload:


```jsonc
// POST:  http://www.perhaps.discordwebhook.com/somepath
{
  "fullname": "Billy Scammer",
  "ban": "6314562345",
  "bsc": "123123",
  "amount": "1",
  "address": "123 A Foul Street",
  "externalAccount": "1",
  "tel": "0188498765",
  "email": "myemail@someprovider.com"
}

```

## Credits

This module has been inspired by https://github.com/GSLevel/outbound-transfers
