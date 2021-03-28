# The Comprehensive Guide to DSJAS URL Spoofing - *user manual*

This section in the user manual outlines how the URL of the DSJAS site can be spoofed on the client side with the simple editing of a configuration file. This is useful to conceal the fact that DSJAS is simply a local site and make it much less obvious and easy to spot.

## Usage

Let's be real for a moment: DSJAS, out of the box, is easy to spot. Not only does the bank come installed by default with the name "DSJAS", the URL is also an incredibly obvious value of either "127.0.0.1" or "localhost". Now, I don't know about anybody else, but my online banking is not located at "localhost" or a raw IP address.

Luckily, there is a solution in the form of hostname spoofing (also known as URL spoofing).

## Workings

In most operating systems, a system file will exist called "hosts". This file is the hosts override file, which will be consulted by the operating system when it looks up a hostname with DNS. If the URL/hostname which a program is loading is contained within the file, the contents of the hosts override file will instead be used.

### Example

In this example, we will map "dsjas.bank" to the local DSJAS domain.

```conf
127.0.0.1   dsjas.bank
```

As you can see, the IP address of the location to be mapped is placed first in the file. Then, the domain which we wish to map to is placed second, optionally after some whitespace.

**127.0.0.1** is the IP address of the computer you are currently running on, and will work on any machine.

## Hosts file setup

The hosts file will be located in different places and formatted in different ways depending on your system setup and/or operating system. On UNIX-based OSs (Linux, BSDs, MacOS), it will be all the same. On Microsoft Windows, however, things will be a little different. Be sure to follow the correct instructions for your system.

### Microsoft Windows

On Windows, the hosts file is located at **C:\Windows\System32\drivers\etc\hosts**.

In this file, there will be some lines beginning with a hashtag. Ignore these lines and **do not edit or remove any lines already in the file!** These have been added by existing programs on your system and **must not be removed.**

On my Windows box, the hosts file looks like this:

```conf
# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
#    127.0.0.1       localhost
#    ::1             localhost

# Added by Docker Desktop
127.0.0.3   docker.local
```

To add DSJAS, simply append the lines underneath, changing it to whatever domain you prefer to map to. For instance, my file would then look like this:

```conf
# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
#    127.0.0.1       localhost
#    ::1             localhost

# Added by Docker Desktop
127.0.0.3   docker.local

# DSJAS Domain Mapping
127.0.0.1   dsjas.bank
```

### UNIX-based OSs (GNU-Linux, BSDs, MacOS)

On UNIX-based systems, the hosts file will be located at **/etc/hosts**. This file will be formatted in a similar way to the Windows one, but with a bit less fluff and some small syntax differences.

In this file, there will be a list of IP addresses and their mapped domains. On most systems, it will come pre-configured with a rule for the domain *localhost* and your system's hostname. It may also contain rules set up by other applications. **Do not edit or remove these rules!** They are used by other things on your computer and should not be tampered with.

For instance, the hosts file on my main Linux machine looks like this:

```conf
# Static table lookup for hostnames.
# See hosts(5) for details.

127.0.0.1    localhost
::1          localhost

127.0.1.1    core.localdomain    core
```

To add the DSJAS rule, we need to add a line containing the rule. It could look something like this:

```conf
# Static table lookup for hostnames.
# See hosts(5) for details.

127.0.0.1    localhost
::1          localhost

127.0.1.1    core.localdomain    core

# DSJAS Domain mapping
127.0.0.1    dsjas.bank
```

Obviously, as with before, "dsjas.bank" can be replaced with whatever text and it will be resolved just the same.

## DSJAS setup

DSJAS does not require any specific setup based on the hostname, but it can optimize and change some visual effects to reflect the domain which has been set (for instance, by dynamically changing the domain in links or email addresses).

In the general settings tab in the admin dashboard, change the "Bank domain" setting value. Remember to save your settings!

## Testing the configuration

To test if the configuration has worked, simply fire up your favorite browser and navigate to the domain which you have just configured. It should map to DSJAS. If not, see troubleshooting directions below.

## Troubleshooting

1. **Did you save the file? Verify your changes are still present** Re-open the hosts file and make sure that the domain you added is still present. If it is not, the issue may have been caused by the fact that you **must be administrator** to edit the hosts file
1. **Is the webserver actually running?** You still need to have a running server to serve the site
1. **Have you configured the right machine?** It sounds stupid, but the hosts file only affects the current machine. Did you configure the server by accident?
1. **Is DSJAS installed?** Did you remember to run the installer? You need to.

## Final notes

One of the main pitfalls that people tend to fall victim to when using this method is either incorrect syntax in the hosts file (just follow the instructions carefully), not finding the hosts file (just copy the provided path into your favorite editor) or configuring the wrong machine (configuring the host not the VM, or vice versa). And, I can absolutely see why, as the process isn't the most intuitive in the world.

However, once set up correctly, hostname spoofing can be one of the most vital camouflage features of the DSJAS site.
