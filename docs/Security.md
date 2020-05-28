# DSJAS Security Policy

The security of our users is important to us. DSJAS should never be able to be used as an exploitation vector or entry point into the computer/network of a user. For that reason, we take security very seriously.

However, in order to both prioritize the most important security vulnerabilities and exclude those which are not considered threats, we are forced to stick to a strict security policy.

Web applications are one of the most open-to-vulnerability programs on a computer - and we realise that. So, patching security bugs will be our utmost priority.

## How we deal with security reports

When we receive a security report, either from a community member, maintainer or other user of the site, we will immediately place it in a queue for testing.

> **Note:** If the bug is immediately apparent (or enough community members vouch for the validity of the problem), your report may go straight to the patching phase

The next step is usually to draft a patch. The initial draft is then reviewed by one or more maintainers. After this has happened, a security advisory will be published to the GitHub repository, along with the final draft of the patch.

We will usually bundle as many security patches as possible in a single release. This ensures that users can be as fully secured as possible, while only having to update once.

## How to report a security problem

> **DO NOT** post your security report as a GitHub issue or a fix as a pull request. Security issues should be discussed *in private*. The point when they go public is when the security advisory goes public.

We have several methods to contact us in the case of a security report. Please email our anonymous, encrypted email at *[DSJAS.security@pm.me](mailto:DSJAS.security@pm.me)*. Your communication will be handled as soon as possible. Please include as much information about the problem as possible.

It's worth mentioning that we don't always reply to security notices. You will be notified once the bug is either confirmed or patched, however.

Finally, thanks for expressing your interest in keeping DSJAS secure. Without community members like you, DSJAS would never be as secure as it could be.

## Report conduct

Please follow the following simple rules when reporting a security vulnerability:

1. **Don't publicize the vulnerability, except to us** We need time to work on a patch for the problem, and we will need that time to remain free of exploits in the wild. Don't tell anybody at all about the exploit until an advisory has been filed.
1. **Don't report the same bug more than once** We will already be dealing with duplicates; reporting more than once will, if anything, make it more difficult for us to work on a patch because we are busy reading your duplicate emails. If you don't receive a response for an extended period of time, just wait and you will be got to eventually.
1. **Do include as much info as possible** We need information to patch. A vague description may lead to your report being discarded or rejected due to us not being able to identify what the actual problem is.
1. **Do tell us exploit methods, code or requirements** We need to know the exact circumstances that go into causing this exploit. This will allow us to patch it far quicker
1. **Don't over explain** We read all reports in their entirety. If you over explain, you may have us missing the point of your report or having difficulty extracting the key pieces of information. Please be concise but descriptive.

## What constitutes a security problem?

It's important to not that not all problems with DSJAS should be reported to the security email. **Please only report security vulnerabilities to this email!**

However, further from that, we may eliminate some "vulnerabilities" to save the time of the developers and maintainers.

In general, our security philosophy is the following:

    If a user can be exploited by just running DSJAS on their computer, we will treat this as a severe security vulnerability

The meaning behind this is that vulnerabilities which require the user to intentionally break something, can only be exploited if DSJAS is hosted remotely or something similar which goes against the design principles that DSJAS is built on may be considered less important than some others. *That **does not** mean they will not be considered or patched*.

In addition, if we inspect the vulnerability and consider it to be of little use to an attacker, we may postpone patching it or not patch it outright until another date.

If your report has been rejected or postponed, you will be notified.

**However** It's important to note, also, that we will patch *all valid* vulnerabilities at some point. All reports will be read by a human being.

## Final words

Thanks for reading our security policy! The fact that you're here means that you either care about your security or about DSJAS and the security of other users. For both of those things, thanks for being a great community member and *things can only get better.*
