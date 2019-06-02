<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/card.php");
$ids = urldecode($subdirectories[3]);
if (strpos($ids, ",") !== false) {
	$ids = explode(",", $ids);
} else {
	$ids = [$ids];
}
foreach($ids as $id) {
	echo "<h2>Slide ID: " . $id . "</h2>";
	$api_endpoint = "list/slides";
	$api_params = [
		"id" => $id
	];
	$slide_res = xhrFetch($api_endpoint, $api_params);
	if (valExists("success", $slide_res)) {
		$slide = $slide_res["data"];

		echo card(
			"Files",
			false,
			newFormField("slide_cover_" . $id, "Slide Image", "media_browser", 1, $slide["cover"])
		);

		echo card (
			"Info",
			false,
			newFormField("slide_title_" . $id, "Title", "text", $slide["title"]) . 
			newFormField("slide_url_" . $id, "Url", "text", $slide["url"]) . 
			newFormField("slide_public_" . $id, "Public", "checkbox", $slide["public"])
		);
	}
}
echo newFormField("save", "Save", "submit", "Save", "slideshowManager.saveChanges");
echo "<script src='" . $htp_root . "functions/slideshowManager.js'></script>";
require_once($php_root . "components/admin/footer.php");