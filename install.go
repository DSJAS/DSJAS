package main

import (
	"math/rand"
	"net/http"
	"os"
)

const (
	SetupTokenFile   = "setuptoken.txt"
	SetupTokenLength = 12
)

func regenInstallToken() error {
	f, err := os.Create(SetupTokenFile)
	if err != nil {
		return err
	}

	out := [SetupTokenLength]byte{}
	for i := 0; i < SetupTokenLength; i++ {
		nc := rand.Int31n('z' - 'a')
		c := byte('a' + nc)
		out[i] = c
	}
	_, err = f.Write(out[:])
	if err != nil {
		return err
	}

	return nil
}

func handleInstallWelcome(w http.ResponseWriter, r *http.Request) {
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
	}
	// If this is the first time, don't bother
	if f, err := os.Open(SetupTokenFile); err != nil {
		err := regenInstallToken()
		if err != nil {
			http.Error(w, "Installer error: Could not write setup token: "+err.Error(), 500)
			return
		}
	} else {
		f.Close()
	}

	err = AdminTemplates.Run("install/welcome.gohtml", w, struct {
		Regenerated bool
		ServerRoot  string
	}{
		Regenerated: regen,
		ServerRoot:  cwd,
	})
	if err != nil {
		w.Write([]byte(err.Error()))
	}
}

func handleInstallVerify(w http.ResponseWriter, r *http.Request) {
	w.Write([]byte("verification"))
}
