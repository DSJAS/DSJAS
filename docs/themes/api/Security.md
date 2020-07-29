# Security - *DSJAS Theme API Documentation*

* **API Name:** Security
* **Description/use:** Used to allow themes to interact with the DSJAS anti-CSRF systems
* **File Name:** Security.php
* **Full Path:** /include/api/theme/Security.php
* **Anchor point specific?** No
* **Provides/exposes:** Core layer interaction functions

## ```getCSRFForm()```

> [!IMPORTANT]
> This function **does not** return a value, but outputs directly to the client. Do not make the mistake of outputting the return value of this function.

Outputs the CSRF form element which can be used to interact with the DSJAS anti-CSRF token systems. This is required on some forms to fit the specification. For more information on if a form needs to be CSRF secured, please refer to the documentation for that specific anchor point.

* **Parameters:** None
* **Returns:** ```void``` - however, does produce client output

### Example

```php
<form>
    <?php
        getCSRFForm();
    ?>
</form>
```
