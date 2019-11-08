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
		case "store":
		case "stores":
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
				if (valExists("url", $data)) {
					$sql_where["url"] = $data["url"];
				}
				if (valExists("public", $data)) {
					$sql_where["public"] = $data["public"];
				}
				if (valExists("order_by",$data)) {
					$sql_order["by"] = $data["order_by"];
					if (valExists("order_dir", $data)) {
						$sql_order["dir"] = $data["order_dir"];
					}
				}
				$pagination_start = 0;
				$pagination_end = 3;
				if (valExists("offset", $data)) {
					$pagination_start = $data["offset"];
				}
				$num_rows = $pagination_end;
				if (valExists("rows", $data)) {
					$num_rows = ((int)$data["rows"] * 3);
					$pagination_end = (string)$pagination_start + $num_rows;
				}
				$sql_limit["start"] = $pagination_start;
				$sql_limit["end"] = $pagination_end;
				$sql = prepareSQL("select", $table, false, $sql_where, $sql_order, $sql_limit);
			}
		} else {
			$output["message"] = "No arguments provided.";
		}
		if ($sql) {
			$output["sql"] = $sql;
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
				if (!valExists("id", $data) && $table !== "about") {
					$output["pagination"] = [
						"prev" => false,
						"next" => false
					];
					if ($pagination_start > 0 ) {
						$prev = $pagination_start - $num_rows;
						if ($prev < 0) {
							$prev = 0;
						}
						$output["pagination"]["prev"] = $prev;
					}
					// find totals and next values
					$sql = "SELECT COUNT(*) FROM `" . $table . "`";
					$total = false;
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$total = $row["COUNT(*)"];
						}
						$output["total"] = $total;
					}
					if ($total && $total > $pagination_end) {
						$output["pagination"]["next"] = $pagination_end;
					}
				}
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