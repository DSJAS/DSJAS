<?php

function config_set($section, $key, $value) {
    $config_data = parse_ini_file("../../Config.ini", true);
    $config_data[$section][$key] = $value;
    $new_content = '';
    foreach ($config_data as $section => $section_content) {
        $section_content = array_map(function($value, $key) {
            return "$key=$value";
        }, array_values($section_content), array_keys($section_content));
        $section_content = implode("\n", $section_content);
        $new_content .= "[$section]\n$section_content\n";
    }
    file_put_contents("../../Config.ini", $new_content);
}

function completePrimarySetup()
{
    config_set("setup", "installed", "1");
}

function completeVerificationStage()
{
    config_set("setup", "owner_verified", "1");
}

function completeDatabaseStage()
{
    config_set("setup", "database_installed", "1");
}

function finalizeInstall()
{
    config_set("setup", "install_finalized", "1");
}

?>