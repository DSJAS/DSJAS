// DSJAS is a fully featured bait bank for baiting scammers attempting to
// infiltrate online banking services.
package main

import (
	"context"
	"errors"
	"flag"
	"log"
	"net/http"
	"os"
	"os/signal"
	"time"

	"github.com/DSJAS/DSJAS/config"
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
		if cfg == nil {
			return
		}

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
}

// logger is middleware which logs incoming requests.
func logger(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		log.Printf("[%s] %s '%s'", r.RemoteAddr, r.Method, r.RequestURI)
		next.ServeHTTP(w, r)
	})
}

func main() {
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
	m.Use(logger)
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
