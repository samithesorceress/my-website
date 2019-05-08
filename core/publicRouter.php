<?php
jsLogs("routing...");
switch($current_path) {
	case "":
	case "/":
		$document_title = "Sami the Sorceress : Homepage";
		require_once($php_root . "views/homepage.php");
		break;
	case "login":
		require_once($php_root . "views/login.php");
		break;
    default:
        $document_title = "404 - Not Found";
        require_once($php_root . "views/404.php");
}