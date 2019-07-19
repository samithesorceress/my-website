<?php
function ctas($type, $btns) {
	$type = rtrim($type, "s");
	$GLOBALS["htp_root"] = "http://127.0.0.1/sami-the-sorceress/";
	$html = "<div class='ctas'>";
	if (!is_array($btns)) {
		$btns = [$btns];
	}
	foreach($btns as $btn) {
		$btn_html = false;
		$btn_link = "http://127.0.0.1/sami-the-sorceress/";
		$btn_icon = false;
		$btn_text = false;
		switch($btn) {
			case "edit":
			case "delete":
				$btn_link .= $btn . "/" . $type;
				$btn_icon = icon($btn);
				$btn_text = ucSmart($btn);
				break;
			case "update":
				$btn_link .= "edit/" . $type;
				$btn_icon = icon("edit");
				$btn_text = ucSmart($btn);
				break;
			case "view-all":
			case "all":
				$btn_link .= "view-all/" . $type;
				if ($type !== "media") {
					$btn_link .= "s";
				}
				$btn_icon = icon("eye");
				$btn_text = "View All";
				break;
			case "add":
				$btn_link .= "new/" . $type;
				$btn_icon = icon("add");
				$btn_text = "Add New";
				break;
			case "new":
				$btn_link .= "new/" . $type;
				$btn_icon = icon("add");
				$btn_text = "New " . ucSmart($type);
				break;
			case "upload":
				$btn_link .= "new/" . $type;
				$btn_icon = icon("upload");
				$btn_text = "Upload More";
				break;

		}

		if ($btn_link) {
			$btn_html .=  "<a href='" . $btn_link . "'>";
		}
		$btn_html .= "<button class='btn cta sml'>";
		if ($btn_icon) {
			$btn_html .= $btn_icon;
		}
		if ($btn_text) {
			$btn_html .= "<span>" . $btn_text . "</span>";
		}
		$btn_html .= "</button>";
		if ($btn_link) {
			$btn_html .= "</a>";
		}
		$html .= $btn_html;
	}
	$html .= "</div>";
	return $html;
}