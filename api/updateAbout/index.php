<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");

	$sql_upd .= "`about` SET ";
	$sql_whr .= " `id`='1'";

	if (valExists("profile", $data)) {
		$sql_upd .= "`profile`='" . $data["profile"] . "',";
	}
	if (valExists("bio", $data)) {
		$sql_upd .= "`bio`='" . $data["bio"] . "',";
	}
	if (valExists("links", $data)) {
		$sql_upd .= "`links`='" . $data["links"] . "',";
	}

	$sql_upd = rtrim($sql_upd, ",");
	$sql = $sql_upd . $sql_whr;

	if ($conn->query($sql)) {
		$output["success"] = true;
		$output["message"] = "about details updated";
	} else {
		$output["success"] = false;
		$output["message"] = "failed to update: " . $sql;
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();