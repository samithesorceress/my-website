<?php
require_once($php_root . "components/admin/header.php");
echo "<form enctype='multipart/form-data' action='" . $htp_root . $current_path . "' method='POST'>";
	echo "<div class='card'>";
		echo newFormField("cover", "Cover", "media_browser", 1);
		echo newFormField("previews", "Preview(s)", "media_browser");
	echo "</div>";
	echo "<div class='card'>";
		echo newFormField("title", "Title");
		echo newFormField("description", "Description", "textarea");
		echo newFormField("tags", "Tags", "text");
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
	echo newFormField("save", "Save", "submit", "Save", "storeManager.saveNew");
echo "</form>";
echo "<script src='" . $htp_root . "functions/storeManager.js'></script>";
require_once($php_root . "components/admin/footer.php");