// Package user implements the DSJAS admin panel users system.
package user

import (
	"database/sql"
	"time"

	"golang.org/x/crypto/bcrypt"
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

	// TODO: Add algorithm to discover a good cost for use, rather than the default
	hash, err := bcrypt.GenerateFromPassword([]byte(u.Password), bcrypt.DefaultCost)
	if err != nil {
		return err
	}

	var res sql.Result
	if u.Hint != "" {
		res, err = createUser.Exec(u.Username, u.Realname, u.Email, string(hash))
	} else {
		res, err = createUserHint.Exec(u.Username, u.Realname, u.Email, string(hash), u.Hint)
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
