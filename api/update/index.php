<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");
	$table = false;
	$cwd = $_SERVER['REQUEST_URI'];
	$subdirectories = explode("sami-the-sorceress/", $cwd);
	$subdirectories = explode("?", $subdirectories[1]);
	$subdirectories = explode("/", $subdirectories[0]);
	$type =	$subdirectories[2];
	if ($type) {
		switch($type) {
			case "about":
			case "media":
			case "videos":
			case "photosets":
			case "slides":
				$table = $type;
				break;
			case "photoset":
			case "video":
			case "slide":
				$table = $type . "s";
				break;
			case "store-item":
			case "store-items":
			case "storeItem":
			case "storeItems":
				$table = "store";
				break;
		}

		if ($table) {
			$sql_upd .= "`" . $table . "`";

			//about
			if ($table == "about") {
				$sql_whr .= " `id`='1'";
				if (valExists("profile", $data)) {
					$sql_set .= " `profile`='" . $data["profile"] . "',";
				}
				if (valExists("bio", $data)) {
					$sql_set .= " `bio`='" . $data["bio"] . "',";
				}
				if (valExists("links", $data)) {
					$sql_set .= " `links`='" . $data["links"] . "',";
				}
			//all others
			} else {
				if (valExists("id", $data)) {
					$sql_whr .= " `id`='" . $data["id"] . "'";
				} elseif (valExists("src", $data)) {
					$sql_whr .= " `src`='" . $data["src"] . "'";
				}
			}

			$sql_set = rtrim($sql_set, ",");
			$sql = $sql_upd . $sql_set . $sql_whr;

			if ($conn->query($sql)) {
				$output["success"] = true;
				$output["message"] = $table . " item updated";
			} else {
				$output["success"] = false;
				$output["message"] = "failed to update: " . $sql;
			}
		}
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();