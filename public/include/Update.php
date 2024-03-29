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

require ABSPATH . INC . "vendor/requests/library/Requests.php";


define("RELEASES_ENDPOINT", "https://api.github.com/repos/DSJAS/DSJAS/releases");
define("ARCHIVE_ENDPOINT", "https://github.com/DSJAS/DSJAS/archive");

/* Used for caching loaded data.
 * Caching API results reduces likelyhood of ratelimits and increases
 * performance, as the GitHub API is much slower than this page.
 */
static $__current_release = null;
static $__releases_cache = null;

static $__version_information;

static $dummy_release = [
    "id" => 0,
    "tag_name" => "0.0.0",
    "name" => "",
    "body" => "",
    "prerelease" => true,

    "url" => "https://github.com/DSJAS/DSJAS/releases",

    "author" => array("login" => "ejv2", "url" => "https://github.com/ejv2"),
    "assets" => array(["name" => "dummy asset"]),
];

/*
 * Release holds all the pertinent data for DSJAS updates as returned by the GitHub API.
 * It is constructed from the associative array returned when parsing JSON from the API
 * response.
 */
class Release
{
    private $id;
    private $tag;
    private $name;
    private $notes;
    private $pre;

    private $url;
    private $human_url;

    private $author;
    private $author_url;

    private $is_stable;
    private $is_beta;
    private $is_alpha;

    private $stable_zip = "";
    private $beta_zip = "";
    private $alpha_zip = "";

    /*
     * Construct a new release object from the parsed JSON response $r.
     */
    public function __construct($r)
    {
        if ($r === null)
            throw new InvalidArgumentException("cannot parse from NULL JSON response");
        if (gettype($r) === "string") {
            $this->tag = "0.0.0";
            return;
        } else if (gettype($r) === "array" && count($r) == 4) {
            $this->tag = $r[0] . "." . $r[1] . "." . $r[2] . "-" . $r[3];
            return;
        }


        $this->id = $r["id"];
        $this->tag = $r["tag_name"];
        $this->name = $r["name"];
        $this->notes = $this->processPatchNotes($r["body"]);
        $this->pre = $r["prerelease"];

        $this->url = $r["url"];
        $this->human_url = $r["html_url"];

        $this->author = $r["author"]["login"];
        $this->author_url = $r["author"]["url"];

        $assets = $r["assets"];
        foreach ($assets as $a) {
            switch ($a["name"]) {
            case "DSJAS-release-alpha.zip":
                $this->alpha_zip = $a["browser_download_url"];
                break;
            case "DSJAS-release-beta.zip":
                $this->beta_zip = $a["browser_download_url"];
                break;
            case "DSJAS-release-stable.zip":
                $this->stable_zip = $a["browser_download_url"];
                break;
            }
        }

        $this->is_stable = ($this->stable_zip != "");
        $this->is_beta = ($this->beta_zip != "");
        $this->is_alpha = ($this->alpha_zip != "");
    }

    private function processPatchNotes($notes)
    {
        /* proper line endings */
        $work = str_replace("\r\n", "\n<br>\n", $notes);

        /* blockquotes */
        $work = preg_replace("/\n> (.*)\n<br>\n/", '<blockquote>${1}</blockquote>', $work);

        /* headers */
        $work = preg_replace("/\n###(.*)\n<br>\n/", '<strong>${1}</strong>', $work);
        $work = preg_replace("/\n##(.*)\n<br>\n/", '<h5>${1}</h5>', $work);
        $work = preg_replace("/\n?#(.*)\n<br>\n/", '<h5>${1}</h5>', $work);

        /* embedded formatting */
        $work = preg_replace("/\*\*([^\*]*)\*\*/", '<strong>${1}</strong>', $work);
        $work = preg_replace("/``([^`]*)``/", '<code>${1}</code>', $work);

        return $work;
    }

    public function getBand()
    {
        $segs = explode("-", $this->tag);

        if (count($segs) == 1)
            return "stable";

        return $segs[1];
    }

    private function verseg($seg)
    {
        $halves = explode("-", $this->tag);
        $first = $halves[0];

        $segs = explode(".", $first);
        if ($seg > count($segs))
            return "0";

        return $segs[$seg];
    }

    public function getMajor()
    {
        return $this->verseg(0);
    }

    public function getMinor()
    {
        return $this->verseg(1);
    }

