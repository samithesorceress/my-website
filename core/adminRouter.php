<?php
jsLogs("routing...");
$route = true;
if (strpos($current_path, "admin") !== false) {
	$subdirectories = explode("/", $current_path);
	if (count($subdirectories) < 2) {
		$document_title = "Dashboard";
		require_once($php_root . "views/dashboard.php");
	} else {
		switch($subdirectories[1]) {
			case "":
				$document_title = "Dashboard";
				require_once($php_root . "views/dashboard.php");
				break;
			case "new":
				$document_title = "New";
				switch($subdirectories[2]) {
					case "media":
						$document_title .= " Media";
						require_once($php_root . "views/new/media.php");
						break;
					case "slide":
						$document_title .= " Slide";
						require_once($php_root . "views/new/slide.php");
						break;
					case "video":
						$document_title .= " Video";
						require_once($php_root . "views/new/video.php");
						break;
					case "photoset":
						$document_title .= " Photoset";
						require_once($php_root . "views/new/photoset.php");
						break;
					case "store-item":
						$document_title .= " Store Item";
						require_once($php_root . "views/new/store-item.php");
						break;
				}
				break;
			case "edit":
				$document_title = "Edit";
				switch($subdirectories[1]) {
					case "about":
						$document_title .= " About";
						require_once($php_root . "views/edit/about.php");
						break;
					case "media":
						$document_title .= " Media";
						require_once($php_root . "views/edit/media.php");
						break;
					case "slide":
						$document_title .= " Slide";
						require_once($php_root . "views/edit/slide.php");
						break;
					case "video":
						$document_title .= " Video";
						require_once($php_root . "views/edit/video.php");
						break;
					case "photoset":
						$document_title .= " Photoset";
						require_once($php_root . "views/edit/photoset.php");
						break;
					case "store-item":
						$document_title .= " Store Item";
						require_once($php_root . "views/edit/store-item.php");
						break;
				}
				break;
			default:
				$document_title = "404 - Not Found";
				require_once($php_root . "views/404.php");
		}
	}
}