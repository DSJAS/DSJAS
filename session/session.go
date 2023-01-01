package session

type Session struct {
	// Is this token authorized to complete the install?
	InstallAuthorized bool
	// ID of admin panel user session
	SiteUserID int64
}
