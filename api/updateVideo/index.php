<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");
	if (valExists("id", $data)) {
		$sql_upd .= "`videos` SET ";
		$sql_whr .= " `id`='1'";

		if (valExists("cover", $data)) {
			$sql_upd .= "`cover`='" . $data["cover"] . "',";
		}
		if (valExists("preview", $data)) {
			$sql_upd .= "`preview`='" . $data["preview"] . "',";
		}
		if (valExists("title", $data)) {
			$sql_upd .= "`title`='" . $data["title"] . "',";
		}
		if (valExists("description", $data)) {
			$sql_upd .= "`description`='" . $data["description"] . "',";
		}
		if (valExists("tags", $data)) {
			$sql_upd .= "`tags`='" . $data["tags"] . "',";
		}
		if (valExists("price", $data)) {
			$sql_upd .= "`price`='" . $data["price"] . "',";
		}
		if (valExists("publish_date", $data)) {
			$sql_upd .= "`publish_date`='" . $data["publish_date"] . "',";
		}
		if (valExists("public", $data)) {
			$sql_upd .= "`public`='" . $data["public"] . "',";
		}

		$sql_upd = rtrim($sql_upd, ",");
		$sql = $sql_upd . $sql_whr;

		if ($conn->query($sql)) {
			$output["success"] = true;
			$output["message"] = "video updated";
		} else {
			$output["message"] = "failed to update: " . $sql;
		}
	} else {
	$output["message"] = "Please provide and ID to update";
	}
} else {
	$output["message"] = "No arguments provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();