<?php
jsLogs("routing...");
$subdirectories = explode("/", $current_path);
switch($subdirectories[0]) {
	case "":
		$document_title = "Sami the Sorceress · Homepage";
		require_once($php_root . "views/homepage.php");
		break;
	case "login":
		$document_title = "Login";
		require_once($php_root . "views/login.php");
		break;
	case "logout":
		$document_title = "Logout";
		require_once($php_root . "views/logout.php");
		break;
	default:
	if (strpos($current_path, "admin") === false) {
		$document_title = "404 - Not Found";
		require_once($php_root . "views/404.php");
	}
}