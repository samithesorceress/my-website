<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/card.php");
$ids = urldecode($subdirectories[3]);
if (strpos($ids, ",") !== false) {
	$ids = explode(",", $ids);
} else {
	$ids = [$ids];
}
foreach($ids as $id) {
	echo "<h2>Store Item ID: " . $id . "</h2>";
	$api_endpoint = "list/store-items";
	$api_params = [
		"id" => $id
	];
	$store_res = xhrFetch($api_endpoint, $api_params);
	if (valExists("success", $store_res)) {
		$store_item = $store_res["data"];

		echo card(
			"Files",
			false,
			newFormField("store_item_cover_" . $id, "Cover", "media_browser", 1, $store_item["cover"]) . newFormField("store_item_previews_" . $id, "Preview(s)", "media_browser", 1, $store_item["previews"])
		);

		echo card(
			"Info",
			false,
			newFormField("store_item_title_" . $id, "Title", "text", $store_item["title"]) . 
			newFormField("store_item_description_" . $id, "Description", "textarea", $store_item["description"]) . 
			newFormField("store_item_tags_" . $id, "Tags", "textarea", $store_item["tags"]) . 
			newFormField("store_item_price_" . $id, "Price", "text", $store_item["price"])
		);

		echo card(
			"Purchase Links",
			false,
			newFormField("store_item_links_" . $id, "Links", "links", $store_item["links"])
		);

		echo card(
			"Metadata",
			false,
			newFormField("store_item_publish_date_" . $id, "Publish Date", "date", $store_item["publish_date"]) . 
			newFormField("store_item_public_" . $id, "Public", "checkbox", $store_item["public"])
		);
	}

}
echo newFormField("save", "Save", "submit", "Save", "storeManager.saveChanges");
echo "<script src='" . $htp_root . "functions/storeManager.js'></script>";
echo "<script src='" . $htp_root . "functions/infiniteLinks.js'></script>";
require_once($php_root . "components/admin/footer.php");