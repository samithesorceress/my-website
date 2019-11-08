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

		if ($table) {
			//about
			if ($table == "about") {
				$sql_where["id"] = 1;
			//all others
			} else {
				if (valExists("id", $data)) {
					$sql_where["id"] = $data["id"];
				} elseif(valExists("src", $data)) {
					$sql_where["src"] = $data["src"];
				}
			}
			foreach($data as $key => $val) {
				if ($key !== "id") {
					$sql_params[$key] = $val;
				}
			}
			$sql = prepareSQL("update", $table, $sql_params, $sql_where);
			if ($conn->query($sql)) {
				$output["success"] = true;
				$output["message"] = $table . " item updated";
				$sql = prepareSQL("select", $table, false, $sql_where);
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$rows[] = $row;
					}
				}
				if (count($rows) == 1) {
					$rows = $rows[0];
				}
				$output["data"] = $rows;
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