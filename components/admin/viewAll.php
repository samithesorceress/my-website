<?php
function viewAll($type) {
	$GLOBALS["htp_root"] = "http://127.0.0.1/sami-the-sorceress/";
	$html = "<section id='view_all' class='grid card'><div class='card_contents'>";
	$api_endpoint  = "list/";
	$api_params = [
		"rows" => 5,
		"order_by" => "id",
		"order_dir" => "DESC"
	];
	$type = rtrim($type, "s");
	switch($type) {
		case "media":
		case "media_item":
		case "media-item":
			$api_endpoint .= "media";
			break;
		case "store":
		case "store_item":
		case "store-item":
			$api_endpoint .= "store-items";
			break;
		default:
			$api_endpoint .= $type . "s";
			break;
	}
	
	$api_res = xhrFetch($api_endpoint, $api_params);
	$pagination = false;
	if (valExists("success", $api_res)) {
		$list_items = $api_res["data"];
		$pagination = $api_res["pagination"];
		if ($list_items) {
			$html .= "<ul id='view_all_list' class='grid_contents' data-type='" . $type . "'>";
			if (valExists("id", $list_items)) {
				$list_items = [$list_items];
			}
			foreach ($list_items as $list_item) {
				$shape = false;
				$src = $list_item;
				$title = false;
				if ($type == "media" || $type == "media-items") {
					$shape = "square";
				} else {
					$shape = "wide";
					if (valExists("cover", $src)) {
						$src = $src["cover"];
					}
					if (valExists("title", $list_item)) {
						$title = $list_item["title"];
					}	
				}

				$html .= "<li id='list_item_" . $list_item["id"] . "' class='view_all_list_item grid_item " . $shape . "_grid_item' data-key='" . $list_item["id"] . "'><button type='button' class='btn cta fab sml list_item_fab_btn'>" . file_get_contents($GLOBALS["htp_root"] . "src/icons/checkbox_checked.svg") . file_get_contents($GLOBALS["htp_root"] . "src/icons/checkbox_unchecked.svg") . "</button><a href='" . $GLOBALS["htp_root"] . "admin/edit/" . $type . "/" . $list_item["id"] . "'>" . mediaContainer($src, $shape, $title) . "</a></li>";
			}
			for ($i = 0; $i < 12; $i += 1) {
				$html .= "<li class='hidden_flex_item grid_item " . $shape . "_grid_item'></li>";
			}
			$html .= "</ul>";
		}
	} else {
		$html .= "<p>No Results</p>";
	}
	$html .= "</div><footer><div class='ctas'>";
		$html .= "<button id='prev_page_btn' class='cta btn sml";
		if ($pagination) {
			if ($pagination["prev"] === false) {
				$html .= " disabled";
			}
		$html .= "' data-offset='" . $pagination["prev"];
		} else {
			$html .= " disabled";
		}
		$html .= "'>" . icon("arrow_left") . "<span>Prev Page</span></button><button id='next_page_btn' class='cta btn sml";
		if ($pagination) {
			if ($pagination["next"] === false) {
				$html .= " disabled";
			}
		$html .= "' data-offset='" . $pagination["next"];
		} else {
			$html .= " disabled";
		}
		$html .= "'><span>Next Page</span>" . icon("arrow_right") . "</button>";
	$html .= "</div></footer></section>";
	return $html;
}