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
	$thumbnail .= "<a href='" . $url . "'>";
		$thumbnail .= mediaContainer($item["cover"], "hd");
		switch($type) {
			case "video":
				$thumbnail .= "<span class='timestamp'>";
					$thumbnail .= $item["timestamp"];
					if (strpos($item["timestamp"], ":") === false) {
						$thumbnail .= ":00";
					}
				$thumbnail .= "</span>";
				break;
			case "photoset":
				$thumbnail .= "<span class='timestamp'>" . $item["photocount"] . " Photos</span>";
				break;
		}
	$thumbnail .= "</a>";
	$info = "<dl>";
		if (valExists("title", $item)) {
			$info .= "<dt class='thumbnail_title'>" . $item["title"] . "</dt>";
		}
		if (valExists("description", $item)) {
			$info .= "<dd class='thumbnail_description'>" . substr($item["description"], 0, 56) . "... <a href='" . $url . "'>[read more]</a></dd>";
		}
	$info .= "</dl>";
	$footer = "<footer class='thumbnail_footer'><ul>";
		$footer .= "<li class='thumbnail_price'><p>";
		if (valExists("price", $item) && $item["price"] !== 0) {
			$footer .= "$" . $item["price"];
		} else {
			$footer .= "FREE";
		}
		$footer .= "</p></li>";
		$buy_link = "#";
		if (valExists("links", $item)) {
			$links = json_decode($item["links"], true);
			if (valExists("0", $links)) {
				$buy_link = urldecode($links["0"]["url"]);
			}
		}
		$footer .= "<li><a href='" . $buy_link . "' target='_blank'><button type='button' class='btn cta sml'><span>";
		if (valExists("price", $item) && $item["price"] !== 0) {
			$footer .= "Buy";
		} else {
			$footer .= "Download";
		}
		$footer .= "</span></button></a></li>";
	$footer .= "</ul></footer>";
	$thumbnail .= $info . $footer . "</div></li>";
	return $thumbnail;
}