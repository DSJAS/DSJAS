# Login - *DSJAS Module API Documentation*

* **API Name:** Login
* **Description/use:** Used for managing utilities on the login page
* **File Name:** Login.js
* **Anchor point specific?** Yes - "Login.php"
* **Exposed object:** Under ``dsjas.login``

> **Note:** This API file contains some internal functions used by the API. For fine-grained control over the API process, you *can* call these functions, but it is **highly discouraged** - as they are not standardised and their behaviour is strictly internal. **Internal functions are intentionally left undocumented.**

## ```addCallback()```

Adds a callback to be executed on the login form being submitted. Multiple callbacks can be hooked at once and will be executed in order of being added.

Callbacks are regular functions which will be executed normally and will return with **no further action taken**. The login form will **not** be submitted on the function returning. No further action will be taken until the function ```callbackYield``` is called, which notifies the API that it can either call the next callback or submit the form if there are no callbacks remaining. Alternatively, you can call the function ```callbackEnd```, which will forcibly end the chain of callback executions, preventing any further from running.

The second parameter to this function relates to if the callback should be called before or after the credentials are verified. If true, the callback can be guaranteed to only be called if valid credentials are supplied. This is useful, for instance, for a 2FA module which wishes to only appear to be authenticating valid logons.

* **Parameter - ```callable callback```:** The function to be executed upon form submission
* **Parameter - ```boolean needsCorrect```:** Should only be called when credentials were verified
* **Returns:** ```undefined```

## ```callbackYield()```

Notifies that the current callback has completed execution and is ready to hand off control to either the next one or the API for form submission preparation.

* **Parameters:** None
* **Returns:** Will not return, if successful

## ```callbackEnd()```

Ends the chain of callback execution and passes back control to the form control code. No more modules will have their callbacks executed after this call and the login form will be immediately submitted.

* **Parameters:** None
* **Returns:** Will not return, if successful

## ```callbackReset()```

Resets the callback processing system back to the original state, where the chain has not yet executed any callbacks. This will result in the callbacks all being called from scratch if the form is submitted again.

* **Parameters:** None
* **Returns:** ```undefined```
