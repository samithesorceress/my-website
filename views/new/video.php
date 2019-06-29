<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/card.php");

	echo card (
		"Files",
		false,
		newFormField("video_cover", "Cover", "media_browser", 1) .
		newFormField("video_preview", "Preview Video", "media_browser", 1)
	);
	
	echo card(
		"Info",
		false,
		newFormField("video_title", "Title") . 
		newFormField("video_description", "Description", "textarea") . 
		newFormField("video_tags", "Tags", "textarea") . 
		newFormField("video_price", "Price")
	);

	echo card(
		"Purchase Links",
		false,
		newFormField("video_links", "Links", "links")
	);

	echo card(
		"Metadata",
		false,
		newFormField("video_publish_date", "Publish Date", "date") . 
		newFormField("video_timestamp", "Length", "text") . newFormField("video_url", "Url", "text") . 
		newFormField("video_public", "Public", "checkbox")
	);

	echo newFormField("save", "Save", "submit", "Save", "videoManager.saveNew");
echo "<script src='" . $htp_root . "functions/videoManager.js'></script>";
echo "<script src='" . $htp_root . "functions/infiniteLinks.js'></script>";
require_once($php_root . "components/admin/footer.php");