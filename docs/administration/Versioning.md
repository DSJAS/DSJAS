# DSJAS version management and release cycles

DSJAS utilizes a trickle-down versioning system to release updates and stay true to a release cycle. Although we cannot guarantee that we will release a new version of DSJAS at any specific time window, we can promise that we will aim to release along these lines.

## How DSJAS releases work

As stated above, DSJAS releases are managed on a trickle-down pattern. But what does that mean?

### Release bands

Essentially, there are three release bands:

1. **Stable:** The main band that most users will wish to be on. Contains tested and tried features which we can state with certainty are stable and usable
1. **Beta:** A secondary band which some users may wish to be on. This band will contain features which are still being worked on (and bug fixes/improvements to those features) and changes which need to be tested before they can be rolled out to all users
1. **Alpha:** Usually reserved to people who aren't using the program for anything serious or developers. This band contains features that we are just getting started working on and which are *probably* broken or unfinished.

On each of these bands, we will release features which will eventually end up in the bands above them. When creating a new feature or making major/possibly breaking changes, we will release onto the lower down bands (AKA alpha and beta) first. Then, when the features have matured, they will be promoted up to the upper bands.

However, sometimes features make their way straight onto the higher bands. When this happens, a release will be pushed out for all three bands which makes the update common for all of them. So, essentially, a stable patch will result in a patch for both alpha and beta.

### Version markers/tags

At any given point, there is a single latest version of DSJAS. That could be v5.0 or v4.6. Whatever it is, there is only one of them. However, not all of the release bands have to be on this latest version. The alpha release, for example, could be on v4.6.7 and the stable could still be on v4.6.0.

This means that the bands, essentially, just act as pointers somewhere into the version history that we (and by extension the DSJAS update client) can use to determine where a given copy of DSJAS should be updated to.

## Version numbering

DSJAS follows the semantic versioning convention in software engineering. Without going into too much detail, the semantic versioning convention splits the version number into three parts.

It's important to note that these three segments of the version number don't correspond to the three release bands. However, it is likely that major versions will be placed in stable, as they mark the point where changes are finished and there are a sufficient number of them to justify a major update.

This, obviously, isn't set in stone, however. A major version update could go to beta first, before a patch release for it being pushed to stable, as all the bugs are worked out. In a similar manor, patch releases are also likely to go to stable, as it's really important that everybody get's security/stability patches as soon as possible. However, as noted before, it's also possible for them to end up in beta due to how the development cycle goes.
