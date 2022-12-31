package install

import "unicode"

// PasswordSecure returns true if pw is secure enough to proceed. Acceptable
// passwords must be 5 UTF-8 runes long and contain at least one upper and
// lower case letter, as well as a number.
func PasswordSecure(pw string) bool {
	decode := []rune(pw)

	// Passwords must be at least 5 characters (runes) long
	if len(decode) < 5 {
		return false
	}

	// Must contain an upper, lower and number at least once
	seenLower, seenUpper, seenNum := false, false, false
	for _, r := range decode {
		if unicode.IsUpper(r) {
			seenUpper = true
			continue
		}
		if unicode.IsLower(r) {
			seenLower = true
			continue
		}
		if unicode.IsDigit(r) {
			seenNum = true
			continue
		}
	}

	return seenLower && seenUpper && seenNum
}
