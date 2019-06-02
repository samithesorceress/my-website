<?php
function card($title = false, $description = false, $contents = false, $footer = false) {
	$html = "<section class='card'>";
	if ($title) {
		$html .= "<h2 class='title'>";
		$icon = "http://127.0.0.1/sami-the-sorceress/src/icons/";
		switch($title) {
			case "About":
				$icon .= "text";
				break;
			case "Videos":
				$icon .= "movie";
				break;
			case "Photosets":
				$icon .= "photo";
				break;
			case "Store":
				$icon .= "cart";
				break;
			case "Slideshow":
				$icon .= "photos";
				break;
			case "Media Manager":
				$icon .= "swarm";
				break;
			default:
				$icon = false;
		}
		if ($icon) {
			$icon .= ".svg";
			$icon = file_get_contents($icon);
			if ($icon) {
				$html .= $icon;
			}
		}
		$html .= "<span>" . $title . "</span></h2>";
	}
	if ($description) {
		$html .= "<p>" . $description . "</p>";
	}
	if ($contents) {
		$html .= $contents;
	}
	if ($footer) {
		$html .= $footer;
	}
	$html .= "</section>";
	return $html;
}