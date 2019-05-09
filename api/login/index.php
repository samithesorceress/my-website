<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions/security.php");
require_once($root . "core/functions/valExists.php");

if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");

	if (valExists("email", $data) && valExists("password", $data)) {

		// Lookup DB data for provided email
		$sql = $sql_sel . "`users` WHERE `email`='" . $data["email"] . "'";
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

		// Prepare PW hash to match against
		$pass_hash = hash('sha256', $data["password"] . $rows["salt"]);

		//	Verify password in exchange for token
		if ($pass_hash == $rows["password"]) {
			$output["success"] = true;
			$output["message"] = "correct email and password combo";
			$output["username"] = $rows["username"];
			//make this hash something else 4 production lol
			$output["token"] = hash('sha256', $rows["token"] . $rows["salt"]);
			$output["user_data"] = $res;
		} else {
			$output["success"] = false;
			$output["message"] = "Bad credentials.";
		}
	} else {
		$output["success"] = false;
		$output["message"] = "Email and password required.";
	}
} else {
	$output["success"] = false;
	$output["message"] = "No arguments provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();