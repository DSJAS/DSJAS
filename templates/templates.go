// Package templates implements a store for html.Templates loaded at startup.
package templates

import (
	"errors"
	"fmt"
	"html/template"
	"io"
	"os"
	"path/filepath"
	"strings"
)

// Store retrieval and execution errors.
var (
	ErrNotFound       = errors.New("no such template or subtemplate")
	ErrTemplateFailed = errors.New("template failed to execute")
)

// Store is a store for html.Templates.
type Store struct {
	root *template.Template
	// base directory
	base string
}

func (s *Store) loadDir(dir string, root *template.Template) error {
	if root == nil {
		panic("template store: incorrect loadDir usage: root is nil")
	}
	errors := LoadError{}

	d, err := os.ReadDir(filepath.Join(s.base, dir))
	if err != nil {
		return LoadError{fmt.Errorf("dir %s: %w", dir, err)}
	}

	end := filepath.Base(dir)
	newroot := root.New(end)
	for _, f := range d {
		if f.IsDir() {
			lerr := s.loadDir(filepath.Join(dir, f.Name()), newroot)
			if lerr != nil {
				errors = append(errors, lerr.(LoadError)...)
			}

			continue
		}

		tmpl := newroot.New(f.Name())
		_, err := tmpl.ParseFiles(filepath.Join(s.base, dir, f.Name()))
		if err != nil {
			errors = append(errors, err)
		}
	}

	if len(errors) == 0 {
		return nil
	}
	return errors
}

// Load walks dir and loads every successive directory into a new tier in the
// store. This means that templates in sub-directories may call any template
// above them, but not the other way around.
func (s *Store) Load(dir string) error {
	s.root = template.New("DSJAS")
	s.base = dir

	return s.loadDir("/", s.root)
}

// Run executes the template at path, which must be rooted at the loaded
// directory. Path may or may not begin with a forward slash.
func (s *Store) Run(path string, w io.Writer, data interface{}) error {
	segments := strings.Split(path, "/")
	walk := s.root
	for _, p := range segments {
		// Ignore any possible leading slashes
		if p == "" {
			continue
		}

		walk = walk.Lookup(p)
		if walk == nil {
			return fmt.Errorf("run %s: %s: %w", path, p, ErrNotFound)
		}
	}

	err := walk.Execute(w, data)
	if err != nil {
		return fmt.Errorf("run %s: %w: %s", path, ErrTemplateFailed, err.Error())
	}

	return nil
}

// MustRun calls (Store*).Run with the provided arguments and panics if an
// error was returned.
func (s *Store) MustRun(path string, w io.Writer, data interface{}) {
	err := s.Run(path, w, data)
	if err != nil {
		panic(err)
	}
}

// LoadError is the collection of errors caused by a call to Store.Load. If the
// entire Load completed successfully, nil is returned, not an empty LoadError.
type LoadError []error

func (e LoadError) Error() string {
	ret := "template store: load:"
	for _, elem := range e {
		ret += fmt.Sprintf("\n\t%s", elem.Error())
	}

	return ret
}
