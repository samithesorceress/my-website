<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");
	$sql_params = [];
	$required = [
		"src",
		"ext",
		""
		"type"
	];




	if (valExists("src", $data)) {
		$sql_ins .= "`media` (src";
		$sql_vals .= "'" . $data["src"] . "'";
		if (valExists("title", $data)) {
			$sql_ins .= ",title";
			$sql_vals .= ",'" . $data["title"] . "'";
		}
		if (valExists("alt", $data)) {
			$sql_ins .= ",alt";
			$sql_vals .= ",'" . $data["title"] . "'";
		}
		if (valExists("type", $data)) {
			$sql_ins .= ",type,public)";
			$sql_vals .= ",'" . $data["type"] . "'";
			if (valExists("public", $data)) {
				$sql_vals .= ",'" . $data["public"] . "')";
			} else {
				$sql_vals .= ",'0')";
			}
			$sql = $sql_ins . $sql_vals;
			if ($conn->query($sql)) {
				$output["success"] = true;
				$output["message"] = "File Uploaded";
			} else {
				$output["success"] = false;
				$output["message"] = "Query failed: " . $sql;
			}
		} else {
			$output["success"] = false;
			$output["message"] = "Type and Visibility required.";
		}
	} else {
		$output["success"] = false;
		$output["message"] = "No image path provided.";
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided";
}









// output results
$output = json_encode($output);
echo $output;
die();