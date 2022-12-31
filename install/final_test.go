package install

import "testing"

func TestPasswordSecure(t *testing.T) {
	tests := []struct {
		name string
		pw   string
		want bool
	}{
		{"Valid", "abcdEFG123456", true},

		{"Short", "a", false},
		{"NoUpper", "abcdefghijklmnop", false},
		{"NoNumber", "abcdefgHIJKLMNOP", false},
	}
	for _, tt := range tests {
		t.Run(tt.name, func(t *testing.T) {
			if got := PasswordSecure(tt.pw); got != tt.want {
				t.Errorf("PasswordSecure() = %v, want %v", got, tt.want)
			}
		})
	}
}
