<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");

	if (valExists("img", $data)) {
		$sql_ins .= "`slides` (img";
		$sql_vals .= "'" . $data["img"] . "'";
		
		if (valExists("text", $data)) {
			$sql_ins .= ",text";
			$sql_vals .= ",'" . $data["text"] . "'";
			
			if (valExists("url", $data)) {
				$sql_ins .= ",url";
				$sql_vals .= ",'" . $data["url"] . "'";

				$sql_ins .= ",public)";
				if (valExists("public", $data)) {
					if ($data["public"] == true) {
						$sql_vals .= ",'1')";
					} else {
						$sql_vals .= ",'0')";
					}
				} else {
					$sql_vals .= ",'1')";
				}
				$sql = $sql_ins . $sql_vals;
				
				//perform sql
				if ($conn->query($sql)) {
					$output["success"] = true;
					$output["message"] = "New slide created";
				} else {
					$output["success"] = false;
					$output["message"] = "Query failed: " . $sql;
				}

			} else {
				$output["success"] = false;
				$output["message"] = "Text required.";
			}
		} else {
			$output["success"] = false;
			$output["message"] = "Text required.";
		}
	} else {
		$output["success"] = false;
		$output["message"] = "No image provided.";
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided";
}









// output results
$output = json_encode($output);
echo $output;
die();