<?php
function viewAll($type) {
	$htp_root = "http://127.0.0.1/sami-the-sorceress/";
	$html = "<section id='view_all' class='card'><div class='card_contents'>";
	$api_endpoint  = "list/";
	$api_params = [
		"rows" => 5,
		"order_by" => "id",
		"order_dir" => "DESC"
	];
	switch($type) {
		case "media":
		case "store-items":
		case "videos":
		case "photosets":
			$api_endpoint .= $type;
			break;
		default:
			$api_endpoint .= $type . "s";
			break;
	}
	$api_res = xhrFetch($api_endpoint, $api_params);
	if (valExists("success", $api_res)) {
		$list_items = $api_res["data"];
		if ($list_items) {
			$html .= "<ul id='view_all_list' data-type='" . $type . "'>";
			if (valExists("id", $list_items)) {
				$list_items = [$list_items];
			}
			foreach ($list_items as $list_item) {
				$shape = false;
				$src = $list_item;
				$title = false;
				if ($type !== "media") {
					$shape = "wide";
				}
				if (valExists("cover", $src)) {
					$src = $src["cover"];
				}
				if (valExists("title", $list_item)) {
					$title = $list_item["title"];
				}

				$html .= "<li id='list_item_" . $list_item["id"] . "' class='view_all_list_item " . $shape . "' data-key='" . $list_item["id"] . "'><button type='button' class='btn cta fab sml list_item_fab_btn'>" . file_get_contents($htp_root . "src/icons/checkbox_checked.svg") . file_get_contents($htp_root . "src/icons/checkbox_unchecked.svg") . "</button><a href='" . $htp_root . "admin/edit/" . $type . "/" . $list_item["id"] . "'>" . mediaContainer($src, $shape, $title) . "</a></li>";
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