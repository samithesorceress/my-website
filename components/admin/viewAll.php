<?php
function viewAll($type) {
	$htp_root = "http://127.0.0.1/sami-the-sorceress/";
	$html = "<section id='view_all' class='card'><div class='card_contents'>";
	$api_endpoint  = "list";
	$api_params = [
		"rows" => 5,
		"order_by" => "id",
		"order_dir" => "DESC"
	];
	switch($type) {
		case "media":
			$api_endpoint .= "Media";
			break;
		case "store-item":
			$api_endpoint .= "StoreItem";
			break;
		default:
			$api_endpoint .= ucWords($type) . "s";
			break;
	}
	$api_res = xhrFetch($api_endpoint, $api_params);
	if (valExists("success", $api_res)) {
		$list_items = $api_res["data"];
		if ($list_items) {
			$html .= "<ul id='view_all_list' data-type='" . $type . "'>";
			foreach ($list_items as $list_item) {
				$html .= "<li id='list_item_" . $list_item["id"] . "' class='view_all_list_item' data-key='" . $list_item["id"] . "'><button id='list_item_fab_btn' type='button' class='btn cta fab sml'>" . file_get_contents($htp_root . "src/icons/checkbox_checked.svg") . file_get_contents($htp_root . "src/icons/checkbox_unchecked.svg") . "</button><a href='" . $htp_root . "admin/edit/" . $type . "/" . $list_item["id"] . "'>" . mediaContainer($list_item) . "</a></li>";
			}
			for ($i = 0; $i < 12; $i += 1) {
				$html .= "<li class='hidden-flex-item'></li>";
			}
			$html .= "</ul>";
		}
	} else {
		$html .= "<p>No Results</p>";
	}
	$html .= "</div></section>";
	return $html;
}