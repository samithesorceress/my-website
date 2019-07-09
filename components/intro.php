<?php
function intro($title = false, $description = false) {
	$html = "<article><header class='intro card'>";
	if ($title) {
		$html .= "<h1 class='title'>" . $title . "</h1>";
	}
	if ($description) {
		$html .= "<p>" . $description . "</p>";
	}
	$html .= "</header>";
	return $html;
}