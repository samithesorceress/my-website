<?php
function thumbnail($type, $item) {
	if (is_string($item)) {
		//handle edge case?
	}
	$url = $GLOBALS["htp_root"] . $type;
	if ($type !== "store") {
		$url .= "s";
	}
	$url .= "/" . $item["url"];
	
	$thumbnail = "<li class='thumbnail card'><div class='thumbnail_content'>";
	switch($type) {
		case "video":
			$timestamp = $item["timestamp"];
			if (strpos($item["timestamp"], ":") === false) {
				$timestamp .= ":00";
			}
			break;
		case "photoset":
			$timestamp = $item["photocount"] . " Photos";
			break;
		default:
			$timestamp = false;
	}
	$thumbnail .= "<figure><a href='" . $url . "'>" . mediaContainer($item["cover"], "hd", false, $timestamp) . "</a>";
		$thumbnail .= "<figcaption><dl>";
		if (valExists("title", $item)) {
			$thumbnail .= "<dt class='thumbnail_title'>" . $item["title"] . "</dt>";
		}
		if (valExists("description", $item)) {
			$thumbnail .= "<dd class='thumbnail_description'>" . substr($item["description"], 0, 56) . "... <a href='" . $url . "'>[read more]</a></dd>";
		}
		$thumbnail .= "</dl></figcaption>";
	$thumbnail .= "</figure><footer class='thumbnail_footer'><ul>";
		$thumbnail .= "<li class='thumbnail_price'><p>";
		if (valExists("price", $item) && $item["price"] !== 0) {
				$thumbnail .= "$" . $item["price"];
		} else {
				$thumbnail .= "FREE";
		}
			$thumbnail .= "</p></li>";
		$buy_link = "#";
		if (valExists("links", $item)) {
			$links = json_decode($item["links"], true);
			if (valExists("0", $links)) {
				$buy_link = urldecode($links["0"]["url"]);
			}
		}
		$thumbnail .= "<li><a href='" . $buy_link . "' target='_blank'><button type='button' class='btn cta sml'><span>";
		if (valExists("price", $item) && $item["price"] !== 0) {
			$thumbnail .= "Buy";
		} else {
			$thumbnail .= "Download";
		}
		$thumbnail .= "</span></button></a></li>";
	$thumbnail .= "</ul></footer>";
	$thumbnail .= "</div></li>";
	return $thumbnail;
}