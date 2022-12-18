// DSJAS is a fully featured bait bank for baiting scammers attempting to
// infiltrate online banking services.
package main

import (
	"context"
	"errors"
	"flag"
	"log"
	"net/http"
	"net/url"
	"os"
	"os/exec"
	"os/signal"
	"strings"
	"time"

	"github.com/DSJAS/DSJAS/config"
	"github.com/DSJAS/DSJAS/install"
	"github.com/DSJAS/DSJAS/templates"
	"github.com/gorilla/mux"
)

// Command line flags.
var (
	ListenAddr     = flag.String("listen", ":80", "The listen address for the server")
	ExtensionsPath = flag.String("plugins", "plugins", "The relative or absolute path to the plugins directory")
	ConfigPath     = flag.String("config", "dsjas.json", "The relative or absolute path to the DSJAS configuration file")
	ConfigNoSave   = flag.Bool("nosave", false, "If true, DSJAS will not save your configuration on server shutdown")
	RunInstaller   = flag.Bool("install", false, "Open your browser to the installer, if required")
)

// DSJAS Global State.
var (
	Config *config.Config
	// Templates for frontend use.
	AdminTemplates *templates.Store
)

// saveconf is the deferred function called to save the configuration. This
// shouldn't really be called manually outside of this defer; config.Store
// should instead be used.
func saveconf(cfg *config.Config) {
	if !*ConfigNoSave {
		err := config.Store(cfg, *ConfigPath)
		if err != nil {
			log.Println("WARNING: Failed to save configuration:", err)
		}
	}
}

func inittemplates() error {
	// Admin dashboard templates
	AdminTemplates = new(templates.Store)
	err := AdminTemplates.Load("templates/admin")
	if err != nil {
		return err
	}
	return nil
}

func initroutes(m *mux.Router) {
	// Static file store
	m.PathPrefix("/assets/").Handler(http.StripPrefix("/assets/", http.FileServer(http.Dir("./assets"))))

	// Admin routes
	{
		s := m.PathPrefix("/admin/").Subrouter()
		{
			s := s.PathPrefix("/install/").Subrouter()

			s.HandleFunc("/verify", handleInstallVerify)
			s.HandleFunc("/welcome", handleInstallWelcome)
			s.Handle("/", http.RedirectHandler("/admin/install/welcome", http.StatusFound))
		}
	}

	// Global fallback for GET requests, used for permalinking
	m.Path("/").Methods("GET").HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		http.Error(w, "Not found", http.StatusNotFound)
	})

	// Fallback for any other request type with an error
	m.Path("/").HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		http.Error(w, "Wrong request method", http.StatusMethodNotAllowed)
	})
}

// logger is middleware which logs incoming requests.
func logger(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		log.Printf("[%s] %s '%s'", r.RemoteAddr, r.Method, r.RequestURI)
		next.ServeHTTP(w, r)
	})
}

// installRedirector is middleware which redirects an uninstalled site to the
// installer. This must always be *last* in the middleware chain.
func installRedirector(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		if strings.HasPrefix(r.URL.Path, "/admin/install/") {
			next.ServeHTTP(w, r)
			return
		}

		Config.Mut.RLock()
		defer Config.Mut.RUnlock()

		if install.Required(Config) {
			http.Redirect(w, r, "/admin/install/", http.StatusFound)
			return
		}
		next.ServeHTTP(w, r)
	})
}

func main() {
	log.Printf("DSJAS v%d.%d.%d starting...", VersionMajor, VersionMinor, VersionPatch)

	var err error
	flag.Parse()
	Config, err = config.Load(*ConfigPath)
	if err != nil {
		log.Fatal("config load failed:", err)
	}

	m := mux.NewRouter()
	srv := http.Server{
		Handler:      m,
		Addr:         *ListenAddr,
		WriteTimeout: 15 * time.Second,
		ReadTimeout:  15 * time.Second,
	}
	m.Use(logger, installRedirector)
	if err = inittemplates(); err != nil {
		log.Panic(err)
	}
	initroutes(m)

	errchan, sigchan := make(chan error, 1), make(chan os.Signal, 1)
	signal.Notify(sigchan, os.Interrupt)
	go func() {
		err := srv.ListenAndServe()
		if !errors.Is(err, http.ErrServerClosed) {
			errchan <- err
		}
	}()
	defer saveconf(Config)

	Config.Mut.RLock()
	if *RunInstaller && install.Required(Config) {
		browser := os.Getenv("BROWSER")
		if browser == "" {
			browser = "firefox"
		}

		url := url.URL{
			Scheme: "http",
			Host:   *ListenAddr,
			Path:   "/admin/install/welcome",
		}
		exec.Command(browser, url.String()).Start()

		log.Println("Opening browser", browser, "to installer")
	}
	Config.Mut.RUnlock()

	select {
	case <-sigchan:
		log.Println("Caught interrupt. Terminating gracefully...")
		ctx, cancel := context.WithTimeout(context.Background(), time.Second*10)
		defer cancel()
		err := srv.Shutdown(ctx)
		if err != nil {
			if err == ctx.Err() {
				log.Fatal("Timeout exceeded. Terminating forcefully")
			}
			log.Fatal(err)
		}
	case err := <-errchan:
		log.Fatal(err)
	}
}
