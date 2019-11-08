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
			$sql_del .= "`" . $table . "`";

			require_once($root . "core/functions/getValues.php");
			if (valExists("id", $data)) {

				if (strpos($data["id"], ",") !== false) {
					$ids = explode(",", $data["id"]);
				} else {
					$ids = [$data["id"]];
				}
		
				$errs = [];
				foreach($ids as $id) {
					$sql_whr = " WHERE `id`='" . $id . "'";
					$sql = $sql_del . $sql_whr;
					if (!$conn->query($sql)) {
						$errs[] = $id;
					}
				}
		
				if (!count($errs)) {
					$output["success"] = true;
					$output["message"] = "Item(s) deleted sucessfully.";
					$output["data"] = $ids;
				} else {
					$output["message"] = count($errs) . " sql statements failed.";
					$output["data"] = $errs;
				}
		
			} elseif(valExists("src", $data)) {
				if (strpos($data["src"], ",") !== false) {
					$srcs = explode(",", $data["src"]);
				} else {
					$srcs = [$data["src"]];
				}
		
				$errs = [];
				foreach($srcs as $src) {
					$sql_whr = " WHERE `src`='" . $src . "'";
					$sql = $sql_del . $sql_whr;
					if (!$conn->query($sql)) {
						$errs[] = $src;
					}
				}
				if (!count($errs)) {
					$output["success"] = true;
					$output["message"] = "Item(s) deleted sucessfully.";
					$output["data"] = $srcs;
				} else {
					$output["message"] = count($errs) . " sql statements failed.";
					$output["data"] = $errs;
				}
			} else {
				$output["message"] = "Please supply an ID or SRC to delete.";
			}
			
		} else {
			$output["message"] = "Invalid type, no table exists for this item.";
		}

	} else {
		$output["message"] = "What are you deleting? add /media, /photoset etc";
	}

} else {
	$output["message"] = "No arguments provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();