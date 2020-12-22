<?php

require_once ABSPATH . INC . "DB.php";
require_once ABSPATH . INC . "Customization.php";


// General constants
define("STATISTICS_TABLE", "statistics");
define("STATISTICS_DEFAULT_CATEGORY", "General");

define("STATISTICS_STATE_NAME", "_statstate");
define("STATISTICS_BEGIN_NAME", "_session_began");
define("STATISTICS_ENDED_NAME", "_session_ended");

// Statistic state definition
define("STATSTATE_INACTIVE", 0);    // INACTIVE - 0: DSJAS is not currently recording statistics and the previous session has been purged
define("STATSTATE_FROZEN",   1);    // FROZEN   - 1: DSJAS is not currently recording statistics, but the previous session has not yet been purged
define("STATSTATE_ACTIVE",   2);    // ACTIVE   - 2: DSJAS is currently recording statistics and the statistics table contains live data

// Statistic type definition
define("STATISTICS_TYPE_NUMBER",    0);     // NUMBER    - 0: A number that can be changed and freely assigned to any integer value
define("STATISTICS_TYPE_COUNTER",   1);     // COUNTER   - 1: A number that cannot be changed freely - can only be incremented
define("STATISTICS_TYPE_TIMESTAMP", 2);     // TIMESTAMP - 2: A timestamp object that cannot be freely accessed - can only be replaced with the current timestamp


// System data fields and their initial values
const sysdat_fields = [ [
                            STATISTICS_STATE_NAME,      // stat_name  - Statistic name in database
                            STATISTICS_TYPE_NUMBER,     // stat_type  - Data type of statistic in database
                            STATSTATE_INACTIVE          // stat_value - Initial value of the sysval in database
                        ],
                        [
                            STATISTICS_BEGIN_NAME,
                            STATISTICS_TYPE_TIMESTAMP,
                            0
                        ],
                        [
                            STATISTICS_ENDED_NAME,
                            STATISTICS_TYPE_TIMESTAMP,
                            0
                        ]
];
define("STATISTICS_SYSDAT_COUNT", count(sysdat_fields));   // The amount of sys_dat fields required for statistics to be initialised


class Statistics
{
    private DB $database;
    private Configuration $config;
    private bool $ownDatabaseInstance = false;
    private bool $ownConfigInstance = false;

    private bool $statsAvailable = true;

    private string $db_host;
    private string $db_name;
    private string $db_username;
    private string $db_password;


