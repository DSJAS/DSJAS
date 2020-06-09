# DSJAS Theme Specification - *developer documentation*

DSJAS themes give a lot of freedom to the developer. However, there still needs to remain a way for DSJAS to communicate with the theme (and vice-versa) and (just as importantly) for modules to communicate with themes.

In order for your code to link up with DSJAS properly, you have the *specification*. This developer spec contains the instructions on how to tell DSJAS what to do, submit information for processing and how you should make it as easy as possible for modules to work with you.

## Table of contents

| Page | Summary |
| ---- | ------- |
| Structure of a theme | What files are required and what your theme needs to do out of the box |
| "Anchor points" | How DSJAS provides endpoints for your theme to submit information on the behalf of the user |
| Logins and logouts | The DSJAS login/out API and endpoints for themes |
| Transactions/transfers | The DSJAS transaction API and endpoints for themes |
