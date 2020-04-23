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

require_once("Customization.php");
require_once("Util.php");

define("THEME_PATH", "/admin/site/UI/");
define("DEFAULT_THEME", "default");

define("THEME_BOOTSTRAP_FILE", "Bootstrap.php");


function shouldProcessPermalink()
{
    return isset($_GET["permalink"]);
}

function processPermalink($url)
{
    $result = array();

    $result[0] = basename($url);
    $result[1] = basename(dirname($url)) . "/";

    if (!endsWith($result[0], ".php")) {
        $result[0] .= ".php";
    }

    return $result;
}

function fixGetHeaders($urlGetValues)
{
    $params = explode("&", $urlGetValues);

    foreach ($params as $param) {
        $key_value = explode("=", $param);

        if (count($key_value) < 2) {
            return;
        }

        $_GET[$key_value[0]] = $key_value[1];
    }
}

function stripGetHeaders($url)
{
    $splitUrl = explode("?", $url);

    if (count($splitUrl) < 2) {
        return $url;
    }

    return $splitUrl[0];
}

function getGetHeaders($url)
{
    $splitUrl = explode("?", $url);
    $splitHeaders = explode("&", $splitUrl[1]);

    return $splitHeaders;
}

function getGetHeadersString($url)
{
    $splitUrl = explode("?", $url);

    if (count($splitUrl) < 2) {
        return "";
    }

    return $splitUrl[1];
}

function shouldRedirectToReal($url)
{
    $realUrl = stripGetHeaders($url);

    $abspathPhp = $_SERVER["DOCUMENT_ROOT"] . $realUrl . ".php";

    $realFilePhp = file_exists($abspathPhp);

    return $realFilePhp;
}

function redirectToReal($url)
{
    $headers = getGetHeadersString($url);

    if (strlen($headers) > 0) {
        $redirectLocation = stripGetHeaders($url) . ".php?" . $headers;
    } else {
        $redirectLocation = stripGetHeaders($url) . ".php";
    }

    header("Location: $redirectLocation");
}

class Theme
{

    public $validTheme;

    private $fileName;
    private $themeName;

    private $themePath;
    private $bootstrapPath;

    private $themeText;
    private $bootstrapText;

    private $themeLoaded = false;

    function __construct($fileName, $dirname, $themeName)
    {
        echo ($this->getHtmlWrappers());

        $this->fileName = $dirname . basename($fileName);
        $this->themeName = $themeName;
        $this->bootstrapPath = ABSPATH . THEME_PATH . $this->themeName . "/" . THEME_BOOTSTRAP_FILE;

        $this->validTheme = $this->validateTheme();
        $this->handleValidationResult();

        $this->themePath = ABSPATH . THEME_PATH . $this->themeName . "/" . $this->fileName;
    }

    function __destruct()
    {
        echo ($this->getHtmlWrappers(1));
    }

    function __get($property)
    {
        return $this->property;
    }

    function __set($property, $val)
    {
        $this->property = $val;
    }

    function loadTheme()
    {
        if ($this->providesBootstrapFile()) {
            require($this->bootstrapPath);
            $this->bootstrapText = getBootstrap();
        }

        if (!$this->themeLoaded) {
            require($this->themePath);
            $this->themeText = getTheme();
            $this->themeLoaded = true;
        }
    }

    function unloadTheme()
    {
        if ($this->themeLoaded) {
            $this->themeText = null;
            $this->themeLoaded = false;
        }
    }

    function displayTheme()
    {
        if ($this->themeLoaded) {
            echo ($this->bootstrapText);
            echo ($this->themeText);
        } else {
            echo ("<strong>Error: </strong> Theme not loaded");
        }
    }


    private function getHtmlWrappers($index = 0)
    {
        if ($index == 0) {
            return "<html>";
        } else {
            return "</html>";
        }
    }

    private function validateTheme()
    {
        if (strlen($this->themeName) < 1 || $this->themeName == null) {
            return false;
        }

        // Check if theme directory is present on disk
        if (!file_exists(ABSPATH . THEME_PATH . $this->themeName)) {
            return false;
        }

        // Check if file is part of theme
        if (!file_exists(ABSPATH . THEME_PATH . $this->themeName . "/" . $this->fileName)) {
            return false;
        }

        return true;
    }

    private function providesBootstrapFile()
    {
        return file_exists($this->bootstrapPath);
    }

    private function handleValidationResult()
    {
        if (!$this->validTheme) {
            if (!file_exists(ABSPATH . THEME_PATH . $this->themeName)) {
                $this->themeName = "default";
            } elseif (strlen($this->themeName) < 1 || $this->themeName == null) {
                $this->themeName = "default";
            } elseif (!file_exists(ABSPATH . THEME_PATH . $this->themeName . "/" . $this->fileName)) {
                $this->fileName = "Error.php";
            }
        }
    }
}
