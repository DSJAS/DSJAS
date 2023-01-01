package session

import "net/http"

type Session struct {
	// Is this token authorized to complete the install?
	InstallAuthorized bool
	// ID of admin panel user session
	SiteUserID int64
}

// WriteSession sets up session cookies for the given ResponseWriter by writing
// the needed cookies. Session cookie tokens are given the lifetime of
// SessionTokenLifetime.
func WriteSession(w http.ResponseWriter, id string) {
	http.SetCookie(w, &http.Cookie{
		Name:   SessionTokenCookie,
		Value:  id,
		MaxAge: int(SessionTokenLifetime.Seconds()),
	})
}
