<?php
require_once $php_root . "components/thumbnail.php";
function thumbnailGroup($type, $limit = false) {
	$html = "<ul class='thumbnail_group'>";
	$api_endpoint  = "list/";
	$api_params = [
		"rows" => 1,
		"order_by" => "id",
		"order_dir" => "DESC"
	];
	$type = rtrim($type, "s");
	switch($type) {
		case "media":
		case "store":
			$api_endpoint .= $type;
			break;
		default:
			$api_endpoint .= $type . "s";
			break;
	}
	if ($limit) {
		$api_params["rows"] = $limit;
	}
	$api_res = xhrFetch($api_endpoint, $api_params);
	if (valExists("success", $api_res)) {
		$thumbnails = $api_res["data"];
		foreach($thumbnails as $thumbnail) {
			$html .= thumbnail($type, $thumbnail);
		}
	}
	for ($i = 0; $i < 3; $i += 1) {
		$html .= "<li class='hidden_flex_item'></li>";
	}
	$html .= "</ul>";
	return $html;
}