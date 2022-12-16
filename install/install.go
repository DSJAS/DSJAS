// Package install implements the DSJAS installer logic and server-side.
package install

import "github.com/DSJAS/DSJAS/config"

// Required returns true if an installation is required.
func Required(c *config.Config) bool {
	// Always require an install if the config is not present.
	if c == nil {
		return true
	}

	// If the config is blank or config is set to false, require an
	// install.
	if !c.Installed {
		return true
	}

	return false
}
