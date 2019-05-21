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
	$media_api = "listMedia?id=" . $id;
	$media_res = xhrFetch($media_api);
	if (valExists("success", $media_res)) {
		$media_item = $media_res["data"];
		echo "<div class='card'>";
			echo newFormField("media_file_" . $id, "File", "media_browser", 1, $id);
			echo newFormField("media_title_" . $id, "Title", "text", $media_item["title"]);
			echo newFormField("media_alt_" . $id, "Alt", "textarea", $media_item["alt"]);
			echo newFormField("media_public_" . $id, "Public", "checkbox");
		echo "</div>";
	}

}
echo newFormField("save", "Save", "submit", "Save", "mediaManager.saveEdits");
echo "<script src='" . $htp_root . "functions/mediaManager.js'></script>";
require_once($php_root . "components/admin/footer.php");