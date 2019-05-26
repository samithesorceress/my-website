<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");

	$sql_sel .= "`media`";
	

	if (valExists("id", $data) || valExists("src", $data)) {
		if (valExists("id", $data)) {
			$sql_sel .= " WHERE `id`='" . $data["id"] . "'";
		} else {
			$sql_sel .= " WHERE `src`='" . $data["src"] . "'";
		}
		$rows = array();
		$result = $conn->query($sql_sel);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		$row = $rows[0];
		$output["data"]["id"] = $row["id"];
		$output["data"]["src"] = $row["src"];
		$output["data"]["ext"] = $row["ext"];

		//
		if (valExists("title", $data) || valExists("alt", $data)) {
			$sql_upd .= "`media` SET `title`='" . $data["title"] . "', `alt`='" . $data["alt"] . "'";
			$sql_whr .= "`src`='" . $data["src"] ."'";
			$sql = $sql_upd . $sql_whr;
			if ($conn->query($sql)) {
				$output["success"] = true;
				$output["message"] = "metadata updated";
			} else {
				$output["success"] = false;
				$output["message"] = "failed to update: " . $sql;
			}
		} else {
			$output["success"] = false;
			$output["message"] = "nothing to update";
		}

	} else {
		$output["success"] = false;
		$output["message"] = "plz provide 'src' value";
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();