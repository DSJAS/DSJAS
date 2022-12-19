package config

import (
	"fmt"
	"sync"
)

// Config is the JSON-decodable representation of the user's configuration on
// disk. It contains the settings for the core, as well as extensions and
// modules.
type Config struct {
	// If false, the installer will be run on first launch.
	Installed bool     `json:"installed"`
	Database  Database `json:"database"`
	// The publicly visible name of the site.
	Name string `json:"bank_name"`
	// The publicly visible domain of the site.
	Domain string `json:"bank_domain"`
	// If true, access to the admin panel results in a 404
	DisableAdmin bool `json:"disable_admin"`
	// If true, access to the admin panel only results in a 404 for
	// non-login pages when not logged in.
	DisableNoAuthAdmin bool `json:"disable_nolog_admin"`

	// Protects all fields in the configuration. Ignored by JSON loader.
	Mut *sync.RWMutex `json:"-"`
}

// Databse contains the required configuration to connect to and use an SQL
// database.
type Database struct {
	// The hostname of the databse, with no trailing port or leading
	// connection type.
	Hostname string `json:"hostname"`
	// The port on which to connect to the server. If zero, 3306 is used.
	Port int `json:"port"`
	// The name of the SQL databse to select on first connection.
	Database string `json:"databse"`
	// The username of the authorized user with which to connect. Must have
	// permissions to access the databse configured above.
	Username string `json:"username"`
	// The password for the user with `Username` configured above.
	Password string `json:"password"`
}

// Addr correctly formats an address for use in the database dialer.
func (d Database) Addr() string {
	port := 3306
	if d.Port != 0 {
		port = d.Port
	}

	host := "127.0.0.1"
	if d.Hostname != "" {
		host = d.Hostname
	}

	return fmt.Sprintf("%s:%d", host, port)
}
