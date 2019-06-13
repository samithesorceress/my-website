<?php
function thumbnail($type, $item) {
	$htp_root = "http://127.0.0.1/sami_the_sorceress/";
	if (is_string($item)) {
		//handle edge case?
	}
	$url = $htp_root . $type . "/" . $item["id"];
	$thumbnail = "<li class='thumbnail card'><div class='thumbnail_content'>";
	if (valExists("cover", $item)) {
		$thumbnail .= "<a href='" . $url . "'>" . mediaContainer($item["cover"], "hd") . "</a>";
	}
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
		$footer .= "<li><a href='" . $htp_root . "'><button type='button' class='btn cta sml'><span>Buy</span></button></a></li>";
	$footer .= "</ul></footer>";
	$thumbnail .= $info . $footer . "</div></li>";
	return $thumbnail;
}