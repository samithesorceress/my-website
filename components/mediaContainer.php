<?php 
function mediaContainer($obj = false, $shape = false, $title = false, $timestamp = false) {
	$html = false;
	$media_root = "http://127.0.0.1/sami-the-sorceress/uploads/";

	// init wrapper
	$html = "<div class='media_container";
	if ($shape) {
		switch($shape) {
			case "wide":
				$html .= " wide_container";
				break;
			case "tall":
				$html .= " wide_container";
				break;
			case "round":
				$html .= " round_container";
				break;
			case "hd":
				$html .= " hd_container";
				break;
		}
	}
	$html .= "'>";
		// slides/videos title
		if ($title) {
			$html .= "<div class='media_title'>";
				$html .= "<span>" . $title . "</span>";
			$html .= "</div>";
		}
		if ($obj !== false) {
			if (!is_array($obj)) {
				$api_endpoint = "list/media";
				$api_params = [
					"id" => $obj
				];
				$api_res = xhrFetch($api_endpoint, $api_params);
				if (valExists("success", $api_res)) {
					$obj = $api_res["data"];
				} else {
					$obj = false;
				}
			}
		}
		$html .= "<div class='media_contents'>";
		if ($obj) {
			if (valExists("type", $obj)) {
				switch ($obj["type"]) {
					case "image":
						$html .= "<img src='" . $media_root . $obj["src"] . "." . $obj["ext"] . "'";
						if (valExists("sizes", $obj)) {
							$sizes = intval($obj["sizes"]);
							$html .= "srcset='";
							for ($i = 0; $i < $sizes; $i += 1) {
								$size = ($i + 1) * 200;
								$html .= $media_root . $obj["src"] . "_" . $size . "w." . $obj["ext"] . " " . $size . "w, ";
							}
							$html = rtrim($html, ", ") . "'";
						}
						break;
					case "video":
						$html .= "<video src='" . $media_root . $obj["src"] . "." . $obj["ext"] . "'";
						break;
				}
			}
			if (valExists("ratio", $obj)) {
				$html .=  " data-shape='";
				$ratio = 1;
				if ($shape) {
					switch($shape) {
						case "wide":
							$ratio = 2.16;
							break;
						case "tall":
							$ratio = .6; // ??
							break;
						case "hd":
							$ratio = 1.77;
							break;
					}
				}
				if ($obj["ratio"] > $ratio) {
					$html .= "wide";
				} else {
					$html .= "tall";
				}
				$html .= "' />";
			}
		} else {
			$html .= "<img src='http://127.0.0.1/sami-the-sorceress/src/imgs/placeholder.png' alt='Placeholder image for missing file.' title='Missing file.' data-shape='wide' loading='lazy' />";
		}
		if ($timestamp) {
			$html .= "<span class='timestamp'>" . $timestamp . "</span>";
		}
	$html .= "</div></div>";

	return $html;
}