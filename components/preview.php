<?php
function preview($item) {
	$html = "<div class='product_preview_container'>";
	$first_char = substr($item, 0, 1);
	$items = false;
	if ($first_char == "{" || $first_char == "[") {
		$items = json_decode($item, true);
	}
	if (is_array($item)) {
		$items = $item;
	}

	if ($items) {//carousel
		$item = false;
		$html .= "<div class='carousel_container'><ul>";
		foreach($items as $item) {
			$html .= "<li>";

			$html .= "</li>";
		}
		$html .= "</ul></div>";
	} else {//single res
		if (is_numeric($item)) {//get img
			$html .= "<div class='image_container'>" .  mediaContainer($item, "hd") . "</div>";
		} else {//is a stream link
			$html .= "<div class='stream_container'><iframe src='https://www.pornhub.com/embed/" . $item . "' frameborder='0' width='560' height='315' scrolling='no' allowfullscreen></iframe></div>";
		}
	}

	$html .= "</div>";
	return $html;
}
?>