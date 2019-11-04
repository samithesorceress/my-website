<?php
function stream($item) {
	$html = "<div class='stream_container'>";
	if ($item) {
		$url = "";
		if (strpos($item, "pornhub")) {
			$url = $item;
		} else {
			$url = "https://www.pornhub.com/embed/" . $item;
		}
		$html .= "<iframe src='" . $url . "' frameborder='0' width='560' height='315' scrolling='no' allowfullscreen></iframe>";
	} else {
		$html .= "<div class='empty_stream_placeholder'></div>";
	}
	$html .= "</div>";
	return $html;
}