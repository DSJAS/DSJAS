// Package install implements the DSJAS installer logic and server-side.
package install

import (
	"sync"

	"github.com/DSJAS/DSJAS/config"
)

// Possible values for the install state.
const (
	// No stages complete, fresh install.
	StateNone = iota
	// Used has verified their server.
	StateVerified
	// User has installed and established a database connection.
	StateDatabase
	// Install complete and server awaiting restart.
	StateComplete
)

// State represents the current stage of the installer. This is used for
// automatic redirection, as well as for checking that the current stage is
// authorized by the previous ones.
type State struct {
	mut   sync.RWMutex
	stage uint8
}

// Returns the server-relative path of the expected installer stage.
func (s *State) URL() string {
	s.mut.RLock()
	defer s.mut.RUnlock()
	prefix := "/admin/install"
	switch s.stage {
	case StateNone:
		return prefix + "/welcome"
	case StateVerified:
		return prefix + "/database"
	case StateDatabase:
		return prefix + "/final"
	case StateComplete:
		return prefix + "/success"
	default:
		panic("unknown install state")
	}
}

// Next shifts the state into the next state, or sustains the current state if
// it is the terminal state (StateComplete).
func (s *State) Next() {
	s.mut.Lock()
	defer s.mut.Unlock()

	if s.stage < StateComplete {
		s.stage++
	}
}

// Sufficient returns true if the current state is sufficient for the comparing
// state to be accessed.
func (s *State) Sufficient(cmp uint8) bool {
	s.mut.RLock()
	defer s.mut.RUnlock()

	return s.stage >= cmp
}

// Done returns true if the current state indicates the compared stage is
// already complete.
func (s *State) Done(cmp uint8) bool {
	s.mut.RLock()
	defer s.mut.RUnlock()

	return cmp < s.stage
}

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
