<?php
$errors = [];

// is attempting save
if (empty($_REQUEST) === false) {
	$data = getValues($_REQUEST);

	//check required values
	$required = [
		"img",
		"text",
		"url"
	];
	$validation = checkRequired($required, $data);
	if (valExists("success", $validation)) {

		//prepare api req
		$api_endpoint = "newSlide";
		$api_params = [
			"img" => $data["img"],
			"text" => $data["text"],
			"url" => $data["url"]
		];

		//check for non-required and add them if they exist
		if (valExists("public", $data)) {
			$api_params["public"] = 1;
		} else {
			$api_params["public"] = 0;
		}

		//make request
		$slide_req = xhrFetch($api_endpoint, $api_params);
		
		// result
		if (valExists("success", $slide_req)) {
			header("Location: " . $admin_root . "view-all/slides");
		} else {
			$errors[] = "Server error.. " . $slide_req["message"];
		}
	} else {

		//missing some values..
		foreach($validation["missing"] as $missing) {
			$errors[] = "Missing: " . $missing;
		}
	}
}





//BEGIN OUTPUT

require_once($php_root . "components/admin/header.php");

if (count($errors) > 0) {
	echo "<div class='errors'>";
	foreach($errors as $error) {
		echo "<p>" . $error . "</p>";
	}
	echo "</div>";
}
?>

<form enctype="multipart/form-data" action="<?php echo $htp_root . $current_path; ?>" method="POST">

	<?php
	echo "<div class='card'>";
		echo newFormField("img", "Image", "media_browser", 1);
	echo "</div>";
	echo "<div class='card'>";
		echo newFormField("text", "Text");
		echo newFormField("url", "Url");
		echo newFormField("public", "Public", "checkbox");
	echo "</div>";
	echo newFormField("save", "Save", "submit", "Save");
	?>

</form>

<?php
require_once($php_root . "components/admin/footer.php");