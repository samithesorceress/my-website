<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions.php");

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
			$sql = prepareSQL("select", $table, false, $sql_where);
		//all others
		} elseif (empty($_REQUEST) === false) {
			require_once($root . "core/functions/getValues.php");

			//single result
			if (valExists("id", $data)) {
				$sql_where["id"] = $data["id"];
				$sql = prepareSQL("select", $table, false, $sql_where);
			// paginate
			} else {
				if (valExists("type", $data)) {
					$sql_where["type"] = $data["type"];
				}
				if (valExists("order_by",$data)) {
					$sql_order["by"] = $data["order_by"];
					if (valExists("order_dir", $data)) {
						$sql_order["dir"] = $data["order_dir"];
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
				$sql_limit["start"] = $pagination_start;
				$sql_limit["end"] = $pagination_end;
				$sql = prepareSQL("select", $table, false, $sql_where, $sql_order, $sql_limit);
			}
		} else {
			$output["message"] = "No arguments provided.";
		}
		if ($sql) {
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
			}
		}
	} else {
		$output["message"] = "Requested table does not exist.";
	}
} else {
	$output["message"] = "What are you listing? add /media, /photoset etc";
}

// output results
$output = json_encode($output);
echo $output;
die();