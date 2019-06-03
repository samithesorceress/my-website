<?php
if ($subdirectories[3]) {
	require_once($php_root . "components/admin/header.php");
	require_once($php_root . "components/card.php");

	$ids = urldecode($subdirectories[3]);
	if (strpos($ids, ",") !== false) {
		$ids = explode(",", $ids);
	} else {
		$ids = [$ids];
	}
	foreach($ids as $id) {
		$api_endpoint = "list/media";
		$api_params = [
			"id" => $id
		];
		$media_res = xhrFetch($api_endpoint, $api_params);
		if (valExists("success", $media_res)) {
			$media_item = $media_res["data"];
			echo card(
				"File",
				false,
				newFormField("media_file_" . $id, "File", "media_browser", 1, $id, true)
			);

			echo card(
				"Metadata",
				false,
				newFormField("media_title_" . $id, "Title", "text", $media_item["title"]) . 
				newFormField("media_alt_" . $id, "Alt", "textarea", $media_item["alt"]) . 
				newFormField("media_public_" . $id, "Public", "checkbox", $media_item["public"])
			);
		}

	}
	echo newFormField("save", "Save", "submit", "Save", "mediaManager.saveChanges");
	echo "<script src='" . $htp_root . "functions/mediaManager.js'></script>";
	require_once($php_root . "components/admin/footer.php");
} else {
	header("Location: " . $htp_root . "admin/view-all/media");
}