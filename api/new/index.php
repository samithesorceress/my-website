<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions.php");

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
		$sql_params = [];

		if ($table) {
			switch($table) {
				case "videos":
					$required = [
						"cover",
						"preview",
						"title",
						"description",
						"tags",
						"price",
						"publish_date"
					];
					break;
				case "slides":
					$required = [
						"cover",
						"title",
						"url"
					];
					break;
				default:
					$required = [
						"cover",
						"previews",
						"title",
						"description",
						"tags",
						"price",
						"publish_date"
					];
			}
			$missing = false;
			foreach($required as $field) {
				if (!valExists($field, $data)) {
					$missing = true;
				}
			}
			if (!$missing) {
				foreach($data as $key => $val) {
					$sql_params[$key] = $val;
				}
			}
		}
		$sql = prepareSQL("insert", $table, $sql_params);
		if ($conn->query($sql)) {
			$output["success"] = true;
			$output["message"] = "new " . $table . " item created";
		} else {
			$output["message"] = "failed to insert: " . $sql;
		}
	}
} else {
	$output["message"] = "No arguments provided.";
}



// output results
$output = json_encode($output);
echo $output;
die();