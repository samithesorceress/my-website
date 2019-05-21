<?php
$errors = [];

// is attempting save
if (empty($_REQUEST) === false) {
	$data = getValues($_REQUEST);
	
	//check required values
	$required = [
		"cover",
		"preview",
		"title",
		"description",
		"tags",
		"price",
		"publish_date"
	];
	$validation = checkRequired($required, $data);
	if (valExists("success", $validation)) {
		
		//prepare api request
		$api_endpoint = "newVideo";
		$api_params = [
			"cover" => $data["cover"],
			"preview" => $data["preview"],
			"title" => $data["title"],
			"description" => $data["description"],
			"tags" => $data["tags"],
			"price" => $data["price"],
			"publish_date" => $data["publish_date"]
		];

		//check for non-required and add them if they exist
		if (valExists("public", $data)) {
			$api_params["public"] = 1;
		} else {
			$api_params["public"] = 0;
		}
		$video_req = xhrFetch($api_endpoint, $api_params);

		//result
		if (valExists("success", $video_req)) {
			header("Location: " . $admin_root . "view-all/videos");
			die();
		} else {
			$errors[] = "Server error: " . $video_req["message"];
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
		echo newFormField("cover", "Cover", "media_browser", 1);
		echo newFormField("preview", "Preview Video", "media_browser", 1);
	echo "</div>";
	echo "<div class='card'>";
		echo newFormField("title", "Title");
		echo newFormField("description", "Description", "textarea");
		echo newFormField("tags", "Tags", "textarea");
		echo newFormField("price", "Price");
	echo "</div>";
	echo "<div class='card'>";
		echo "<h3>Purchase Links</h3>";
		// todo: infinite link fields
	echo "</div>";
	echo "<div class='card'>";
		echo newFormField("publish_date", "Publish Date", "date");
		echo newFormField("public", "Public", "checkbox");
	echo "</div>";
	echo newFormField("save", "Save", "submit", "Save");
	?>

</form>

<?php
require_once($php_root . "components/admin/footer.php");