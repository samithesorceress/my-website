<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions.php");

if (empty($_FILES) === false) {
	$output["message"] = "has files";
	echo $output;
die();
}
if (empty($_REQUEST) === false) {
	require_once($root . "core/functions/getValues.php");
	
	//check required
	$required = [
		"cover",
		"previews",
		"title",
		"description",
		"tags",
		"price",
		"publish_date"
	];
	$validation = checkRequired($required, $data);
	
	if (valExists("success", $validation)) {
		$fields = $required;
		$formatted_fields = "";
		$formatted_values = "";
		$sql_ins .= "`photosets` (";

		//check non-required values, to prepare correct sql command.
		if (valExists("public", $data)) {
			$fields[] = "public";
		}

		// loop and append all the inputs
		foreach($fields as $field) {
			$formatted_fields .= $field . ", ";
			$value = $data[$field];
			//encode urls
			if(strpos($value, 'http') !== false && strpos($value, '/') !== false) {
				$value = urlencode($value);
			}
			//switch public value to binary
			if ($field == "public") {
				if ($value) {
					$value = "1";
				} else {
					$value = "0";
				}
			}
			//concat
			$formatted_values .= "'" . $value . "', ";
		}
		//cleanup
		$formatted_fields = rtrim($formatted_fields, ", "). ")";
		$formatted_values = rtrim($formatted_values, ", "). ")";

		//perform sql
		$sql = $sql_ins . $formatted_fields . $sql_vals . $formatted_values;
		if ($conn->query($sql)) {
			$output["success"] = true;
			$output["message"] = "New photoset created";
		} else {
			$output["success"] = false;
			$output["message"] = "Query failed: " . $sql;
		}
		
	} else {
		
		// missing some required value(s)
		$output["message"] = "Missing: ";
		foreach($validation["missing"] as $missing) {
			$output["message"] .= $missing . ", ";
		}
		$output["message"] = rtrim($output["message"], ", ") . ".";
	}
} else {
	$output["message"] = "No arguments provided.";
}






// output results
$output = json_encode($output);
echo $output;
die();