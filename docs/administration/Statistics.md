# The Statistics System - *user manual*

> **Help with "statistics unavailable" errors** Please scroll to the section on common errors and problems. For a quick solution, ensure that your database is up, enabled and functioning correctly. Please also ensure that there are no flags enabled in your bootstrap script which disable statistic logging

The DSJAS statistics system works in a similar way to many analytics platforms, with a similar goal. Effectively, the statistics system is designed to log and provide a summary of key events which took place during a period of usage of the site. These events are usually key markers of site usage, such as transactions and number of logins, with the end goal being that a user can look back at their session's statistical summary and see interesting or satisfying facts about this session that DSJAS has recorded.

In this section of the user manual, we will outline

1. How to use the DSJAS statistics system
1. How to administer the DSJAS statistics system

## How can statistics help me?

It is a common wish shared amongst many of the people who spend their free time messing with scammers to look back and see how much time they either wasted or made difficult for them. I can say for sure that, in the past, I have wanted to go back and look at how many minutes of time we spent trying to bypass the inspect element prevention or yelling at the "simple captcha". To solve this problem, I would usually just use either a stopwatch or a clock application on the computer. But, this has its downsides. Not only do I have to manually start and stop it whenever I wish to switch calls/scammers, it also had no way of logging different events that took place during the scam.

So, just as I did with the original idea for DSJAS (a bait bank that can dynamically change the way it looks), I started writing and designed a solution.

DSJAS statistics are like that little stopwatch I used, but on steroids. The DSJAS statistics system will track how much time you spent on the site, how much time in a bait in total, as well as how many total page hits to the bank, admin dashboard and transfer pages there were. It can also track how much "money" was "transferred" and how many dollars you have "lost". Basically, it's just like that stopwatch, but if that stopwatch could count hundreds of other things at the same time (and to it automatically).

Ok, but what does it actually do? The statistics system, basically, lets you tell it when you are starting a scambaiting session. When you do this, it will reset all its statistics back to zero and begin counting again. From that moment on, whenever a page loads or an action takes place in DSJAS, it will place some code on each page to track what is going on. At the end of everything, you tell DSJAS your session is over (the scammer hung up, you revealed what is going on, etc.), and it will give you an overview of everything that went on, along with some handy summary to look over.

## Getting started with statistics

Getting started with statistics is simple. In the admin dashboard, click on the sidebar tab labelled "Session statistics". This will take you to the statistics dashboard. By default, there will be no session running and no statistics stored. To start a session and the recording of statistics, click the green "Start" button.

Now, you can go about your day, baiting scammers. Once you have finished (either overall or with a particular scammer, depending on what statistics you want), navigate back to the same page and click the red "Stop" button. The statistics will now be in a "frozen" state: they are viewable and the overview is available, but the site is not updating anything. Once you have finished marvelling at your own prowess in scambaiting and just how many dollars you have been "scammed out of", you can click the blue "Clear" button. This will erase all the statistics from the current session and ready you to start the next one.

## Theme statistics

To provide a rich and extensible experience in statistics, DSJAS exposes a system through which themes can provide you will statistics specific to their pages/workings. For instance, a theme may wish to provide statistics on how many times a scammer clicked on a fake link or visited a fake offer page.

In the default theme, statistics are logged which show how many times the scammer was tricked into visiting the "CP Rewards" page.

Each theme has full control over the statiscics, the type of data they store and when/where they are added, providing a truly extensible experience.
