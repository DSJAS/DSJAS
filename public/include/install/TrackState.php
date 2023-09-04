<?php

require_once ABSPATH . INC . "Customization.php";


function completePrimarySetup()
{
    global $sharedInstallConfig;

    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "setup", "installed", "1");
}

function completeVerificationStage()
{
    global $sharedInstallConfig;

    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "setup", "owner_verified", "1");
}

function completeDatabaseStage()
{
    global $sharedInstallConfig;

    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "setup", "database_installed", "1");
}

function uncompleteDatabaseStage()
{
    global $sharedInstallConfig;

    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "setup", "database_installed", "0");
}

function finalizeInstall()
{
    global $sharedInstallConfig;

    $sharedInstallConfig->setKey(ID_GLOBAL_CONFIG, "setup", "install_finalized", "1");
}