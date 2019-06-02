<?php

function ucSmart($string){//smart ucwords function
	return preg_replace_callback("/\b(A|An|The|And|Of|But|Or|For|Nor|With|On|At|To|From|By)\b/i",function($matches){//add words here to avoid capitalization
		return strtolower($matches[1]);
	},ucwords($string));
}
function valExists($key, $arr) {
	if (is_array($arr)) {
		if (array_key_exists($key, $arr) && $arr[$key]) {
			return true;
		}
		return false;
	}
	return false;
}
function formatParams($params){
	$obj = false;
	if (is_array($params)) {
		$obj = "?";
		foreach($params as $key => $val) {
			$obj .= $key . "=" . urlencode($val) . "&";
		}
		$obj = rtrim($obj, "&");
	}
	return $obj;
}
function xhrFetch($endpoint, $params = false) {
	if (strpos($endpoint, 'http') !== 0) {
		$xhr_url = "http://127.0.0.1/sami-the-sorceress/api/" . $endpoint;

	} else {
		$xhr_url = $endpoint;
	}
	if ($params) {
		$params = formatParams($params);
		if ($params) {
			$xhr_url .= $params;
		}
	}
	jsLogs("xhrFetching: " . $xhr_url);
	$xhr_res = "";
	//if (function_exists("curl_init")) {
	//	$ch = curl_init();
	  //  curl_setopt($ch, CURLOPT_URL, $url);
	    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   // $xhr_res = curl_exec($ch);
	   // curl_close($ch);
	//} else {
		$xhr_res = file_get_contents($xhr_url);
		jsLogs($xhr_res);
	//}
	if (strlen($xhr_res) > 0) {
		$xhr_first = substr($xhr_res, 0, 1);
		if ($xhr_first == "{" || $xhr_first == "[") {
			$xhr_json = json_decode($xhr_res, true);
			if (is_array($xhr_json)) {
				return $xhr_json;
			} else {
				return $xhr_res;
			}
		}
		return $xhr_res;
	} else {
		return false;
	}
}
function jsLogs($data) {
    $html = "";
    $coll;

    if (is_array($data) || is_object($data)) {
        $coll = json_encode($data);
    } else {
        $coll = $data;
    }

    $html = "<script>console.log('PHP: ".$coll."');</script>";

    //echo($html);
}
function newFormField($id, $name, $type = "text", $val = false, $val2 = false, $val3 = false) {
	$input = "<div class='field";
	switch($type) {
		case "text":
		case "password":
		case "email":
		case "tel":
		case "file":
		case "files":
		case "date":
			$input .= "'><label for='" . $id . "'>" . $name . "</label><input id='" . $id . "' name='" . $id . "' type='" . $type . "'";
			if ($val) {
				$input .= "value='" . $val . "'";
			}
			$input .= "/>";
			break;
		case "textarea":
			$input .= " textarea_field'><label for='" . $id . "'>" . $name . "</label><textarea id='" . $id . "' name='" . $id . "'>";
			if (is_array($val)) {
				foreach($val as $v) {
					$input .= $v ."
					";
				}
			}
			if ($val) {
				$input .= $val;
			}
			$input .= "</textarea>";
			break;
		case "checkbox":
			$input .= " checkbox_field'><input id='" . $id . "' name='" . $id . "' type='" . $type . "'";
			if ($val) {
				$input .= " checked";
			}
			$input .= " /><label class='checkbox_label' for='" . $id . "'>" . $name . "</label>";
			break;
		case "select":
			$input .= "'><label for='" . $id . "'>" . $name . "</label><select id='" . $id . "' name='" . $id . "'><option value='null'>Select One</option>";
				if(is_array($val)) {
					foreach($val as $v) {
						$input .= "<option value='" . $v . "'>" . $v . "</option>";
					}
				} else {
					$input .= "<option value='" . $val . "'>" . $val . "</option>";
				}
			$input .= "</select>";
			break;
		case "media_browser":
		case "photo_browser":
		case "video_browser":
			$input .= " media_browser_field'><label for='" . $id . "'>" . $name . "</label>";
			if ($val2) {
				$input .= mediaContainer($val2);
			} else {
				$input .= "<div class='media_container'><p>No Media Selected</p></div>";
			}
			$input .= "<input id='" . $id . "' name='" . $id . "' type='hidden'";
			if ($val2) {
				$input .= " value='" . $val2 . "'";
			}
			$input .= "/><button id='" . $id . "_browser' type='button' class='btn cta sml media_browser_btn";
				if ($val !== 1) {
					$input .= " multi";
				}
				if ($type == "photo_browser") {
					$input .= " photos_only";
				} elseif ($type == "video_browser") {
					$input .= " videos_only";
				}
			$input .= "' data-action='";
			if ($val3) {
				$input .= "replace'><span>Replace";
			} else {
				$input .= "browse'><span>Browse";
			}
			$input .= "</span></button>";
			break;
		case "submit":
			$input .= "'><input id='" . $id . "' name='" . $id . "' type='" . $type . "'";
			if ($val) {
				$input .= "value='" . $val . "'";
			}
			if ($val2) {
				$input .= "data-cb='" . $val2 . "'";
			}
			$input .= "/>";
			break;
		case "hidden":
			$input .= "'><input id='" . $id . "' name='" . $id . "' type='hidden' value='" . $val . "'/>";
			break;
		case "links":
		case "infinite_links":
			$input .= "s infinite_links' id='" . $id . "'><h3>" . $name . "</h3><ul class='links_list'>";
			if ($val) {
				//has data, build existing links
				if (!is_array($val)) {
					$val = json_decode($val, true);
				}
				for($i = 0; $i < count($val); $i += 1) {
					$link = $val[$i];
					$input .= "<li class='field'><div><label for='link_url_" . $i . "'>Url</label><input id='link_url_" . $i . "' name='link_url_" . $i . "' type='text' value='" . urldecode($link["url"]) . "'/></div><div><label for='link_title_" . $i . "'>Title</label><input id='link_title_" . $i . "' name='link_title_" . $i . "' type='text' value='" . $link["title"] . "'/></div><button class='btn delete_link_btn' type='button'>" . icon("delete") . "</button></li>";
				}
			} else {
				//fresh input, build placeholder
				$input .= "<li class='field'><div><label for='link_1_url'>Url</label><input id='link_1_url' name='link_1_url' type='text'/></div><div><label for='link_1_title'>Title</label><input id='link_1_title' name='link_1_title' type='text'></div><button class='btn delete_link_btn' type='button'>" . icon("delete") . "</button></li>";
			}
			$input .= "</ul><div class='ctas align_left'><button class='cta btn add_link_btn' type='button'>" . icon("add") . "<span>Add Link</span></button></div>";
			break;
	}
	$input .= "</div>";
	return $input;
}

