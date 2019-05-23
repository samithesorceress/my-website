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
	$video_api = "listVideos?id=" . $id;
	$video_res = xhrFetch($video_api);
	if (valExists("success", $video_res)) {
		$video_item = $video_res["data"];
		echo "<div class='card'>";
			echo newFormField("video_cover_" . $id, "Cover", "media_browser", 1, $video_item["cover"]);
			echo newFormField("video_preview_" . $id, "Preview", "media_browser", 1, $video_item["preview"]);

			echo newFormField("video_title_" . $id, "Title", "text", $video_item["title"]);
			echo newFormField("video_description_" . $id, "Description", "textarea", $video_item["description"]);
			echo newFormField("video_tags_" . $id, "Tags", "textarea", $video_item["tags"]);
			echo newFormField("video_price_" . $id, "Price", "text", $video_item["price"]);
			echo newFormField("video_publish_date_" . $id, "Publish Date", "date", $video_item["publish_date"]);
			echo newFormField("video_public_" . $id, "Public", "checkbox");
		echo "</div>";
	}

}
echo newFormField("save", "Save", "submit", "Save", "videoManager.saveChanges");
echo "<script src='" . $htp_root . "functions/videoManager.js'></script>";
require_once($php_root . "components/admin/footer.php");