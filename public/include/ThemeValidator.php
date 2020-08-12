<?php

/**
 * This file is part of DSJAS
 * Written and maintained by the DSJAS project.
 *
 * Copyright (C) 2020 - Ethan Marshall
 *
 * DSJAS is free software which is licensed and distributed under
 * the terms of the MIT software licence.
 * Exact terms can be found in the LICENCE file.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * above mentioned licence for specific details.
 */

require_once "Theme.php";
require_once "Module.php";

require_once "Customization.php";
require_once "Util.php";


class ThemeValidator
{

    private $errors = [];
    private $warnings = [];

    private $wereWarnings = false;
    private $wereErrors = false;
    private $wereFatalErrors = false;

    private $numErrors = 0;
    private $numFatalErrors = 0;
    private $numWarnings = 0;

    private $themeTarget = "default";

    private $runLoadTest = true;
    private $runStructTest = true;
    private $runModuleCompatTest = false;

    public $validationResult = "no_result";


    function __construct($runLoadTest = true, $runStructTest = true, $runModuleCompatTest = false, $themeTarget = "")
    {
        $this->themeTarget = ($themeTarget == "") ? getActiveTheme() : $themeTarget;

        $this->runLoadTest = $runLoadTest;
        $this->runStructTest = $runStructTest;
        $this->runModuleCompatTest = $runModuleCompatTest;
    }

    function __destruct()
    {
    }

    function run()
    {
        if ($this->runStructTest) {
            $this->runStructTest();
        }
        if ($this->runLoadTest) {
            $this->runLoadTest();
        }
        if ($this->runModuleCompatTest) {
            $this->runModuleCompatTest();
        }
    }

    function getResults()
    {
        if ($this->wereFatalErrors) {
            return [$this->calculateFinalResult(), -1, $this->errors];
        }

        return [$this->calculateFinalResult(), $this->warnings, $this->errors];
    }

    function issuesFound()
    {
        return $this->wereErrors;
    }


    private function runLoadTest()
    {
        // Test 1: Theme's index file should be loadable
        $this->runLoadTestFile("Index.php", "/", true, true);

        // Test 2: Theme's error file should be loadable
        $this->runLoadTestFile("Error.php", "/", true, false);

        // Test 3: Theme should provide loadable login/out pages
        $this->runLoadTestFile("Login.php", "user/");
        $this->runLoadTestFile("Logout.php", "user/");

        // Test 4: Theme should provide user dashboard
        $this->runLoadTestFile("Dashboard.php", "user/");
    }

    private function runStructTest()
    {
        // Test 1: Check that the theme directory exists
        $themeDir = ABSPATH . THEME_PATH . $this->themeTarget . "/";
        if (!is_dir($themeDir)) {
            $this->addError("Missing theme directory", "The theme directory appears to be missing. This may be a result of an error in the install process or bad configuration. You should re-install or remove the theme.", true);
        }

        // Test 2: Themes must provide an index page
        $this->testFileExists("Index.php", "/", true, true);

        // Test 3: Themes must provide an error page
        $this->testFileExists("Error.php", "/", true, false);

        // Test 4: Themes should provide a bootstrap file, even if it is empty
        $this->testFileExists("Bootstrap.php", "/", false, false);
    }

    private function runModuleCompatTest()
    {
    }

    private function calculateFinalResult()
    {
        if ($this->wereFatalErrors) {
            $this->validationResult = "fatal_error";
        } elseif ($this->wereErrors) {
            $this->validationResult = "errors_found";
        } elseif ($this->wereWarnings) {
            $this->validationResult = "passed_warnings";
        } else {
            $this->validationResult = "passed";
        }

        return $this->validationResult;
    }

    private function runLoadTestFile($fileName, $dirname, $fileRequired = false, $fileCritical = false)
    {
        $themeObject = new Theme($fileName, $dirname, $this->themeTarget);

        if (!$themeObject->validTheme) {
            if ($fileRequired) {
                $this->addError("Missing a required file", "The required file $fileName is not present in the theme", $fileCritical);
                return;
            }

            $this->addWarning("Missing theme file on disc", "The file $fileName was searched for and could not be found. This could mean that the theme is not installed correctly or that your configuration is invalid");
        }
    }

    private function testFileExists($fileName, $dirName, $fileRequired = false, $fileCritical = false)
    {
        $path = ABSPATH . THEME_PATH . $this->themeTarget . $dirName . $fileName;

        if (!is_file($path)) {
            if ($fileRequired) {
                $this->addError("Missing required theme file on disc", "The file $fileName was searched for and could not be found. This could mean that your configuration is invalid or that the theme is not packaged correctly", $fileCritical);
                return;
            }

            $this->addWarning("Missing theme file on disc", "The file $fileName was searched for and could not be found. This could mean that the theme is not installed correctly or that your configuration is invalid");
        }
    }

    private function addWarning($warningName, $warningText)
    {
        $this->wereWarnings = true;
        $this->numWarnings++;

        array_push($this->warnings, [$warningName, $warningText]);
    }

    private function addError($errorName, $errorText, $fatal = false)
    {
        if ($fatal) {
            $this->wereFatalErrors = true;
            $this->numFatalErrors++;
        }

        $this->wereErrors = true;
        $this->numErrors++;
        array_push($this->errors, [$errorName, $errorText]);
    }
}
