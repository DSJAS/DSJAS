// Package user implements the DSJAS admin panel users system.
package user

import (
	"database/sql"
	"time"
)

type User struct {
	ID       int64
	Username string
	Realname string
	Email    string
	// In hashed form
	Password string
	Hint     string
	// Translated to DATETIME under the hood
	Registered time.Time
}

// Create inserts the user into the database. If successful, the user's ID
// field is updated to reflect the corresponding database row ID.
func (u *User) Create() error {
	if u.ID != 0 {
		panic("create user: invalid usage: ID is not zero")
	}

	var err error
	var res sql.Result
	if u.Hint != "" {
		res, err = createUser.Exec(u.Username, u.Realname, u.Email)
	} else {
		res, err = createUserHint.Exec()
	}

	if err != nil {
		return err
	}
	u.ID, err = res.LastInsertId()
	if err != nil {
		return err
	}
	return nil
}
