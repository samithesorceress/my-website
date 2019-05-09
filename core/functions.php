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
function xhrFetch($url, $params = false) {
	if (strpos($url, 'http') !== 0) {
		$xhr_url = "http://127.0.0.1/sami-the-sorceress/api/" . $url;

	} else {
		$xhr_url = $url;
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
function newFormField($id, $name, $type = "text", $val = false, $val2 = false) {
	$html = "<div class='field";
	$input = "";
	switch($type) {
		case "text":
		case "password":
		case "email":
		case "tel":
		case "file":
		case "files":
		case "date":
			$input = "'><label for='" . $id . "'>" . $name . "</label><input id='" . $id . "' name='" . $id . "' type='" . $type . "'";
			if ($val) {
				$input .= "value='" . $val . "'";
			}
			$input .= "/>";
			break;
		case "textarea":
			$input = " textarea_field'><label for='" . $id . "'>" . $name . "</label><textarea id='" . $id . "' name='" . $id . "'>";
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
			$input = " checkbox_field'><input id='" . $id . "' name='" . $id . "' type='" . $type . "'";
			if ($val) {
				$input .= " checked";
			}
			$input .= " /><label class='checkbox_label' for='" . $id . "'>" . $name . "</label>";
			break;
		case "select":
			$input = "'><label for='" . $id . "'>" . $name . "</label><select id='" . $id . "' name='" . $id . "'><option value='null'>Select One</option>";
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
			$num = $val;
			$val = $val2;
			$media_data = false;
			if ($val) {
				$media_api = "listMedia?id=" . $val;
				$media_res = xhrFetch($media_api);
				$media_data = false;
				if (valExists("success", $media_res)) {
					$media_data = $media_res["data"];
				}
			}
			$input = "'><label for='" . $id . "'>" . $name . "</label><div class='media_container' style='height:auto'>";
			if ($media_data) {
				$input .= "<img src='http://127.0.0.1/sami-the-sorceress/uploads/" . $media_data["src"] . "." . $media_data["ext"] . "' alt='" . $media_data["alt"] . "' title='" . $media_data["title"] . "'/>";
			} else {
				$input .= "<p>No Media Selected</p>";
			}
			$input .= "</div><input id='" . $id . "' name='" . $id . "' type='hidden'";
			if ($val) {
				$input .= " value='" . $val . "'";
			}
			$input .= "/><button id='" . $id . "_browser' type='button' class='btn cta media_browser_btn";
				if ($num !== 1) {
					$input .= " multi";
				}
				if ($type == "photo_browser") {
					$input .= " photos_only";
				} elseif ($type == "video_browser") {
					$input .= " videos_only";
				}
			$input .= "' onClick='openMediaBrowser()'><span>";
			if ($val) {
				$input .= "Replace";
			} else {
				$input .= "Browse";
			}
			$input .= "</span></button>";
			break;
		case "submit":
			$input = "'><input id='" . $id . "' name='" . $id . "' type='" . $type . "'";
			if ($val) {
				$input .= "value='" . $val . "'";
			}
			$input .= "/>";
			break;
		case "hidden":
			$input = "'><input id='" . $id . "' name='" . $id . "' type='hidden' value='" . $val . "'/>";
			break;
	}
	$html .= $input;
	$html .= "</div>";
	return $html;
}
