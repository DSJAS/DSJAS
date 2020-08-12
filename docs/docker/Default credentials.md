# DSJAS docker default credentials - *user manual*

When building the DSJAS docker images, we automatically generate accounts for both DSJAS and your database. The accounts created are shown below. You will be able to authenticate to the DSJAS website using the default admin account and the default edna account.

## Bank user: Edna

| Account name | Default user ID | Username | Password |
| ---- | ---- | ---- | ---- |
| Edna | 1 | edna | hunter2 |

## Admin user: Administrator

| Account name | Default user ID | Username | Password |
| ---- | ---- | ---- | ---- |
| Administrator | 1 | admin | DSJAS1234 |

## Database accounts

> **Important:** If you need access to the database, the randomly generated root password is displayed the first time the container is run **only**. You may wish to note it down so that you can use it later as this is the only time it will be given out.

| Username | Password | Scope | Domain |
| ---- | ---- | ---- | ---- |
| DSJAS | DSJAS-Default-Password-1234 | Full administrator access to the DSJAS database **only** | All |
| root | *A random password is generated at first launch* | Full administrator access to all databases | localhost |
