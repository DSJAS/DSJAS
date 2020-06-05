# A slightly intrusive advertising campaign

    These days, with the high operating costs of a website, online banking just isn't free. Luckily for you, you don't have to pay, because DSJAS now offers an ad supported method

## About

> **Important:** We don't actually make revenue from these advertisements. This is just designed to be a joke to annoy the scammers as much as possible. DSJAS is **not** advertisement supported and will always be free, without such scummy monetization methods.

DSJAS's services and online banking has always given the impression that the service is provided for free. So, it might be good for your bank to be able to offset the costs of this by adding advertisements which pop over content, disabling the site for 30+ seconds while and ad plays.

When DSJAS determines that an advertisement should be played, a full screen popup will display showing an advertisement and a countdown. You will only be able to dismiss the popup when the timer reaches zero (or when you type ```closeAdvert()``` in console).

## What does this module do?

This module will run when the page is loaded in a browser. A random amount of time is generated using your browser and displayed in console. If the popup doesn't appear to be opening, don't worry: you probably just have got an unfortunately long random time generated. The maximum time is thirty seconds.

When that time is reached, a popup will open, stopping any other areas of the page from being interacted with. After the popup has opened, a timer will begin counting down from thirty seconds. A random advertisement video from YouTube will be displayed.

> **Note:** You need to click on the video player to start the ad playing. Due to YouTube's internal workings, we are currently unable to auto-play the video without causing the advert to open as the page loads.

When the timer reaches zero, the countdown will be replaced with a *Skip ad* button. Click this to dismiss the advert.

Below is an example of one of the three adverts which can be displayed:

![Mafia city advertising campaign](https://i.imgur.com/xW737I3.png "Advert popup when timer is counting down")
![Mafia city advertising campaign - skipped](https://i.imgur.com/YjdHW8E.png "Advert popup when timer has reached zero")

You can see that the popup will initially be created with a countdown - this is shown in the first picture. In the second picture you can see what will be displayed when the timer reaches zero.

## How do I use this module?

This module will automatically be placed on **all** bank pages. This does not include certain special pages (such as the error page or any admin pages).

There are certain tricks and hidden features you may wish to know about before using the module.

### Advert campaign variants

#### SquareSpace

A rather infamous SquareSpace advert from YouTube will be loaded. This advert was specifically selected to be a weird as possible. The weirdest candidate we could find was one where characters from Sesame Street become art professionals by selling garbage to collectors (*I never thought I would type that into a computer*).

This advert's length is much longer than the countdown.

**Countdown length:** 30 seconds
**Video length:** 4+ minutes

#### Raid shadow legends

Yes, everybody's favorite mobile game is a sponsor of *[insert bank name here]*. Now you can play as a brand new hero and unlock special advantages over other players. Download for free today...

You know the drill

A random Raid Shadow Legends advert will be selected.

**Countdown length:** 20-30 seconds
**Video length:** Varies with video, usually around 30 seconds

#### Mafia City

You can probably guess why this is here. In case you don't, Mafia City is as somewhat poorly made mobile game which is known for its over the top advertisements which don't actually relate in any way to real gameplay. For this reason, it has become a bit of a running joke among internet users and an example to mobile game companies of how not to make their advertisements.

So, of course, your bank is now sponsored by them!

Either:

1. A random Mafia City ad will be loaded OR
2. The hour-long compilation of all ads

**Countdown length:** *EITHER* the length of the advert (usually around 20 seconds) *OR* 30 seconds for the hour-long compilation

### Quick cancel

If you really need to cancel the advert and remove it immediately, you can paste the following in console:

    closeAdvert(true);

Pasting this in console will result in the video being stopped and the popup being dismissed.

### Resummoning advertisements

> **Important:** This module was not designed with the possibility of reopening the advert in mind. Some adverts may work incorrectly with this or may have issues loading. Please only do this if you need a quick distraction

Type/paste in console

    triggerAdvertisement(); mafiaCity()

You can replace ```mafiaCity``` with the name of the advert campaign. The names of the campaigns are:

1. mafiaCity
1. squareSpace
1. raidShadowLegends

> **Note:** This is JavaScript code, which shouldn't be edited unless you know how to program in JS. Don't touch the brackets or semicolons. You are best just pasting in the code.

### Gauging time to load

You can determine the time left until an advert will be displayed by looking in the console for a message which looks like the following:

![Time to advert](https://i.imgur.com/XgfOdhW.png)

The number is the number of seconds until the advert will be triggered. This will only be printed once and cannot be retrieved after the console is cleared.

## Possible giveaways

This module adds a lot ouf output in console in order to feedback to you if you need info. For instance, the text ```Intrusive advertising module loaded``` will always be displayed at the top of the console window, along with a copyright message.
The time to an advert being loaded is also displayed at page load in console.

The adverts can sometimes look a little over the top or annoy scammers to the point of them hanging up. Be aware of that.

Finally, the adverts could look unprofessional to somebody who has been doing a lot of creating popups. They are just a YouTube video after all!
Maybe don't try this on popup scammers...
