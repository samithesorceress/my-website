<?php
jsLogs("routing...");
$route = true;

$subdirectories = explode("/", $current_path);
switch($subdirectories[0]) {
	case "":
		$document_title = "Dashboard";
		require_once($php_root . "views/dashboard.php");
		break;
	case "login":
		header("Location: " . $htp_root);
		break;
	case "logout":
		$document_title = "Logout";
		require_once($php_root . "views/logout.php");
		break;
	case "new":
		$document_title = "New";
		require_once($php_root . "views/new.php");
		break;
	default:
		$document_title = "404 - Not Found";
		require_once($php_root . "views/404.php");
}