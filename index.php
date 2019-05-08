<?php
// Enable Error Reporting
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Init variables
$php_root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/";
$htp_root = "http://127.0.0.1/sami-the-sorceress/";

// Defaults
$document_title = "Sami the Sorceress";
$document_url = $htp_root;
$last_updated = "2019-4-1";
$document_version = 1.0;
$favicon = $htp_root . "favicon.ico";
$robots_txt = "NOFOLLOW NOINDEX";
$fonts = "Quicksand:400,500";

// Functions
require_once($php_root . "core/functions.php");
jsLogs("hello world");
// Find where we are
require_once($php_root . "core/headers.php");

// Verify user
require_once($php_root . "core/checkLogin.php");