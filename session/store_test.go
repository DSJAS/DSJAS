package session

import (
	"errors"
	"net/http"
	"testing"

	"github.com/google/uuid"
)

func testExtract(t *testing.T) {
	store := NewStore()
	tok, err := store.Insert(&Session{SiteUserID: 127})
	if err != nil {
		t.Fatal(err)
	}

	ret, err := store.Extract(tok)
	if err != nil {
		t.Fatalf("Expected valid session retrieval, got %s", err.Error())
	}
	if ret.SiteUserID != 127 {
		t.Fatalf("Expected session for userID 127, got %d", ret.SiteUserID)
	}

	newtok := []rune(tok)
	swp := newtok[len(tok)-1]
	if swp != '0' {
		newtok[len(tok)-1] = '0'
	} else {
		newtok[len(tok)-1] = '1'
	}

	ret, err = store.Extract(string(newtok))
	if err == nil {
		t.Fatalf("Invalid session did not return error (sess: %v)", ret)
	}
	if !errors.Is(err, ErrTokenNotFound) {
		t.Fatalf("Expected ErrTokenNotFound from invalid session, got %s", err.Error())
	}
}

func testExtractCookie(t *testing.T) {
	store := NewStore()
	tok, err := store.Insert(&Session{SiteUserID: 127})
	if err != nil {
		t.Fatal(err)
	}

	// Successful request
	req, err := http.NewRequest("GET", "/", nil)
	if err != nil {
		panic(err)
	}
	req.AddCookie(&http.Cookie{Name: SessionTokenCookie, Value: tok})

	ret, err := store.ExtractCookie(req)
	if err != nil {
		t.Fatalf("Expected valid session retrieval, got %s", err.Error())
	}
	if ret.SiteUserID != 127 {
		t.Fatalf("Expected session for userID 127, got %d", ret.SiteUserID)
	}

	// Failure request (no session)
	//
	// We need to generate a UID which we know does not exist, so just
	// intentionally break our existing one. But, we need it to still parse
	// properly.
	newtok := []rune(tok)
	swp := newtok[len(tok)-1]
	if swp != '0' {
		newtok[len(tok)-1] = '0'
	} else {
		newtok[len(tok)-1] = '1'
	}

	freq, err := http.NewRequest("GET", "/", nil)
	if err != nil {
		panic(err)
	}
	freq.AddCookie(&http.Cookie{Name: SessionTokenCookie, Value: string(newtok)})

	ret, err = store.ExtractCookie(freq)
	if err == nil {
		t.Fatalf("Invalid session did not return error (sess: %v)", ret)
	}
	if !errors.Is(err, ErrTokenNotFound) {
		t.Fatalf("Expected ErrTokenNotFound from invalid session, got %s", err.Error())
	}
}

func testExtractConcurrent(t *testing.T) {
	store := NewStore()

	ids := make([]string, 10)
	wait := make(chan struct{}, len(ids))
	for i := range ids {
		ids[i] = store.Generate()
	}

	for i, id := range ids {
		go func(i int, id string) {
			_, err := store.Extract(id)
			if err != nil {
				t.Error("unexpected error from concurrent extract: ", err)
			}

			wait <- struct{}{}
		}(i, id)
	}

	for i := 0; i < len(ids); i++ {
		<-wait
	}
}

func TestExtract(t *testing.T) {
	t.Run("Normal", testExtract)
	t.Run("Cookie", testExtractCookie)
	t.Run("Concurrent", testExtractConcurrent)
}

func TestGenerate(t *testing.T) {
	store := NewStore()
	id := store.Generate()

	if _, err := uuid.Parse(id); err != nil {
		t.Fatalf("Generate() returned invalid UUID (error: %v)", err)
	}
}