    public function getPatch()
    {
        return $this->verseg(2);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPatchNotes()
    {
        return $this->notes;
    }

    public function getAPILink()
    {
        return $this->url;
    }

    public function getLink()
    {
        return $this->human_url;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAuthorLink()
    {
        return $this->author_url;
    }

    public function toString()
    {
        return $this->tag;
    }

    private function toBandVal($band)
    {
        switch ($band)
        {
        case "stable":
            return 2;
        case "beta":
            return 1;
        case "alpha":
            return 0;
        default:
            return -1;
        }
    }

    public function matchesBand($current)
    {
        return $this->toBandVal($this->getBand()) >= $this->toBandVal($current);
    }

    private function toValue($maj, $min, $pat)
    {
        $ver = (double)$maj * 1000;
        $ver += (double)$min * 10;
        $ver += (double)$pat;

        return $ver;
    }

    public function laterThan($maj, $min, $pat)
    {
        return $this->toValue($this->getMajor(), $this->getMinor(), $this->getPatch())
            > $this->toValue($maj, $min, $pat);

        return false;
    }

    public function laterThanRelease($rel)
    {
        return $this->laterThan($rel->getMajor(),
                                $rel->getMinor(),
                                $rel->getPatch());
    }

    public function isDummy()
    {
        return !$this->laterThan(0, 0, 0);
    }

    public function getDownload($band)
    {
        if ($band == "stable" && $this->is_stable) {
            return $this->stable_zip;
        } elseif ($band == "beta" && $this->is_beta) {
            return $this->beta_zip;
        } elseif ($band == "alpha" && $this->is_alpha) {
            return $this->alpha_zip;
        } else {
            return false;
        }
    }
}

/* Returns the UNIX timestamp time of when the API cooldown expires, or false if no cooldown is active */
function getTimeoutExpire()
{
    Requests::register_autoloader();

    $decoded = [];
    try {
        $resp = Requests::get(RELEASES_ENDPOINT);
        if ($resp->status_code == 403 &&
            isset($resp->headers["x-ratelimit-reset"])) {

            return $resp->headers["x-ratelimit-reset"];
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

function isRateLimited()
{
    return getTimeoutExpire() !== false;
}

/* Returns an array of Release objects parsed from the release API */
function getReleases()
{
    global $__releases_cache;

    /* return cached copy if available */
    if ($__releases_cache != null && count($__releases_cache) != 0)
        return $__releases_cache;


    /* else hit API */
    Requests::register_autoloader();

    $decoded = [];
    try {
        $json = Requests::get(RELEASES_ENDPOINT)->body;
        $decoded = json_decode($json, true);
    } catch (Exception $e) {
        return [];
    }

    $__releases_cache = [];
    foreach ($decoded as $d) {
        $__releases_cache[] = new Release($d);
    }

    return $__releases_cache;
}

function getCurrentRelease()
{
    global $__current_release;
    if ($__current_release != null)
        return $__current_release;

    $rel = getReleases();
    foreach ($rel as $r) {
        if ($r->getMajor() == getMajorVersion() &&
            $r->getMinor() == getMinorVersion() &&
            $r->getPatch() == getPatchVersion() &&
            $r->matchesBand(getUpdateBand())) {


            /* cache results */
            if ($__current_release == null) {
                $__current_release = $r;
            }
            return $r;
        }
    }

    /* return blank release with correct versions as fallback */
    return new Release(array(
        getMajorVersion(),
        getMinorVersion(),
        getPatchVersion(),
        getUpdateBand(),
    ));
}

function getMajorVersion()
{
    $info = loadVersionInfo();

    return $info["version"]["major"];
}

function getMinorVersion()
{
    $info = loadVersionInfo();

    return $info["version"]["minor"];
}

function getPatchVersion()
{
    $info = loadVersionInfo();

    return $info["version"]["patch"];
}

function getVersionName()
{
    return getCurrentRelease()->getName();
}

function getVersionDescription()
{
    return getCurrentRelease()->getPatchNotes();
}

function getUpdateBand()
{
    $info = loadVersionInfo();

    return $info["version-release-band"];
}

function getSemanticVersion()
{
    $info = loadVersionInfo();

    $major = $info["version"]["major"];
    $minor = $info["version"]["minor"];
    $patch = $info["version"]["patch"];
    $band = $info["version-release-band"];

    $ver = "$major.$minor.$patch-$band";

    return $ver;
}

function getVersionString()
{
    $info = loadVersionInfo();

    $major = $info["version"]["major"];
    $minor = $info["version"]["minor"];
    $patch = $info["version"]["patch"];

    $ver = "$major.$minor.$patch";

    return $ver;
}

function parseVersionString($version)
{
    $around = explode("-", $version);
    $version = explode(".", $around[0]);

    if (count($around) > 1) {
        $band = $around[1];
    } else {
        $band = "";
    }

    if (count($version) != 3) {
        $version = ["-1", "-1", "-1"];
    }

    return [$version[0], $version[1], $version[2], $band];
}

function getLatestAvailableVersion($band)
{
    $currentBand = getUpdateBand();
    $releases = getReleases();
    $latest = new Release([0, 0, 1, "alpha"]);

    if (count($releases) == 0) {
        return new Release([0, 0, 0, "alpha"]);
    }

    foreach ($releases as $r) {
        /* if any updates failed, return that */
        if ($r->isDummy())
            return $r;

        if ($r->matchesBand($currentBand) && $r->laterThanRelease($latest)) {
            $latest = $r;
        }
    }

    return $latest;
}

function isUpdateAvailable()
{
    $currentVersion = getVersionString();
    return getLatestAvailableVersion(getUpdateBand())->laterThan(
        getMajorVersion(),
        getMinorVersion(),
        getPatchVersion()
    );
}

function isInsiderBand()
{
    $band = getUpdateBand();

    if ($band == "stable" || $band == "") {
        return false;
    }

    return true;
}

function getArchiveLocation($major, $minor, $patch, $band)
{
    return sprintf("%s/%d.%d.%d-%s.tar.gz", ARCHIVE_ENDPOINT, $major, $minor, $patch, $band);
}

function loadVersionInfo()
{
    global $__version_information;

    if (!isset($__version_information) || $__version_information == null) {
        $jsonContent = file_get_contents(ABSPATH . "/Version.json");

        $__version_information = json_decode($jsonContent, true);

        return $__version_information;
    } else {
        return $__version_information;
    }
}