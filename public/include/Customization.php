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

define("GLOBAL_CONFIG_FILE", ABSPATH . "/Config.ini");
define("THEME_CONFIG_FILE", ABSPATH . "/admin/site/UI/config.ini");
define("PLUGIN_CONFIG_FILE", ABSPATH . "/admin/site/extensions/config.ini");
define("MODULE_CONFIG_FILE", ABSPATH . "/admin/site/modules/config.ini");

define("AMOUNT_CONFIG_FILES", 3);

define("ID_GLOBAL_CONFIG", 0);
define("ID_THEME_CONFIG", 1);
define("ID_PLUGIN_CONFIG", 2);
define("ID_MODULE_CONFIG", 3);


class Configuration
{
    private $fileNames;
    private $data = array();

    function __construct($parseGlobal = true, $parseTheme = true, $parsePlugin = true, $parseModule = true)
    {
        $this->fileNames[ID_GLOBAL_CONFIG] = GLOBAL_CONFIG_FILE;
        $this->fileNames[ID_THEME_CONFIG] = THEME_CONFIG_FILE;
        $this->fileNames[ID_PLUGIN_CONFIG] = PLUGIN_CONFIG_FILE;
        $this->fileNames[ID_MODULE_CONFIG] = MODULE_CONFIG_FILE;

        if ($parseGlobal) {
            $this->data[ID_GLOBAL_CONFIG] = $this->parseFile(ID_GLOBAL_CONFIG);
        }
        if ($parseTheme) {
            $this->data[ID_THEME_CONFIG] = $this->parseFile(ID_THEME_CONFIG);
        }
        if ($parsePlugin) {
            $this->data[ID_PLUGIN_CONFIG] = $this->parseFile(ID_PLUGIN_CONFIG);
        }
        if ($parseModule) {
            $this->data[ID_MODULE_CONFIG] = $this->parseFile(ID_MODULE_CONFIG);
        }
    }

    function __destruct()
    {
    }

    function __get($property)
    {
        return $this->$property;
    }

    function __set($property, $value)
    {
        $this->$property = $value;
    }

    function getKey($configFile, $section, $key)
    {
        $data = $this->data[$configFile];

        $rawData = $data[$section][$key];

        return html_entity_decode($rawData);
    }

    function setKey($configFile, $section, $key, $value)
    {
        $config_data = $this->getIniData($configFile);
        $config_data[$section][$key] = htmlentities($value);
        $new_content = '';
        foreach ($config_data as $section => $section_content) {
            $section_content = array_map(
                function ($value, $key) {
                    return "$key=\"$value\"";
                },
                array_values($section_content),
                array_keys($section_content)
            );
            $section_content = implode("\n", $section_content);
            $new_content .= "[$section]\n$section_content\n";
        }
        file_put_contents($this->fileNames[$configFile], $new_content);
        $this->data[$configFile] = $this->parseFile($configFile);
    }


    private function parseFile($file)
    {
        return parse_ini_file($this->fileNames[$file], true);
    }

    private function getIniData($file)
    {
        return $this->data[$file];
    }
}

function getActiveTheme()
{
    $conf = new Configuration(false, true, false, false);

    $default = $conf->getKey(ID_THEME_CONFIG, "config", "use_default");

    if (!$default) {
        $currentTheme = $conf->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
    } else {
        $currentTheme = "default";
    }

    return $currentTheme;
}

function getCurrentBankName()
{
    $conf = new Configuration(true, false, false, false);
    return $conf->getKey(ID_GLOBAL_CONFIG, "customization", "bank_name");
}

function getCurrentBankURL()
{
    $conf = new Configuration(true, false, false, false);
    return $conf->getKey(ID_GLOBAL_CONFIG, "customization", "bank_domain");
}
