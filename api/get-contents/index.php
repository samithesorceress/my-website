<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

$path = false;

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");
	
	if (valExists("icon", $data)) {
		$path = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/src/icons/" . $data["icon"] . ".svg";
	}

	if ($path) {
		//echo urlencode(file_get_contents($path));
		echo file_get_contents($path);
	}
}