# Error - *DSJAS Theme API Documentation*

* **API Name:** Error
* **Description/use:** Used for correctly implementing a custom error page
* **File Name:** Error.php
* **Full Path:** /include/api/theme/Error.php
* **Anchor point specific?** Yes
* **Provides/exposes:** Convenience/abstraction functions

## ```getErrorCode()```

> [!IMPORTANT]
> This function automatically processes/escapes user-provided data for XSS prevention. This means that the data returned can be treated as safe.

Returns an string representation of the HTTP error code which was raised, either by DSJAS load code or by the webserver itself.

* **Parameters:** None
* **Returns:** ```string``` The string representation of the HTTP error code

### Example

```php
$errorCode = getErrorCode();

switch ($errorCode) {
    case "404":
        echo ("The requested page was not found");
    case "403":
        echo ("Access to the requested page was forbidden");
    case "500":
        echo ("Something went wrong internally!");
    default:
        echo ("There was a problem handling your request");
}
```
