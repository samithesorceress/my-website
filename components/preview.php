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
		$html .= "<ul>";
		foreach($items as $item) {
			$html .= "<li>";

			$html .= "</li>";
		}
		$html .= "</ul>";
	} else {//single res
		if (is_numeric($item)) {//get img
			$html .= mediaContainer($item, "hd");
		} else {//is a stream link

		}
	}

	$html .= "</div>";
	return $html;
}
?>