function getValues($input) {
	$data = [];
	foreach ($input as $key => $value) {
		if ($key !== "file") {
			// Then sanitize them
			$data[$key] = addslashes($value);
		}
	}
	return $data;
}

function checkRequired($required, $input) {
	$res = [
		"success" => false,
		"missing" => []
	];

	if ($required !== false && $input !== false) {
		if (is_array($required) && is_array($input)) {
			foreach ($required as $req) {
				if (!valExists($req, $input)) {
					$res["missing"][] = $req;
				}
			}
			if (count($res["missing"]) < 1) {
				$res["success"] = true;
			}
		}
	}

	return $res;
}

function mediaContainer($obj, $shape = false, $title = false) {
	$html = false;
	$media_root = "http://127.0.0.1/sami-the-sorceress/uploads/";
	if ($obj) {
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
			}
		}
		$html .= "'>";
			// slides/videos title
			if ($title) {
				$html .= "<p class='title'>" . $title . "</p>";
			}

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
			if ($obj) {
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
				$html .=  " data-shape='";
				$ratio = 1;
				if ($shape) {
					switch($shape) {
						case "wide":
							$ratio = 2.16;
							break;
						case "tall";
							$ratio = .6; // ??
							break;
					}
				}
				if ($obj["ratio"] > $ratio) {
					$html .= "wide";
				} else {
					$html .= "tall";
				}
				$html .= "' loading='lazy' />";
			} else {
				$html .= "<img src='http://127.0.0.1/sami-the-sorceress/src/imgs/placeholder.png' alt='Placeholder image for missing file.' title='Missing file.' data-shape='wide' loading='lazy' />";
			}
		
		$html .= "</div>";
	}

	return $html;
}
function icon($src) {
	$img = file_get_contents("http://127.0.0.1/sami-the-sorceress/src/icons/" . $src . ".svg");
	if ($img) {
		return $img;
	}
	return false;
}