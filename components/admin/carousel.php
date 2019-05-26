<?php

function carousel($type) {
	$html = "<div class='carousel'>";
	$edit_link = "http://127.0.0.1/sami-the-sorceress/admin/edit/";
	$api_endpoint = "list/";
	switch($type) {
		case "video":
		case "videos":
			$edit_link .= "video/";
			$api_endpoint .= "videos";
			break;
		case "photoset":
		case "photosets":
			$edit_link .= "photoset/";
			$api_endpoint .= "photosets";
			break;
		case "store":
		case "store-items":
			$edit_link .= "store-item/";
			$api_endpoint .= "store-items";
			break;
		case "media":
		case "media-items":
			$edit_link .= "media/";
			$api_endpoint .= "media";
			break;
		case "slides":
		case "slideshow":
			$edit_link .= "slide/";
			$api_endpoint .= "slides";
	}
	$api_params = [
		"rows" => 3,
		"order_by" => "id",
		"order_dir" => "DESC"
	];
	$api_res = xhrFetch($api_endpoint, $api_params);
	if (valExists("success", $api_res)) {
		$items = $api_res["data"];
		if ($items) {
			if (valExists("id", $items)) { // only one result was returned
				$items = [$items];
			}
			foreach ($items as $item) {
				$html .= "<li><a href='" . $edit_link . $item["id"] . "'>";
					if ($type == "media" || $type == "media-items") {
						if (valExists("cover", $item)) {
							$html .= mediaContainer($item["cover"]);
						} elseif (valExists("src", $item)) {
							$html .= mediaContainer($item);
						}
						
					} else {
						$html .= mediaContainer($item["cover"], "wide", $item["title"]);
					}
					
					$html .= "</a></li>";
			}
		} else {
			$html .= "<p>No Results</p>";
		}
	} else {
		$html .= "<p>No Results</p>";
	}
	$html .="</div>";
	return $html;
}