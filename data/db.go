package data

import (
	"database/sql"
	"errors"
	"time"

	"github.com/DSJAS/DSJAS/config"
	"github.com/DSJAS/DSJAS/data/user"
	"github.com/go-sql-driver/mysql"
)

// Database connection or usage errors.
var (
	ErrBadConfig  = errors.New("invalid database config")
	ErrConnection = errors.New("database connection failed")
)

// discardLogger is used to tell mysql to shut up about errors we can handle
// manually.
type discardLogger struct{}

func (d *discardLogger) Print(v ...any) {}

// ConnectionError is returned from NewDatabase and unwraps to a config error
// or a connection error.
type ConnectionError struct {
	unwrap, detail error
}

func (c ConnectionError) Error() string {
	return c.unwrap.Error() + ": " + c.detail.Error()
}

func (c ConnectionError) Unwrap() error {
	return c.unwrap
}

// Database holds references to the current instances of the database
// connection and the GORM engine.
type Database struct {
	conn *sql.DB
}

func NewDatabase(cfg config.Database) (*Database, error) {
	dbcfg := mysql.Config{
		Net:                  "tcp",
		Addr:                 cfg.Addr(),
		User:                 cfg.Username,
		Passwd:               cfg.Password,
		DBName:               cfg.Database,
		ReadTimeout:          15 * time.Second,
		WriteTimeout:         15 * time.Second,
		RejectReadOnly:       true,
		AllowNativePasswords: true,
		CheckConnLiveness:    true,
	}
	mysql.SetLogger(&discardLogger{})

	db, err := sql.Open("mysql", dbcfg.FormatDSN())
	if err != nil {
		return nil, ConnectionError{ErrBadConfig, err}
	}
	err = db.Ping()
	if err != nil {
		return nil, ConnectionError{ErrConnection, err}
	}

	return &Database{db}, nil
}

// InitPrepare initializes all known prepared statements from the data package
// and returns any errors encountered.
func (d *Database) InitPrepare() error {
	if d.conn == nil {
		panic("database: InitPrepare called before NewDatabase")
	}

	if err := user.Prepare(d.conn); err != nil {
		return err
	}

	return nil
}

func (d *Database) Close() error {
	return d.conn.Close()
}
