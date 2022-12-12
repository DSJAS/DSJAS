package dsjas

import (
	"net/http"

	"github.com/gorilla/mux"
)

type DSJAS struct {
	mux *mux.Router
}

func NewDSJAS() DSJAS {
	return DSJAS{
		mux: mux.NewRouter(),
	}
}

func (d *DSJAS) ServeHTTP(w http.ResponseWriter, r *http.Request) {
}
