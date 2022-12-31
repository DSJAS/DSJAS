package user

import "database/sql"

// Prepared statements texts.
const (
	createUserTxt     = "INSERT INTO `users` (username, real_name, email, password_hash) VALUES (?, ?, ?, ?)"
	createUserHintTxt = "INSERT INTO `users` (username, real_name, email, password_hash, password_hint) VALUES (?, ?, ?, ?, ?)"
)

// Prepared statements.
var (
	createUser     *sql.Stmt
	createUserHint *sql.Stmt
)

func Prepare(db *sql.DB) (err error) {
	createUser, err = db.Prepare(createUserTxt)
	if err != nil {
		return
	}
	createUserHint, err = db.Prepare(createUserHintTxt)
	if err != nil {
		return
	}

	return nil
}
