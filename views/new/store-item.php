<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/card.php");

	echo card(
		"Files",
		false,
		newFormField("store_item_cover", "Cover", "media_browser", 1) . 
		newFormField("store_item_previews", "Preview(s)", "media_browser")
	);

	echo card(
		"Info",
		false,
		newFormField("store_item_title", "Title") . 
		newFormField("store_item_description", "Description", "textarea") . 
		newFormField("store_item_tags", "Tags", "text") . 
		newFormField("store_item_price", "Price")
	);
	
	echo card(
		"Purchase Links",
		false,
		newFormField("store_item_links", "Links", "links")
	);
	
	echo card(
		"Metadata",
		false,
		newFormField("store_item_publish_date", "Publish Date", "date") . 
		newFormField("store_item_public", "Public", "checkbox")
	);

	echo newFormField("save", "Save", "submit", "Save", "storeManager.saveNew");
echo "<script src='" . $htp_root . "functions/storeManager.js'></script>";
echo "<script src='" . $htp_root . "functions/infiniteLinks.js'></script>";
require_once($php_root . "components/admin/footer.php");