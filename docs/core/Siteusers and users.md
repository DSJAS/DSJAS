# Site users and users - *core documentation*

In DSJAS, one of the important concepts in the core code is the differentiation between site users and users.

In case you haven't come across this terminology before, I'll offer a brief explanation. DSJAS handles two kinds of users: users of the bank side and admin panel. DSJAS separates the sessions for these two areas of the site in order to allow two independent sessions to be happening at the same time.

## Session organisation

DSJAS uses the PHP session with a suffix system in order to organise information for the two sessions. The suffixes used are:

| Session area | Suffix |
| ---- | ---- |
| Bank | _u |
| Admin panel | _su |

You may have noticed that these match with the terminology of **user** and **site-user**. In session variables, these suffixes are appended to the end of the array key. For example, the key for session state of site users is *loggedin_su* and for regular users *loggedin_u*.

## Drawbacks

However, due to the way PHP sessions work, both are stored in the same session cookie. This means that, sadly, if the *PHPSESSID* cookie is cleared, we loose track of the user's session for both the admin panel and the main bank.

Although this is a major drawback, it can't really be helped unless we switch completely away from PHP sessions. And, sadly, that would make the robustness of DSJAS reduce significantly, as PHP sessions are pretty much guaranteed to work and don't require a database. Even if the database goes down, our sessions should be preserved to give authenticated admins a chance at tackling the problem.
