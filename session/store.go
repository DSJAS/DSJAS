package session

import (
	"errors"
	"net/http"
	"sync"

	"github.com/google/uuid"
)

// HTTP session cookie implementation constants.
const (
	// Name of the HTTP cookie containing the session ID.
	SessionTokenCookie = "session_id"
)

// Token usage errors. Any instance of TokenError will unwrap to one of these
// types.
var (
	ErrBadTokenSyntax = errors.New("bad session token syntax")
	ErrTokenNotFound  = errors.New("no session for given token")
	ErrNoToken        = errors.New("no token provided")

	errInvalidToken = errors.New("invalid token")
	errTokenEmpty   = errors.New("token empty")
)

// TokenError is returned when an invalid token is attempted for use in the
// token store.
type TokenError struct {
	wrap, detail error
}

func (e TokenError) Error() string {
	return e.wrap.Error() + ": " + e.detail.Error()
}

func (e TokenError) Unwrap() error {
	return e.wrap
}

type Store struct {
	*sync.RWMutex
	store map[uuid.UUID]Session
}

func NewStore() *Store {
	return &Store{new(sync.RWMutex), make(map[uuid.UUID]Session)}
}

// Extract returns the session associated with the given token. An error is
// returned if the provided token is not valid or has no session associated
// with it (does not exist).
func (s *Store) Extract(token string) (Session, error) {
	if token == "" {
		return Session{}, TokenError{ErrNoToken, errTokenEmpty}
	}

	tok, err := uuid.Parse(token)
	if err != nil {
		return Session{}, TokenError{ErrBadTokenSyntax, err}
	}

	s.RLock()
	defer s.RUnlock()
	sess, ok := s.store[tok]
	if !ok {
		return Session{}, TokenError{ErrTokenNotFound, errInvalidToken}
	}

	return sess, nil
}

// ExtractCookie returns the session associated with the token provided by the
// given request. An error is returned if the request does not contain a
// session token, the session token is invalid or if the token has no session
// associated with it.
func (s *Store) ExtractCookie(r *http.Request) (Session, error) {
	c, err := r.Cookie(SessionTokenCookie)
	if err != nil {
		return Session{}, TokenError{ErrTokenNotFound, err}
	}

	return s.Extract(c.Value)
}

// Insert generates a new session ID and initializes the session to the value
// of sess. If sess is nil, the zero value is used. If an error is returned, it
// is due to a failure to generate a new UUID for the session.
func (s *Store) Insert(sess *Session) (string, error) {
	if sess == nil {
		sess = &Session{}
	}

	id, err := uuid.NewRandom()
	if err != nil {
		return "", err
	}

	s.Lock()
	defer s.Unlock()
	s.store[id] = *sess

	return id.String(), nil
}

// Generate generates and stores a new session in the session store, returning
// the stringified session ID. The session store is initialized to its zero
// value.
//
// This call is equivalent to obtaining the ID from store.Insert(nil).
func (s *Store) Generate() string {
	id, _ := s.Insert(nil)
	return id
}
