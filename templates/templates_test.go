package templates

import (
	"errors"
	"io"
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

func testRun(t *testing.T) {
	s := Store{}
	build := &strings.Builder{}
	s.Load("testdata")

	err := s.Run("1.gohtml", build, nil)
	if err != nil {
		t.Fatal("unexpected error from `1.gohtml`:", err)
	}
	if !strings.Contains(build.String(), "1.gohtml") {
		t.Fatalf("`1.gohtml` did not contain sentinel value\nfile text:\n%s", build.String())
	}
}

func testRunDir(t *testing.T) {
	s := Store{}
	build := &strings.Builder{}
	s.Load("testdata")

	err := s.Run("d1/2.gohtml", build, nil)
	if err != nil {
		t.Fatal("unexpected error from `2.gohtml`:", err)
	}
	if !strings.Contains(build.String(), "2.gohtml") {
		t.Fatalf("`2.gohtml` did not contain sentinel value\nfile text:\n%s", build.String())
	}
	build.Reset()

	err = s.Run("d1/d3/3.gohtml", build, nil)
	if err != nil {
		t.Fatal("unexpected error from `3.gohtml`:", err)
	}
	if !strings.Contains(build.String(), "3.gohtml") {
		t.Fatalf("`3.gohtml` did not contain sentinel value\nfile text:\n%s", build.String())
	}
}

func testRunFail(t *testing.T) {
	s := Store{}
	s.Load("testdata")

	err := s.Run("notexist.gohtml", io.Discard, nil)
	if err == nil {
		t.Fatal("expected run `notexist.gohtml` to fail")
	}
	if !errors.Is(err, ErrNotFound) {
		t.Error("wrong error from run `notexist.gohtml`, expected not found, got:", err)
	}

	err = s.Run("d2/badfunc.gohtml", io.Discard, nil)
	if err == nil {
		t.Fatal("expected run `d2/badfunc.gohtml` to fail")
	}
	if !errors.Is(err, ErrTemplateFailed) {
		t.Error("wrong error from run `d2/badfunc.gohtml`, expected template failure, got:", err)
	}
}

func testRunNested(t *testing.T) {
	s := Store{}
	build := &strings.Builder{}
	s.Load("testdata")

	err := s.Run("1nest.gohtml", build, nil)
	if err != nil {
		t.Fatal("unexpected error from `1nest.gohtml`:", err)
	}

	if !strings.Contains(build.String(), "1.gohtml") {
		t.Fatalf("`1nest.gohtml` did not contain sentinel value\nfile text:\n%s", build.String())
	}
	if !strings.Contains(build.String(), "1nest.gohtml") {
		t.Fatalf("`1nest.gohtml` did not contain second sentinel value\nfile text:\n%s", build.String())
	}
}

func TestRun(t *testing.T) {
	t.Run("Run", testRun)
	t.Run("RunDir", testRunDir)
	t.Run("RunFail", testRunFail)
	t.Run("RunNest", testRunNested)
}
