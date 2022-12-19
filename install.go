package main

import (
	"math/rand"
	"net/http"
	"os"

	"github.com/DSJAS/DSJAS/frontend"
	"github.com/DSJAS/DSJAS/install"
)

const (
	SetupTokenFile   = "setuptoken.txt"
	SetupTokenLength = 25
)

// redirectStage redirects the user to the correct stage for their install
// state. Returns true if a redirect was ordered. The request should terminate
// as a result.
func redirectStage(w http.ResponseWriter, r *http.Request, expect uint8) bool {
	if InstallState.Done(expect) || !InstallState.Sufficient(expect) {
		http.Redirect(w, r, InstallState.URL(), http.StatusFound)
		return true
	}

	return false
}

func GenInstallToken() [SetupTokenLength]byte {
	out := [SetupTokenLength]byte{}
	for i := 0; i < SetupTokenLength; i++ {
		class := rand.Intn(3)
		c := byte(0)
		switch class {
		case 0: // number
			nc := rand.Int31n('9' - '0')
			c = byte('0' + nc)
		case 1: // lower case
			nc := rand.Int31n('z' - 'a')
			c = byte('a' + nc)
		case 2: // upper case
			nc := rand.Int31n('Z' - 'A')
			c = byte('A' + nc)
		default:
			panic("install token: invalid random character class")
		}

		out[i] = c
	}

	return out
}

func regenInstallToken() error {
	f, err := os.Create(SetupTokenFile)
	if err != nil {
		return err
	}

	tok := GenInstallToken()

	_, err = f.Write(tok[:])
	if err != nil {
		return err
	}

	return nil
}

func handleInstallWelcome(w http.ResponseWriter, r *http.Request) {
	// To use this page, we must be in StateNone
	if redirectStage(w, r, install.StateNone) {
		return
	}

	cwd, err := os.Getwd()
	if err != nil {
		http.Error(w, "Installer error: Could not determine server root", 500)
		return
	}
	regen := false
	if r.URL.Query().Has("force_regen") {
		// If a forced regen has occurred, we should tell the user
		regen = true
		err := regenInstallToken()
		if err != nil {
			http.Error(w, "Installer error: Could not write setup token: "+err.Error(), 500)
			return
		}
	} else {
		if f, err := os.Open(SetupTokenFile); err != nil {
			err := regenInstallToken()
			if err != nil {
				http.Error(w, "Installer error: Could not write setup token: "+err.Error(), 500)
				return
			}
		} else {
			f.Close()
		}
	}

	AdminTemplates.MustRun("install/welcome.gohtml", w, struct {
		Regenerated bool
		ServerRoot  string
	}{
		Regenerated: regen,
		ServerRoot:  cwd,
	})
}

func handleInstallVerify(w http.ResponseWriter, r *http.Request) {
	if redirectStage(w, r, install.StateNone) {
		return
	}

	var uerr *frontend.Error

	tokf, err := os.Open(SetupTokenFile)
	if err != nil {
		http.Error(w, "Installer error: Setup token file could not be accessed: "+err.Error(), 500)
		return
	}
	defer tokf.Close()

	btok := make([]byte, SetupTokenLength)
	l, err := tokf.Read(btok)
	if err != nil {
		http.Error(w, "Installer error: Setup token could not be read", 500)
		return
	}
	btok = btok[:l]
	tok := string(btok)

	if check := r.URL.Query().Get("verify"); check != "" {
		if tok == check {
			InstallState.Next()
			http.Redirect(w, r, "/admin/install/database", http.StatusFound)
			return
		}

		uerr = &frontend.Error{
			Title: "Invalid token",
			Body:  "The provided verification token was not valid. Please check it is correct and try again.",
		}
	}

	AdminTemplates.MustRun("install/verify.gohtml", w, struct {
		Err *frontend.Error
	}{uerr})
}
