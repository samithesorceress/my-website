<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

$sql_sel .= "`about`";

$sql = $sql_sel;
$rows = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$rows[] = $row;
	}
}
if ($rows) {
	$output["success"] = true;
	$output["data"] = $rows[0];
} else {
	$output["success"] = false;
}

// output results
$output = json_encode($output);
echo $output;
die();