    function __construct(Configuration $configInstance=null, DB $databaseInstance=null) {
        if ($configInstance == null) {
            $this->config = new Configuration(true, false, false, false);
            $this->ownConfigInstance = true;
        }
        else {
            $this->config = $configInstance;
        }

        $this->db_host = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "server_hostname");
        $this->db_name = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "database_name");
        $this->db_username = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "username");
        $this->db_password = $this->config->getKey(ID_GLOBAL_CONFIG, "database", "password");


        if ($databaseInstance == null) {
            $this->database = new DB($this->db_host, $this->db_name, $this->db_username, $this->db_password);
            $this->ownDatabaseInstance = true;

            if (!$this->database->validateConnection()) {
                $this->statsAvailable = false;
                return;
            }
        }
        else {
            $this->database = $databaseInstance;
        }

        if (!$this->sysdatPresent()) {
            $this->initialiseSysdat();
        }
    }

    function __destruct() {
        if ($this->ownDatabaseInstance) {
            $this->database->safeDisconnect();
        }
    }

    function statisticsAvailable() {
        return $this->statsAvailable;
    }

    function getStatsResources() {
        return [$this->ownConfigInstance, $this->ownDatabaseInstance];
    }

    function getCurrentStatsState() {
        $retrieveQuery = new SimpleStatement("SELECT `stat_value` FROM `" . STATISTICS_TABLE . "` WHERE `stat_name` = '" . STATISTICS_STATE_NAME . "'");
        $this->database->unsafeQuery($retrieveQuery);

        if (!$this->database->validateAction()) {
            return STATSTATE_INACTIVE;
        }

        return (integer)$retrieveQuery->result[0]["stat_value"];
    }

    function shiftStatsState() {
        $currentState = $this->getCurrentStatsState();

        switch ($currentState)
        {
            case STATSTATE_INACTIVE:
                if (!$this->handleActiveShift())
                    return false;

                $currentState = STATSTATE_ACTIVE;
                break;

            case STATSTATE_FROZEN:
                if (!$this->handleInactiveShift())
                    return false;

                $currentState = STATSTATE_INACTIVE;
                break;

            case STATSTATE_ACTIVE:
                if (!$this->handleFrozenShift())
                    return false;

                $currentState = STATSTATE_FROZEN;
                break;

            default:
                $currentState = STATSTATE_INACTIVE;
                break;
        }

        $this->rawShiftState($currentState);
        return true;
    }

    function getStatisticType($statisticName) {
        $query = new SimpleStatement("SELECT `stat_type` FROM `" . STATISTICS_TABLE . "` WHERE `stat_name` = '$statisticName'");
        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            return STATISTICS_TYPE_NUMBER;

        return $query->result[0]["stat_type"];
    }

    function statisticExists($statisticName) {
        $query = new SimpleStatement("SELECT * FROM `" . STATISTICS_TABLE . "` WHERE `stat_name` = '$statisticName'");
        $this->database->unsafeQuery($query);

        return ($query->affectedRows > 0);
    }

    function isStatisticSystemReserved($statisticName) {
        $query = new SimpleStatement("SELECT `sys_data` FROM `" . STATISTICS_TABLE . "` WHERE `stat_name` = $statisticName");
        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            return false;

        return $query->result[0]["sys_data"];
    }

    function registerStatistic($statisticName, $statisticType=STATISTICS_TYPE_NUMBER, $statLabel=" ", $statCategory=" ", $initialValue=0, $themeSourced=false) {
        if ($this->statisticExists($statisticName))
            return false;

        if ($this->getCurrentStatsState() != STATSTATE_ACTIVE)
            return false;

        $query = new PreparedStatement("INSERT INTO `" . STATISTICS_TABLE . "` (`stat_name`, `stat_type`, `stat_value`, `stat_label`, `stat_category`, `sys_data`, `theme_def`) VALUES (?, ?, ?, ?, ?, ?, ?)",
                                        [$statisticName, $statisticType, $initialValue, $statLabel, $statCategory, 0, (int)$themeSourced],
                                        "siissii");
        $this->database->prepareQuery($query);
        $this->database->query();

        if (!$this->database->validateAction())
            return false;

        return true;
    }

    function getSessionTimestamps() {
        // Get start time
        $query = new SimpleStatement("SELECT `stat_value` FROM `" . STATISTICS_TABLE . "` WHERE `stat_name` = '" . STATISTICS_BEGIN_NAME . "'");
        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            $startTime = 0;

        $startTime = $query->result[0]["stat_value"];

        // Get end time
        $query = new SimpleStatement("SELECT `stat_value` FROM `" . STATISTICS_TABLE . "` WHERE `stat_name` = '" . STATISTICS_ENDED_NAME . "'");
        $this->database->unsafeQuery($query);


        if (!$this->database->validateAction())
            $endTime = 0;

        $endTime = $query->result[0]["stat_value"];

        return [$startTime, $endTime];
    }

    function incrementCounterStat($statName) {
        if (!$this->performOnWritePrequesites($statName, STATISTICS_TYPE_COUNTER, [STATISTICS_TYPE_NUMBER]))
            return false;

        $query = new SimpleStatement("UPDATE `" . STATISTICS_TABLE . "` SET `stat_value` = (`stat_value` + 1) WHERE `stat_name` = '$statName' ");
        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            return false;

        return true;
    }

    function setNumberStat($statName, $statVal=0) {
        if (!$this->performOnWritePrequesites($statName, STATISTICS_TYPE_NUMBER))
            return false;

        $query = new PreparedStatement("UPDATE `" . STATISTICS_TABLE . "` SET `stat_value` = ? WHERE `stat_name` = ?",
                                        [$statVal, $statName],
                                        "is");

        $this->database->prepareQuery($query);
        $this->database->query();

        if (!$this->database->validateAction())
            return false;

        return true;

    }

    function stampTimestampStat($statName) {
        if (!$this->performOnWritePrequesites($statName, STATISTICS_TYPE_TIMESTAMP))
            return false;

        $stamp = time();
        $query = new SimpleStatement("UPDATE `" . STATISTICS_TABLE . "` SET `stat_value` = $stamp WHERE `stat_name` = '$statName'");

        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            return false;

        return true;
    }

    function getStatistic($statName) {
        if ($this->isStatisticSystemReserved($statName))
            return 0;

        if (!$this->statisticExists($statName))
            return 0;

        $query = new PreparedStatement("SELECT * FROM `" . STATISTICS_TABLE . "` WHERE `stat_name` = ?",
                                        [$statName],
                                        "s");

        $this->database->prepareQuery($query);
        $this->database->query();

        if (!$this->database->validateAction())
            return 0;

        return $query->result[0]["stat_value"];
    }

    private function performOnWritePrequesites($statName, $requiredType, $extraAllowedTypes=[]) {
        if (!$this->statisticExists($statName))
            if (!$this->registerStatistic($statName, $requiredType, $statName, STATISTICS_DEFAULT_CATEGORY))
                return false;

        $type = $this->getStatisticType($statName);
        if ($type != $requiredType) {
            if (count($extraAllowedTypes) > 0) {
                if (!in_array($type, $extraAllowedTypes))
                    return false;
            } else {
                return false;
            }
        }

        if ($this->isStatisticSystemReserved($statName))
            return false;

        if ($this->getCurrentStatsState() != STATSTATE_ACTIVE)
            return false;


        return true;
    }

    private function sysdatPresent() {
        $query = new SimpleStatement("SELECT * FROM `" . STATISTICS_TABLE . "` WHERE `sys_data` = 1");
        $this->database->unsafeQuery($query);

        if ($query->affectedRows < STATISTICS_SYSDAT_COUNT) {
            return false;
        }

        return true;
    }

    private function initialiseSysdat() {
        // If we have deleted the sysdat fields, we should just reset to defaults
        // because something has gone... quite wrong...
        foreach (sysdat_fields as $sysdat)
        {
            $name = $sysdat[0];
            $type = $sysdat[1];
            $initial_value = $sysdat[2];

            $query = new SimpleStatement("INSERT INTO `" . STATISTICS_TABLE . "`
(`stat_name`, `stat_type`, `stat_value`, `sys_data`, `stat_label`, `stat_category`)
VALUES ('$name', '$type', '$initial_value', 1, '$name', 'System reserved')");

            $this->database->unsafeQuery($query);
        }
    }

    private function rawShiftState($newState) {
        $query = new SimpleStatement("UPDATE `" . STATISTICS_TABLE . "` SET `stat_value` = $newState WHERE `stat_name` = '" . STATISTICS_STATE_NAME . "'");
        $this->database->unsafeQuery($query);
    }

    private function handleActiveShift() {
        // Timestamp session beginning
        $timestamp = time();

        $query = new SimpleStatement("UPDATE `" . STATISTICS_TABLE . "` SET `stat_value` = $timestamp WHERE `stat_name` = '" . STATISTICS_BEGIN_NAME . "'");
        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            return false;


        return true;
    }

    private function handleInactiveShift() {
        // Purge all current session data
        $query = new SimpleStatement("DELETE FROM `" . STATISTICS_TABLE . "` WHERE `sys_data` = 0");
        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            return false;

        return true;
    }

    private function handleFrozenShift() {
        // Timestamp session ending
        $timestamp = time();

        $query = new SimpleStatement("UPDATE `" . STATISTICS_TABLE . "` SET `stat_value` = $timestamp WHERE `stat_name` = '" . STATISTICS_ENDED_NAME . "'");
        $this->database->unsafeQuery($query);

        if (!$this->database->validateAction())
            return false;


        return true;
    }
}
