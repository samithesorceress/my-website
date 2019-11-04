<?php
require_once($GLOBALS["php_root"] . "components/admin/header.php");
require_once($GLOBALS["php_root"] . "components/card.php");

	echo card(
		"Files",
		false,
		newFormField("photoset_cover", "Cover", "media_browser") . 
		newFormField("photoset_previews", "Preview(s)", "media_browser")
	);

	echo card(
		"Info",
		false,
		newFormField("photoset_title", "Title") . 
		newFormField("photoset_description", "Description", "textarea") . 
		newFormField("photoset_tags", "Tags", "text") . 
		newFormField("photoset_price", "Price")
	);

	echo card(
		"Purchase Links",
		false,
		newFormField("photoset_links", "Links", "links")
	);

	echo card(
		"Metadata",
		false,
		newFormField("photoset_publish_date", "Publish Date", "date") . newFormField("photoset_photocount", "# of Photos", "text") . newFormField("photoset_url", "Url", "text") . 
		newFormField("photoset_public", "Public", "checkbox", 1)
	);

	echo newFormField("save", "Save", "submit", "Save", "photosetManager.saveNew");
echo "<script src='" . $GLOBALS["htp_root"] . "functions/photosetManager.js'></script>";
echo "<script src='" . $GLOBALS["htp_root"] . "functions/infiniteLinks.js'></script>";
require_once($GLOBALS["php_root"] . "components/admin/footer.php");