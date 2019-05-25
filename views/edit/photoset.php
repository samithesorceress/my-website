<?php
require_once($php_root . "components/admin/header.php");

$ids = urldecode($subdirectories[3]);
if (strpos($ids, ",") !== false) {
	$ids = explode(",", $ids);
} else {
	$ids = [$ids];
}
//echo "<pre>";
//print_r($ids);
//echo "</pre>";
foreach($ids as $id) {
	$photoset_api = "listPhotosets?id=" . $id;
	$photoset_res = xhrFetch($photoset_api);
	if (valExists("success", $photoset_res)) {
		$photoset = $photoset_res["data"];
		echo "<div class='card'>";
			echo newFormField("photoset_cover_" . $id, "Cover", "media_browser", 1, $photoset["cover"]);
			echo newFormField("photoset_previews_" . $id, "Preview(s)", "media_browser", 1, $photoset["previews"]);

			echo newFormField("photoset_title_" . $id, "Title", "text", $photoset["title"]);
			echo newFormField("photoset_description_" . $id, "Description", "textarea", $photoset["description"]);
			echo newFormField("photoset_tags_" . $id, "Tags", "textarea", $photoset["tags"]);
			echo newFormField("photoset_price_" . $id, "Price", "text", $photoset["price"]);
			echo newFormField("photoset_publish_date_" . $id, "Publish Date", "date", $photoset["publish_date"]);
			echo newFormField("photoset_public_" . $id, "Public", "checkbox", $photoset["public"]);
		echo "</div>";
	}

}
echo newFormField("save", "Save", "submit", "Save", "photosetManager.saveChanges");
echo "<script src='" . $htp_root . "functions/photosetManager.js'></script>";
require_once($php_root . "components/admin/footer.php");