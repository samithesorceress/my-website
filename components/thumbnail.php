<?php
function thumbnail($type, $item) {
	$htp_root = "http://127.0.0.1/sami_the_sorceress/";
	if (is_string($item)) {
		//handle edge case?
	}
	$html = "<li class='thumbnail card'><a href='" . $htp_root . $type . "/" . $item["id"] . "'>";
	if (valExists("cover", $item)) {
		$html .= mediaContainer($item["cover"], "hd");
	}
	$html .= "<dl>";
		if (valExists("title", $item)) {
			$html .= "<dt class='thumbnail_title'>" . $item["title"] . "</dt>";
		}
		if (valExists("description", $item)) {
			$html .= "<dd class='thumbnail_description'>" . substr($item["description"], 0, 56) . "...</dd>";
		}
		if (valExists("price", $item)) {
			$html .= "<dd class='thumbnail_price'>$" . $item["price"] . "</dd>";
		}
	$html .= "</dl>";
	$html .= "<footer class='thumbnail_footer'><a href='" . $htp_root . "'><button type='button' class='btn cta sml'><span>Info</span></button></a><a href='" . $htp_root . "'><button type='button' class='btn cta sml'><span>Buy</span></button></a></footer>";
	$html .= "</a></li>";
	return $html;
}