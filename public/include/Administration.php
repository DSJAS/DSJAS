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

require_once "Customization.php";
require_once "Util.php";

require_once "vendor/requests/library/Requests.php";


function getAdministrationNotices()
{
    $fileContent = file_get_contents(ABSPATH . "/admin/data/AdminNotices.json");
    $json = json_decode($fileContent, true);

    return $json;
}

function addAdministrationNotice($noticeName, $noticeTitle, $noticeBody, $actionLink = "", $actionLinkLabel = "More info", $style = 0)
{
    $existingNotices = json_decode(file_get_contents(ABSPATH . "/admin/data/AdminNotices.json"), true);

    $existingNotices[$noticeName]["title"] = $noticeTitle;
    $existingNotices[$noticeName]["body"] = $noticeBody;
    $existingNotices[$noticeName]["action-link"] = $actionLink;
    $existingNotices[$noticeName]["action-link-text"] = $actionLinkLabel;
    $existingNotices[$noticeName]["style"] = $style;

    $finalJson = json_encode($existingNotices);

    $file = fopen(ABSPATH . "/admin/data/AdminNotices.json", "w+");

    if ($file !== false) {
        ftruncate($file, 0);
        fwrite($file, $finalJson);
        fclose($file);
    }
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
            break;

        default:
            $style = "alert alert-info";
            break;
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

function validateThemeUpload()
{
    $submission = $_FILES["themeFile"];

    $validation = doThemeUploadValidation();
    if (!$validation[0]) {
        return $validation;
    }

    if (!isUploadArchive($submission["tmp_name"])) {
        return [false, -1];
    }
}

function getInstallErrorPage($error)
{
    switch ($error) {
    case -1:
        return "/admin/settings/mod.php?notArchive";

    case -2:
        return "/admin/settings/mod.php?unknownError";

    case 1:
        return "/admin/settings/mod.php?malformedRequest";

    case 2:
        return "/admin/settings/mod.php?noFile";

    case 3:
        return "/admin/settings/mod.php?unknownError";

    case 4:
        return "/admin/settings/mod.php?sizeError";

    case 5:
        return "/admin/settings/mod.php?missingManifest";

    case 6:
        return "/admin/settings/mod.php?malformedManifest";

    case 7:
        return "/admin/settings/mod.php?alreadyExists";

    case 8:
        return "/admin/settings/mod.php?wrongType";

    default:
        return "/admin/settings/mod.php?unknownError";
    }
}

function validateModuleUpload()
{
    $submission = $_FILES["moduleFile"];

    $validation = doModuleUploadValidation();
    if (!$validation[0]) {
        return $validation;
    }

    if (!isUploadArchive($submission["tmp_name"])) {
        return [false, -1];
    }
}

function doModuleUploadValidation()
{
    if (!isset($_FILES['moduleFile']['error']) 
        || is_array($_FILES['moduleFile']['error'])
    ) {
        return [false, 1];
    }

    switch ($_FILES['moduleFile']['error']) {
    case UPLOAD_ERR_OK:
        break;
    case UPLOAD_ERR_NO_FILE:
        return [false, 2];
    case UPLOAD_ERR_INI_SIZE:
        return [false, 4];
    case UPLOAD_ERR_FORM_SIZE:
        return [false, 4];
    default:
        return [false, -2];
    }

    if ($_FILES['moduleFile']['size'] > 50000000) {
        return [false, 4];
    }

    return [true, 0];
}

function doThemeUploadValidation()
{
    if (!isset($_FILES['themeFile']['error']) 
        || is_array($_FILES['themeFile']['error'])
    ) {
        return [false, 1];
    }

    switch ($_FILES['themeFile']['error']) {
    case UPLOAD_ERR_OK:
        break;
    case UPLOAD_ERR_NO_FILE:
        return [false, 2];
    case UPLOAD_ERR_INI_SIZE:
        return [false, 4];
    case UPLOAD_ERR_FORM_SIZE:
        return [false, 4];
    default:
        return [false, -2];
    }

    // You should also check filesize here.
    if ($_FILES['themeFile']['size'] > 50000000) {
        return [false, 4];
    }

    return [true, 0];
}

function isUploadArchive($uploadPath)
{
    $fh = @fopen($uploadPath, "r");

    if (!$fh) {
        return false;
    }

    $blob = fgets($fh, 5);

    fclose($fh);

    if (strpos($blob, 'PK') !== false) {
        return true;
    } else {
        return false;
    }
}

function unpackDirExists()
{
    return is_dir(ABSPATH . "/uploads/themeUploads/");
}

function moduleUnpackDirExists()
{
    return is_dir(ABSPATH . "/uploads/moduleUploads/");
}

function downloadThemeFromURL($url)
{
    Requests::register_autoloader();

    try {
        $downloadRequest = Requests::get($url);
        $fileContent = $downloadRequest->body;
    } catch (Requests_Exception $e) {
        return false;
    }

    if (!$downloadRequest->success) {
        return false;
    }

    if (!unpackDirExists()) {
        mkdir(ABSPATH . "/uploads/themeUploads", 0777, true);
        chmod(ABSPATH . "/uploads/themeUploads", 0777);
    }

    $fileName = ABSPATH . "/uploads/themeUploads/" . sha1($fileContent) . ".zip";

    file_put_contents($fileName, $fileContent);

    if (!isUploadArchive($fileName)) {
        return false;
    }

    return $fileName;
}


function unpackAndInstallTheme($themeFile, $uploadedFile = true)
{
    if (!unpackDirExists()) {
        mkdir(ABSPATH . "/uploads/themeUploads", 0777, true);
        chmod(ABSPATH . "/uploads/themeUploads", 0777);
    }

    $fileHash = sha1_file($themeFile);
    $fileName = ABSPATH . "/uploads/themeUploads/" . $fileHash . ".zip";

    if ($uploadedFile) {
        if (!move_uploaded_file(
            $themeFile,
            $fileName
        )
        ) {
            return [false, -2];
        }
    } else {
        rename($themeFile, $fileName);
    }

    mkdir(ABSPATH . "/uploads/themeUploads/" . $fileHash . "-unpack", 0777, true);
    chmod(ABSPATH . "/uploads/themeUploads/" . $fileHash . "-unpack", 0777);

    $archive = new ZipArchive();
    $archive->open($fileName);
    $archive->extractTo(ABSPATH . "/uploads/themeUploads/" . $fileHash . "-unpack");
    $archive->close();

    $configExists = is_file(ABSPATH . "/uploads/themeUploads/" . $fileHash . "-unpack/" . ".dsjasManifest");

    if (!$configExists) {
        return [false, 5];
    }

    $configData = file_get_contents(ABSPATH . "/uploads/themeUploads/" . $fileHash . "-unpack/" . ".dsjasManifest");
    $parsedConfigData = json_decode($configData, true);

    if ($parsedConfigData === null) {
        return [false, 6];
    }

    $requestedName = $parsedConfigData["name"];
    $extensionType = $parsedConfigData["extension-type"];

    unlink(ABSPATH . "/uploads/themeUploads/" . $fileHash . "-unpack/.dsjasManifest");
    unlink($fileName);

    if ($extensionType != "theme") {
        return [false, 8];
    }

    $installedThemes = scandir(ABSPATH . "/admin/site/UI/");
    if (in_array($requestedName, $installedThemes)) {
        return [false, 7];
    }

    rename(ABSPATH . "/uploads/themeUploads/" . $fileHash . "-unpack/", ABSPATH . "/admin/site/UI/" . $requestedName);

    return [true, $requestedName];
}

function unpackAndInstallModule($themeFile, $uploadedFile = true)
{
    if (!moduleUnpackDirExists()) {
        mkdir(ABSPATH . "/uploads/moduleUploads", 0777, true);
        chmod(ABSPATH . "/uploads/moduleUploads", 0777);
    }

    $fileHash = sha1_file($themeFile);
    $fileName = ABSPATH . "/uploads/moduleUploads/" . $fileHash . ".zip";

    if ($uploadedFile) {
        if (!move_uploaded_file(
            $themeFile,
            $fileName
        )
        ) {
            return [false, 1];
        }
    } else {
        rename($themeFile, $fileName);
    }

    mkdir(ABSPATH . "/uploads/moduleUploads/" . $fileHash . "-unpack", 0777, true);
    chmod(ABSPATH . "/uploads/moduleUploads/" . $fileHash . "-unpack", 0777);

    $archive = new ZipArchive();
    $archive->open($fileName);
    $archive->extractTo(ABSPATH . "/uploads/moduleUploads/" . $fileHash . "-unpack");
    $archive->close();

    $configExists = is_file(ABSPATH . "/uploads/moduleUploads/" . $fileHash . "-unpack/" . ".dsjasManifest");

    if (!$configExists) {
        return [false, 5];
    }

    $configData = file_get_contents(ABSPATH . "/uploads/moduleUploads/" . $fileHash . "-unpack/" . ".dsjasManifest");
    $parsedConfigData = json_decode($configData, true);

    if ($parsedConfigData === null) {
        return [false, 6];
    }

    $requestedName = $parsedConfigData["name"];
    $extensionType = $parsedConfigData["extension-type"];

    unlink(ABSPATH . "/uploads/moduleUploads/" . $fileHash . "-unpack/.dsjasManifest");
    unlink($fileName);

    if ($extensionType != "module") {
        return [false, 8];
    }

    $installedModules = scandir(ABSPATH . "/admin/site/modules/");
    if (in_array($requestedName, $installedModules)) {
        return [false, 7];
    }

    rename(ABSPATH . "/uploads/moduleUploads/" . $fileHash . "-unpack/", ABSPATH . "/admin/site/modules/" . $requestedName);

    return [true, $requestedName];
}

function updateValidatorTimestamp($timeStamp = 0)
{
    $useTimestamp = ($timeStamp === 0) ? time() : $timeStamp;

    $configuration = new Configuration(false, true, false, false);
    $configuration->setKey(ID_THEME_CONFIG, "validation", "last_validation_timestamp", $useTimestamp);
}

function updateValidatorState($result)
{
    $configuration = new Configuration(false, true, false, false);
    $configuration->setKey(ID_THEME_CONFIG, "validation", "last_validation", $result);
}

function resetValidatorState()
{
    $configuration = new Configuration(false, true, false, false);
    $configuration->setKey(ID_THEME_CONFIG, "validation", "last_validation", "never_run");
    $configuration->setKey(ID_THEME_CONFIG, "validation", "last_validation_timestamp", "0");
}

function enableTheme($themeName)
{
    $configuration = new Configuration(false, true, false, false);
    $configuration->setKey(ID_THEME_CONFIG, "config", "use_default", "0");
    $configuration->setKey(ID_THEME_CONFIG, "extensions", "current_UI_extension", $themeName);
}

function enableDefaultTheme()
{
    $configuration = new Configuration(false, true, false, false);
    $configuration->setKey(ID_THEME_CONFIG, "config", "use_default", "1");
    $configuration->setKey(ID_THEME_CONFIG, "extensions", "current_UI_extension", "");
}

function enableModule($moduleName)
{
    $configuration = new Configuration(false, true, false, false);
    $configuration->setKey(ID_MODULE_CONFIG, "active_modules", $moduleName, "1");
}

function themeExists($themeName)
{
    return is_dir(ABSPATH . "/admin/site/UI/" . $themeName);
}

function moduleExists($moduleName)
{
    return is_dir(ABSPATH . "/admin/site/modules/" . $moduleName);
}

function uninstallTheme($themeName)
{
    enableDefaultTheme();

    recursiveDeleteDirectory(ABSPATH . "/admin/site/UI/" . $themeName);
}

function uninstallModule($moduleName)
{
    $configuration = new Configuration(false, true, false, false);
    $configuration->setKey(ID_MODULE_CONFIG, "active_modules", $moduleName, "0");

    recursiveDeleteDirectory(ABSPATH . "/admin/site/modules/" . $moduleName);
}
