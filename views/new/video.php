<?php
require_once($php_root . "components/admin/header.php");

	echo "<div class='card'>";
		echo newFormField("video_cover", "Cover", "media_browser", 1);
		echo newFormField("video_preview", "Preview Video", "media_browser", 1);
	echo "</div>";
	echo "<div class='card'>";
		echo newFormField("video_title", "Title");
		echo newFormField("video_description", "Description", "textarea");
		echo newFormField("video_tags", "Tags", "textarea");
		echo newFormField("video_price", "Price");
	echo "</div>";
	echo "<div class='card'>";
		echo "<h3>Purchase Links</h3>";
		// todo: infinite link fields
	echo "</div>";
	echo "<div class='card'>";
		echo newFormField("video_publish_date", "Publish Date", "date");
		echo newFormField("video_public", "Public", "checkbox");
	echo "</div>";
	echo newFormField("save", "Save", "submit", "Save", "videoManager.saveNew");
echo "<script src='" . $htp_root . "functions/videoManager.js'></script>";
require_once($php_root . "components/admin/footer.php");