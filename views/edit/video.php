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
	echo "<h2>Video ID: " . $id . "</h2>";
	$video_api = "list/videos?id=" . $id;
	$video_res = xhrFetch($video_api);
	if (valExists("success", $video_res)) {
		$video_item = $video_res["data"];

		echo card(
			"Files", 
			false, "<div class='fields'>" . 
			newFormField("video_cover_" . $id, "Cover", "media_browser", $video_item["cover"]) . 
			newFormField("video_stream_" . $id, "Pornhub Preview", "stream", $video_item["stream"]) . 
			"</div>"
		);

		echo card(
			"Info", 
			false, 
			newFormField("video_title_" . $id, "Title", "text", $video_item["title"]) . 
			newFormField("video_description_" . $id, "Description", "textarea", $video_item["description"]) . 
			newFormField("video_tags_" . $id, "Tags", "textarea", $video_item["tags"]) . 
			newFormField("video_price_" . $id, "Price", "text", $video_item["price"])
		);

		echo card(
			"Purchase Links", 
			false, 
			newFormField("video_links_" . $id, "Links", "links", $video_item["links"])
		);

		echo card(
			"Metadata", 
			false, 
			newFormField("video_publish_date_" . $id, "Publish Date", "date", $video_item["publish_date"]) . newFormField("video_timestamp_" . $id, "Length", "text", $video_item["timestamp"]) . newFormField("video_url_" . $id, "Url", "text", $video_item["url"]) . 
			newFormField("video_public_" . $id, "Public", "checkbox", $video_item["public"])
		);
	}
}
echo newFormField("save", "Save", "submit", "Save", "videoManager.saveChanges");
echo "<script src='" . $GLOBALS["htp_root"] . "functions/videoManager.js'></script>";
echo "<script src='" . $GLOBALS["htp_root"] . "functions/infiniteLinks.js'></script>";
require_once($GLOBALS["php_root"] . "components/admin/footer.php");