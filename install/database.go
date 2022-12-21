package install

import (
	"database/sql"
	"fmt"
	"strings"

	"github.com/DSJAS/DSJAS/config"
	"github.com/go-sql-driver/mysql"
)

// installTable is a single table to be auto-installed to the database. It
// contains at least one installField and may or may not have a primary key
// (although they should really all have one).
type installTable struct {
	Name   string
	Fields []installField
	// If nil, no primary key is used.
	PrimaryKey string
}

func (i installTable) FormatQuery() string {
	q := &strings.Builder{}

	q.WriteString(fmt.Sprintf("CREATE TABLE `%s` (", i.Name))

	for _, field := range i.Fields {
		q.WriteString(field.Format() + " , ")
	}

	if i.PrimaryKey != "" {
		q.WriteString("PRIMARY KEY(`" + i.PrimaryKey + "`)")
	}

	q.WriteRune(')')
	return q.String()
}

// installField is a single column to be used in an auto-installed table.
type installField struct {
	Name, Type       string
	Autoinc, NotNull bool
	Default          string
}

func (i installField) Format() string {
	flags := ""
	if i.Default != "" {
		flags += "DEFAULT " + i.Default + " "
	}
	if i.NotNull {
		flags += "NOT NULL "
	}
	if i.Autoinc {
		flags += "AUTO_INCREMENT "
	}
	return fmt.Sprintf("`%s` %s %s", i.Name, i.Type, flags)
}

var installTables = []installTable{
	{"users", []installField{
		{"user_id", "BIGINT", true, true, ""},
		{"username", "TINYTEXT", false, true, ""},
		{"real_name", "TEXT", false, true, "'unknown'"},
		{"password_hash", "LONGTEXT", false, true, ""},
		{"password_hint", "TEXT", false, true, "'No password hint provided'"},
		{"email", "TEXT", false, true, ""},
		{"date_of_registration", "DATETIME", false, false, "CURRENT_TIMESTAMP"},
	}, "user_id"},
	{"siteusers", []installField{
		{"user_id", "BIGINT", true, true, ""},
		{"username", "TINYTEXT", false, true, ""},
		{"real_name", "TEXT", false, true, "'unknown'"},
		{"password_hash", "LONGTEXT", false, true, ""},
		{"password_hint", "TEXT", false, true, "'No password hint provided'"},
		{"email", "TEXT", false, true, ""},
		{"date_of_registration", "DATETIME", false, true, "CURRENT_TIMESTAMP"},
		{"account_enabled", "BOOLEAN", false, true, "TRUE"},
		{"new_account", "BOOLEAN", false, true, "TRUE"},
	}, "user_id"},
	{"accounts", []installField{
		{"account_id", "BIGINT", true, true, ""},
		{"account_number", "INT(11)", false, true, ""},
		{"account_name", "TEXT", false, true, "'Current Account'"},
		{"account_type", "ENUM('current','savings','shared','misc')", false, true, "'current'"},
		{"account_balance", "DECIMAL(11,2)", false, true, "'0'"},
		{"account_holder_name", "TEXT", false, true, ""},
		{"associated_online_account_id", "INT", false, true, ""},
		{"account_disabled", "BOOLEAN", false, true, "FALSE"},
	}, "account_id"},
	{"transactions", []installField{
		{"transaction_id", "BIGINT", true, true, ""},
		{"transaction_date", "DATETIME", false, true, "CURRENT_TIMESTAMP"},
		{"origin_account_id", "BIGINT", false, true, ""},
		{"dest_account_id", "BIGINT", false, true, ""},
		{"transaction_description", "TEXT", false, true, ""},
		{"transaction_type", "ENUM('transfer','withdrawal','purchase','misc')", false, true, "'transfer'"},
		{"transaction_amount", "DECIMAL(11,2)", false, true, ""},
	}, "transaction_id"},
	{"statistics", []installField{
		{"stat_name", "VARCHAR(255)", false, true, ""},
		{"stat_type", "INT", false, true, ""},
		{"stat_value", "INT", false, true, "'0'"},
		{"stat_label", "TEXT", false, true, ""},
		{"stat_category", "TEXT", false, true, ""},
		{"sys_data", "BOOLEAN", false, true, "FALSE"},
		{"theme_def", "BOOLEAN", false, true, "FALSE"},
	}, "stat_name"},
}

func Tables(db config.Database) error {
	dbc := mysql.Config{
		Net:    "tcp",
		Addr:   db.Addr(),
		DBName: db.Database,
		User:   db.Username,
		Passwd: db.Password,

		AllowNativePasswords: true,
		RejectReadOnly:       true,
	}

	hndl, err := sql.Open("mysql", dbc.FormatDSN())
	if err != nil {
		return err
	}
	defer hndl.Close()

	tx, err := hndl.Begin()
	if err != nil {
		return err
	}
	for _, table := range installTables {

		_, err = tx.Query(table.FormatQuery())
		if err != nil {
			tx.Rollback()
			return err
		}

	}

	tx.Commit()
	return nil
}
