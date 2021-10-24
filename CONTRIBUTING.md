# DSJAS Project Contribution Guidelines

Hello! Welcome to the DSJAS Contribution Guide. This guide aims to outline the key rules and guidance for contributing to DSJAS and ensuring that your bug report or contribution can be processed as quickly as possible.

DSJAS is authored in PHP and is designed to function with the Apache Webserver, using a MySQL backend database. This means that, to contribute, you *must* have at least these tools installed. Further, you are required to have at least a basic knowledge of website programming and PHP in order to contribute code, although reporting bugs does not require any kind of technical knowledge. If you are contributing in docker, we recommend that you use the devcontainer Dockerfile, rather than the main one, as this is better suited to realtime development, as it does not require any copying of source trees and simply makes use of mounted folders.

## Getting started

To get started contributing, you should clone the site from the ``master`` branch and follow the instructions for how to set up a developer install - available in the *install* section of the documentation.

## Reporting an issue

If you are reporting an issue to us, please ensure that you have first:

* Read the FAQ document
* Checked the documentation for a simple solution

The documentation is organised into sections which deal with many different categories of parts to the site. If you wish to find a solution to your problem, your first stop should be the documentation.

If you are still not sure of a solution after checking the documentation, feel free to open an issue. For your issue to be dealt with as quickly and easily as possible, please include the following data (if possible):

* PHP, Apache and SQL server version
* Browser version and type (Chromium, Firefox, Safari etc.)
* DSJAS version and band

If applicable, it is also useful to properly label your issue, as this will allow developers working on specific parts of the site to pick out your issue relating to their work. This is particularly useful for, for instance, a certain builtin or section of the core.

During the diagnosis of your problem, you *may* be asked for the following information:

* Your full DSJAS config
* A data dump of your database
* A snapshot of your admin data
* Information about custom extensions installed

If you are not comfortable providing this data, feel free to redact it to an extent, but this may make the pinpointing of your issue more difficult. Of course, you are not obliged to provide any of this data, but it **will** make the finding and fixing of whatever problem you have easier. If we are able to confirm that what you have reported is a bug, your issue will be left open until a commit has been merged or pushed to master which fixes this issue, at which point the developer responsible will leave a comment outlining what was changed and how it should fix your issue. At the next release of the program, you can expect your problem to be resolved. If this is not the case, you should re-open your issue and mention the developer who was responsible for the fix to inform them that this did not work.

Issues which are requesting new features should be as specific as possible as to what the feature would be, why it would be useful and any possible technical details as to how it could be implemented (again, only if possible).

Any and all reports of issues are welcome and will be read in due course. Thanks for your assistance making DSJAS better!

## Submitting Pull Requests

All pull requests should contain a summary of what was changed, why this change is justified and any technical details about impacts of the change. In order to make your changes more likely to be merged, make sure your commits are all properly labelled, and remember: a good commit title is worth a thousand commit descriptions. Be brief and clear.

As a general rule, we do not want to add any unnecessary dependencies to DSJAS, such as third-party libraries or overly complicated frameworks. However, if you do *absolutely need* to add a library or framework, please add it as a Git submodule and place the root in the "public/include/vendor" directory.

Adding builtins is usually not accepted, unless there is some strong reason as to why this should be included by default. Remember: with each builtin which is added, more ideas are taken away from the community and added to an already large codebase. Each builtin which is added is another to maintain, both by us and by you. Similarly, each builtin added is a larger download or update for our users. So, it is often better to keep these in separate repositories and to leave them for users to install and for the developer to maintain. I cannot maintain 500 builtins at once: we are a single project, not a packaging repository.

Changes to APIs must document if they are breaking or not. Any breaking changes need to be published clearly in the next patch log in the update patch notes. Also, all builtins must be patched to work accordingly. Builtins should exemplify current API usage standards and idiomatic DSJAS API code. We ought to keep them as up-to-date as possible so they can serve as an example and a strong default.

## Summary

In summary, thanks a lot for helping us keep DSJAS working well and doing its job to foil scammers' plots. It is because of the network of people willing to report issues with DSJAS and contribute new code that DSJAS remains alive and working well. Please never be afraid to contribute anything you consider of value, as we won't hesitate to take a look.

Thanks and happy scambaiting.
