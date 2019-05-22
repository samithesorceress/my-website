<?php

function mediaContainer($obj, $shape, $title) {
	$html = false;

	if ($obj) {
		// init wrapper
		$html = "div class='media_container";
		if ($shape) {
			switch($shape) {
				case "wide":
					$html .= " wide_contianer";
					break;
				case "tall";
					$html .= " tall_contianer";
					break;
			}
		}
		$html .= "'>";
			// slides/videos title
			if ($title) {
				$html .= "<p class='title'>" . $title . "</p>";
			}

			if (!is_array($obj)) {
				$api_endpoint = "listMedia";
				$api_params = [
					"id" => $obj
				];
				$api_res = xhrFetch($api_endpoint, $api_params);
				if (util.valExists("success", $api_res)) {
					$obj = $api_res["data"];
				}
			}
			if ($obj) {
				switch ($obj["type"]) {
					case "image":
						$html .= "<img src='" . $htp_root . "uploads/" . $obj["src"] . "." . $obj["ext"] . "'";
						break;
					case "video":
						$html .= "<video src='" . $htp_root . "uploads/" . $obj["src"] . "." . $obj["ext"] . "'";
						break;
				}
				$html .=  " data-shape='";
				$ratio = 1;
				if ($shape) {
					case "wide":
						$ratio = 2.16;
						break;
					case "tall";
						$ratio = .6 // ??
						break;
				}
				if ($obj["ratio"] > $ratio) {
					echo "wide";
				} else {
					echo "tall";
				}
				$html .= "' loading='lazy' />";
			} else {
				return false;
			}
		
		$html .= "</div>";
	}

	return $html;
}