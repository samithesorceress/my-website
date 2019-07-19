<?php
$errors = [];
require_once($GLOBALS["php_root"] . "components/admin/header.php");

	echo "<div class='card'>";
		echo newFormField("src", "Image/Video", "file");
	echo "</div>";
	echo "<div class='card'>";
		echo newFormField("title", "Title");
		echo newFormField("alt", "Alt", "textarea");
		echo newFormField("public", "Public", "checkbox");
	echo "</div>";
	echo newFormField("save", "Save", "submit", "Save", "mediaManager.saveNew");

echo "<script src='" . $GLOBALS["htp_root"] . "functions/mediaManager.js'></script>";
require_once($GLOBALS["php_root"] . "components/admin/footer.php");