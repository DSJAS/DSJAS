package templates

import (
	"strings"
	"testing"
)

func TestLoad(t *testing.T) {
	s := Store{}
	err := s.Load("testdata")
	if err == nil {
		t.Error("expected error from load, got nil")
	}

	errs, ok := err.(LoadError)
	if !ok {
		t.Fatal("wrong return type from load, expected LoadError")
	}
	if !strings.Contains(errs[0].Error(), "invalid.gohtml") {
		t.Fatal("expected invalid.gohtml to raise error, got:", errs[0])
	}
}
