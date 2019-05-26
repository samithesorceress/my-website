<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	$table = false;
	$type =	$subdirectories[2];
	if ($type) {
		switch($type) {
			case "media":
				$table = $type;
				break;
			case "photoset":
			case "video":
				$table = $type . "s";
				break;
			case "store-item":
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
		
				$errs = 0;
				foreach($ids as $id) {
					$sql_whr = " WHERE `id`='" . $id . "'";
					$sql = $sql_del . $sql_whr;
					if (!$conn->query($sql)) {
						$errs += 1;
					}
				}
		
				if (!$errs) {
					$output["success"] = true;
					$output["message"] = "Item(s) deleted suceessfully.";
					$output["data"] = $ids;
				} else {
					$output["message"] = $errs . " sql statements failed.";
				}
		
			} else {
				$output["message"] = "Please supply an ID to delete.";
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