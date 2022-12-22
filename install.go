package main

import (
	"database/sql"
	"encoding/json"
	"math/rand"
	"net/http"
	"os"

	"github.com/DSJAS/DSJAS/config"
	"github.com/DSJAS/DSJAS/frontend"
	"github.com/DSJAS/DSJAS/install"
	"github.com/go-sql-driver/mysql"
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
			os.Remove(SetupTokenFile)

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

func handleDatabaseSetup(w http.ResponseWriter, r *http.Request) {
	if redirectStage(w, r, install.StateVerified) {
		return
	}

	AdminTemplates.MustRun("install/database.gohtml", w, struct {
		Err *frontend.Error
	}{})
}

func handleDatabaseConfig(w http.ResponseWriter, r *http.Request) {
	if !InstallState.Sufficient(install.StateVerified) {
		http.Error(w, "Database cannot be configured outside of installer mode", 403)
		return
	}

	r.ParseForm()
	if !r.PostForm.Has("config") {
		http.Error(w, "Expected JSON encoded `config` header", 400)
		return
	}

	Config.Mut.Lock()
	defer Config.Mut.Unlock()

	postdat := r.PostForm.Get("config")
	err := json.Unmarshal([]byte(postdat), &Config.Database)
	if err != nil {
		http.Error(w, "Expected `config` header to be correctly JSON encoded", 400)
		return
	}

	err = install.Tables(Config.Database)
	if err != nil {
		http.Error(w, "Error during table setups: "+err.Error(), 400)
		return
	}

	InstallState.Next()
	w.Write([]byte("database setup complete"))
}

func handleDatabaseTest(w http.ResponseWriter, r *http.Request) {
	if !InstallState.Sufficient(install.StateVerified) {
		http.Error(w, "Database tests are only allowed after verification", 403)
		return
	}

	r.ParseForm()
	if !r.PostForm.Has("test") {
		http.Error(w, "Expected a JSON-encoded `test` header", 400)
		return
	}

	testdat := config.Database{}
	err := json.Unmarshal([]byte(r.PostForm.Get("test")), &testdat)
	if err != nil {
		http.Error(w, "Expected `test` header to be correctly JSON-encoded", 400)
		return
	}

	dbc := mysql.Config{
		Net:    "tcp",
		Addr:   testdat.Addr(),
		DBName: testdat.Database,
		User:   testdat.Username,
		Passwd: testdat.Password,

		AllowNativePasswords: true,
		RejectReadOnly:       true,
	}

	db, err := sql.Open("mysql", dbc.FormatDSN())
	if err != nil {
		http.Error(w, "Invalid config (error: "+err.Error()+")", 400)
	}
	defer db.Close()

	err = db.Ping()
	if err != nil {
		http.Error(w, "Database did not respond correctly to PING (error: "+err.Error()+")", 400)
	}
}

func handleInstallFinal(w http.ResponseWriter, r *http.Request) {
	if r.URL.Query().Has("skip") {
		Config.Mut.Lock()
		defer Config.Mut.Unlock()

		InstallState.Next()
		Config.Installed = true

		http.Redirect(w, r, "/admin/install/success", http.StatusFound)
		return
	}

	AdminTemplates.MustRun("install/final.gohtml", w, nil)
}

func handleInstallFinalize(w http.ResponseWriter, r *http.Request) {
}

func handleInstallSuccess(w http.ResponseWriter, r *http.Request) {
	AdminTemplates.MustRun("install/success.gohtml", w, nil)
}
