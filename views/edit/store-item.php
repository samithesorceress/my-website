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
	$store_api = "listStoreItems?id=" . $id;
	$store_res = xhrFetch($store_api);
	if (valExists("success", $store_res)) {
		$store_item = $store_res["data"];
		echo "<div class='card'>";
			echo newFormField("store_item_cover_" . $id, "Cover", "media_browser", 1, $store_item["cover"]);
			echo newFormField("store_item_previews_" . $id, "Preview(s)", "media_browser", 1, $store_item["previews"]);

			echo newFormField("store_item_title_" . $id, "Title", "text", $store_item["title"]);
			echo newFormField("store_item_description_" . $id, "Description", "textarea", $store_item["description"]);
			echo newFormField("store_item_tags_" . $id, "Tags", "textarea", $store_item["tags"]);
			echo newFormField("store_item_price_" . $id, "Price", "text", $store_item["price"]);
			echo newFormField("store_item_publish_date_" . $id, "Publish Date", "date", $store_item["publish_date"]);
			echo newFormField("store_item_public_" . $id, "Public", "checkbox", $store_item["public"]);
		echo "</div>";
	}

}
echo newFormField("save", "Save", "submit", "Save", "storeManager.saveChanges");
echo "<script src='" . $htp_root . "functions/storeManager.js'></script>";
require_once($php_root . "components/admin/footer.php");