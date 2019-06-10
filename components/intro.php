<?php
function intro($title = false, $description = false) {
	$html = "<main id='main'><div id='spinner_0' class='spinner visible'><svg viewBox='0 0 50 50'><circle class='progress' cx='25' cy='25' r='20'/></svg></div>";
	if ($title) {
		$html .= "<h1 class='title'>" . $title . "</h1>";
	}
	if ($description) {
		$html .= "<p>" . $description . "</p>";
	}
	$html .= "<article>";
	return $html;
}