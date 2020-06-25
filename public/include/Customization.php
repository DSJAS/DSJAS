<?php

/**
 * Welcome to Dave-Smith Johnson & Son family bank!
 * 
 * This is a tool to assist with scam baiting, especially with scammers attempting to
 * obtain bank information or to attempt to scam you into giving money.
 * 
 * This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
 * is free to use and change for all users. Scam bait as much as you want!
 * 
 * This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
 * I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!
 * 
 * Please, waste these people's time as much as possible. It's fun and it does good for everyone.
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

    private $globalConfiguration;
    private $themeConfiguration;
    private $pluginConfiguration;
    private $moduleConfiguration;

    private $fileNames;

    private $parsedIniData = array();

    function __construct($parseGlobal = true, $parseTheme = true, $parsePlugin = true, $parseModule = true)
    {
        $this->fileNames[ID_GLOBAL_CONFIG] = GLOBAL_CONFIG_FILE;
        $this->fileNames[ID_THEME_CONFIG] = THEME_CONFIG_FILE;
        $this->fileNames[ID_PLUGIN_CONFIG] = PLUGIN_CONFIG_FILE;
        $this->fileNames[ID_MODULE_CONFIG] = MODULE_CONFIG_FILE;

        if ($parseGlobal) {
            $this->parsedIniData[ID_GLOBAL_CONFIG] = $this->parseFile(ID_GLOBAL_CONFIG);
        }
        if ($parseTheme) {
            $this->parsedIniData[ID_THEME_CONFIG] = $this->parseFile(ID_THEME_CONFIG);
        }
        if ($parsePlugin) {
            $this->parsedIniData[ID_PLUGIN_CONFIG] = $this->parseFile(ID_PLUGIN_CONFIG);
        }
        if ($parseModule) {
            $this->parsedIniData[ID_MODULE_CONFIG] = $this->parseFile(ID_MODULE_CONFIG);
        }
    }

    function __destruct()
    {
    }

    function __get($property)
    {
        return $this->property;
    }

    function __set($property, $value)
    {
        $this->property = $value;
    }

    function getKey($configFile, $section, $key)
    {
        $data = $this->parsedIniData[$configFile];

        return $data[$section][$key];
    }

    function setKey($configFile, $section, $key, $value)
    {
        $config_data = $this->getIniData($configFile);
        $config_data[$section][$key] = $value;
        $new_content = '';
        foreach ($config_data as $section => $section_content) {
            $section_content = array_map(
                function ($value, $key) {
                    return "$key=$value";
                },
                array_values($section_content),
                array_keys($section_content)
            );
            $section_content = implode("\n", $section_content);
            $new_content .= "[$section]\n$section_content\n";
        }
        file_put_contents($this->fileNames[$configFile], $new_content);
        $this->parsedIniData[$configFile] = $this->parseFile($configFile);
    }


    private function parseFile($file)
    {
        return parse_ini_file($this->fileNames[$file], true);
    }

    private function getIniData($file)
    {
        return $this->parsedIniData[$file];
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
