<?php
function thumbnail($type, $item) {
	$htp_root = "http://127.0.0.1/sami_the_sorceress/";
	if (is_string($item)) {
		//handle edge case?
	}
	$url = $htp_root . $type;
	if ($type !== "store") {
		$url .= "s";
	}
	$url .= "/" . $item["url"];
	
	$thumbnail = "<li class='thumbnail card'><div class='thumbnail_content'>";
	$thumbnail .= "<a href='" . $url . "'>";
		switch($type) {
			case "video":
				$thumbnail .= "<span class='timestamp'>" . $item["timestamp"] . "</span>";
				break;
			case "photoset":
				$thumbnail .= "<span class='timestamp'>" . $item["photocount"] . " Photos</span>";
				break;
		}
		$thumbnail .= mediaContainer($item["cover"], "hd");
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
		if (valExists("price", $item)) {
			$footer .= "<li class='thumbnail_price'><p>$" . $item["price"] . "</p></li>";
		}
		$buy_link = "#";
		if (valExists("links", $item)) {
			$links = json_decode($item["links"], true);
			if (valExists("0", $links)) {
				$buy_link = urldecode($links["0"]["url"]);
			}
		}
		$footer .= "<li><a href='" . $buy_link . "' target='_blank'><button type='button' class='btn cta sml'><span>Buy</span></button></a></li>";
	$footer .= "</ul></footer>";
	$thumbnail .= $info . $footer . "</div></li>";
	return $thumbnail;
}