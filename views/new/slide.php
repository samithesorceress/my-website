<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/card.php");

	echo card(
		"File",
		false,
		newFormField("slide_cover", "Slide Image", "media_browser", 1)
	);
	
	echo card(
		"Info",
		false,
		newFormField("slide_title", "Text") . 
		newFormField("slide_url", "Url") . 
		newFormField("slide_public", "Public", "checkbox")
	);
	
	echo newFormField("save", "Save", "submit", "Save", "slideshowManager.saveNew");
	echo "<script src='" . $htp_root . "functions/slideshowManager.js'></script>";
require_once($php_root . "components/admin/footer.php");