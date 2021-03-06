<?php
require_once($GLOBALS["php_root"] . "components/admin/header.php");
require_once($GLOBALS["php_root"] . "components/card.php");
$ids = urldecode($subdirectories[3]);
if (strpos($ids, ",") !== false) {
	$ids = explode(",", $ids);
} else {
	$ids = [$ids];
}
foreach($ids as $id) {
	echo "<h2>Photoset ID: " . $id . "</h2>";
	$api_endpoint = "list/photosets";
	$api_params = [
		"id" => $id
	];
	$photoset_res = xhrFetch($api_endpoint, $api_params);
	if (valExists("success", $photoset_res)) {
		$photoset = $photoset_res["data"];
		$photoset_previews = json_decode($photoset["previews"], true);
		echo card(
			"Files",
			false,
			newFormField("photoset_cover_" . $id, "Cover", "media_browser", $photoset["cover"]) . "<div class='fields three_col'>" . 
			newFormField("photoset_preview_1_" . $id, "Preview Img 1", "media_browser", $photoset_previews[0]) . newFormField("photoset_preview_2_" . $id, "Preview Img 2", "media_browser", $photoset_previews[1]) . newFormField("photoset_preview_3_" . $id, "Preview Img 3", "media_browser", $photoset_previews[2]) . "</div>"
		);

		echo card (
			"Info",
			false,
			newFormField("photoset_title_" . $id, "Title", "text", $photoset["title"]) . 
			newFormField("photoset_description_" . $id, "Description", "textarea", $photoset["description"]) . 
			newFormField("photoset_tags_" . $id, "Tags", "textarea", $photoset["tags"]) . 
			newFormField("photoset_price_" . $id, "Price", "text", $photoset["price"])
		);

		echo card(
			"Purchase Links",
			false,
			newFormField("photoset_links_" . $id, "Links", "links", $photoset["links"])
		);

		echo card(
			"Metadata",
			false,
			newFormField("photoset_publish_date_" . $id, "Publish Date", "date", $photoset["publish_date"]) . newFormField("photoset_photocount_" . $id, "# of Photos", "text", $photoset["photocount"]) . newFormField("photoset_url_" . $id, "Url", "text", $photoset["url"]) . 
			newFormField("photoset_public_" . $id, "Public", "checkbox", $photoset["public"])
		);

	}
}
echo newFormField("save", "Save", "submit", "Save", "photosetManager.saveChanges");
echo "<script src='" . $GLOBALS["htp_root"] . "functions/photosetManager.js'></script>";
echo "<script src='" . $GLOBALS["htp_root"] . "functions/infiniteLinks.js'></script>";
require_once($GLOBALS["php_root"] . "components/admin/footer.php");