<?php
jsLogs("routing...");
$subdirectories = explode("/", $current_path);
switch($subdirectories[0]) {
	case "":
		$document_title = "Homepage";
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
	case "admin":
		require_once($php_root . "core/checkLogin.php");
		break;
	case "about":
		require_once($php_root . "views/about.php");
		break;
	case "contact":
		require_once($php_root . "views/contact.php");
		break;
	case "video":
	case "videos":
	case "photoset":
	case "photosets":
	case "store":
		$current_category = $subdirectories[0];
		if ($current_category !== "store") {
			$current_category = rtrim($current_category, "s") . "s";
		}
		$document_title = ucWords($current_category);
		if (count($subdirectories) > 1 && $subdirectories[1] !== "") {
			$current_url_slug = $subdirectories[1];
			require_once($php_root . "views/product.php");
		} else {
			require_once($php_root . "views/category.php");
		}
		break;
	default:
		$document_title = "404 - Not Found";
		require_once($php_root . "views/404.php");
}