<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");

	if (valExists("token", $data) && valExists("user", $data)) {

		// Lookup DB data for provided id
		$sql = $sql_sel . "`users` WHERE `username`='" . $data["user"] . "'";
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
		$res = json_encode($rows);

		// Prepare token to match against
		$gen_token = hash('sha256', $rows["token"] . $rows["salt"]);
		
		//Verify token 
		if ($data["token"] == $gen_token) {
			$output["success"] = true;
			$output["message"] = "Token verified.";
			$output["user_data"] = $res;
		} else {
			$output["success"] = false;
			$output["message"] = "Token missmatch! Invalid session should be deleted.";
		}
	} else {
		$output["success"] = false;
		$output["message"] = "ID and token required.";
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided.";
}


// output results
$output = json_encode($output);
echo $output;
die();