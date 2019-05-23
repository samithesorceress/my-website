<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");

	$sql_sel .= "`videos`";
	// single img
	if (valExists("id", $data)) {
		$sql_whr .= "`id`='" . $data["id"] . "'";
		$sql = $sql_sel . $sql_whr;
	// paginate
	} else {
		//order
		if (valExists("order_by",$data)) {
			$sql_ord = " ORDER BY " . $data["order_by"];
			if (valExists("order_dir", $data)) {
				$sql_ord .= " " . $data["order_dir"];
			}
		}
		$pagination_start = 0;
		$pagination_end = 5;
		if (valExists("offset", $data)) {
			$pagination_start = $data["offset"];
		}
		if (valExists("rows", $data)) {
			$pagination_end = (string)$pagination_start + ((int)$data["rows"] * 5);
		}
		if ($pagination_start) {
			$sql_lmt .= $pagination_start . ", ";
		}
		$sql_lmt .= $pagination_end;
		$sql = $sql_sel . $sql_ord . $sql_lmt;
	}
	$rows = array();
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	if (count($rows) == 1) {
		$rows = $rows[0];
	}
	if ($rows) {
		$output["success"] = true;	
		$output["data"] = $rows;
	} else {
		$output["message"] = "SQL Failed: " . $sql;
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();