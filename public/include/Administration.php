<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

*/

function getAdministrationNotices()
{
    $fileContent = file_get_contents(ABSPATH . "/admin/data/AdminNotices.json");
    $json = json_decode($fileContent, true);

    return $json;
}

function purgeAdministrationNotices()
{
    $file = fopen(ABSPATH . "/admin/data/AdminNotices.json", "w+");

    if ($file !== false) {
        ftruncate($file, 0);
        fwrite($file, "{}");
        fclose($file);
    }
}

function handleAdminNotices($notices)
{
    if (count($notices) < 1) {
        return;
    }

    foreach ($notices as $notice) {
        switch ($notice["style"]) {
            case 0:
                $style = "alert alert-info";
                break;

            case 1:
                $style = "alert alert-primary";
                break;

            case 2:
                $style = "alert alert-secondary";
                break;

            case 3:
                $style = "alert alert-warning";
                break;

            case 4:
                $style = "alert alert-danger";
                break;

            case 5:
                $style = "alert alert-success";

            default:
                $style = "alert alert-info";
        }

        echo ("<div class=\"$style\" role=\"alert\">");

        echo ("<p><strong>" . $notice["title"] . "</strong> " . $notice["body"] . "</p>");
        echo ("<a href=\"" . $notice["action-link"] . "\">" . $notice["action-link-text"] . "</a>");

        echo ("</div>");
    }
}

function factoryReset(Configuration $config)
{
    $config->setKey(ID_GLOBAL_CONFIG, "database", "running_without_database", "0");
    $config->setKey(ID_GLOBAL_CONFIG, "database", "server_hostname", "localhost");
    $config->setKey(ID_GLOBAL_CONFIG, "database", "database_name", "DSJAS");
    $config->setKey(ID_GLOBAL_CONFIG, "database", "username", "DSJAS");
    $config->setKey(ID_GLOBAL_CONFIG, "database", "password", "");

    $config->setKey(ID_GLOBAL_CONFIG, "customization", "bank_name", "DSJAS");
    $config->setKey(ID_GLOBAL_CONFIG, "customization", "bank_domain", "https://djohnson.financial");

    $config->setKey(ID_GLOBAL_CONFIG, "settings", "allow_access_to_admin", "1");
}

function installReset(Configuration $config)
{
    $config->setKey(ID_GLOBAL_CONFIG, "setup", "install_finalized", "0");
    $config->setKey(ID_GLOBAL_CONFIG, "setup", "installed", "0");
    $config->setKey(ID_GLOBAL_CONFIG, "setup", "owner_verified", "0");
    $config->setKey(ID_GLOBAL_CONFIG, "setup", "database_installed", "0");
}
