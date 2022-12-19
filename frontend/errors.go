package frontend

import (
	"fmt"
	"html/template"
)

// Error is a user friendly representation of an error for display. It contains
// the necessary formatting for use in HTML templates.
type Error struct {
	Title, Body string
}

// HTML formats the error as HTML safe for output to the browser. Title and
// Body need not be escaped; this is done automatically.
func (e *Error) HTML() template.HTML {
	esch, escerr := template.HTMLEscapeString(e.Title), template.HTMLEscapeString(e.Body)

	return template.HTML(fmt.Sprintf(`
<div class="alert alert-danger">
	<strong>%s</strong> %s
</div>`, esch, escerr))
}
