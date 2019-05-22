<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/api/";
require_once($root . "core/init.php");
require_once($root . "core/defaults.php");
require_once($root . "core/functions.php");

if (empty($_FILES) === false && valExists("file", $_FILES)) {
	$sql_params = [];
	// File vars
	
	$file = $_FILES["file"];
	$output["file"] = $file;

	$file_name = $file["tmp_name"];
	$file_err = $file["error"];
	$file_size = $file["size"];
	$update_id = false;
	if (empty($_REQUEST) === false && valExists("update_id", $_REQUEST)) {
		$update_id = $_REQUEST["update_id"];
	}
	$output["id"] = $update_id;
	list(
		$file_width,
		$file_height,
		$file_type
	) = getimagesize($file_name);
	$sql_params["max_size"] = $file_width;
	$file_ratio = $file_width / $file_height;
	$sql_params["ratio"] = $file_ratio;

	try {
		// Validate
		if (!isset($file_err) || is_array($file_err)) {
			throw new RuntimeException("Invalid parameters.");
		}
		switch ($file_err) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException("No file sent.");
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException("Exceeded filesize limit.");
			default:
				throw new RuntimeException("Unknown errors.");
		}
	
		// Check filesize 
		if ($file_size > 5000000000) { //5 gigs
			throw new RuntimeException("Exceeded filesize limit.");
		}
	
		// Check MIME type
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		if (false === $ext = array_search(
			$finfo->file($file_name),
			array(
				"jpg" => "image/jpeg",
				"jpeg" => "image/jpeg",
				"png" => "image/png",
				"gif" => "image/gif",
				"mp4" => "video/mp4"
			),
			true
		)) {
			throw new RuntimeException("Invalid file format.");
		}

		//Save extention and type
		$sql_params["ext"] = $ext;
		switch($ext) {
			case "mp4":
				$sql_params["type"] = "video";
				break;
			default:
			$sql_params["type"] = "image";
		}
		
		// Gen hash name
		$date = date("m/d/Y h:i:s a", time());
		$new_file_name = sha1($file_name . $date);
		$sql_params["src"] = $new_file_name;

		// Attempt initial upload
		if (!move_uploaded_file(
			$file_name,
			sprintf("../../uploads/%s.%s",
				$new_file_name,
				$ext
			)
		)) {
			throw new RuntimeException("Failed initial upload.");
		}

		// Make different image resolutions
		if ($sql_params["type"] == "image" && $file_width > 200) {
			$original_file = "../../uploads/" . $new_file_name . "." . $ext;
			$total_sizes = floor($file_width / 200);
			$sql_params["sizes"] = $total_sizes;

			for ($i = 1; $i <= $total_sizes; $i += 1) {
				$new_width = $i * 200;
				$new_height = $new_width / $file_ratio;				
				$new_file = "../../uploads/" . $new_file_name . "_" . $new_width . "w." . $ext;

				// first make a copy of the file
				if (!copy($original_file, $new_file)) {
					throw new RuntimeException("Failed making resized image.");
				}

				// then resize that new file
				$src = imagecreatefromstring(file_get_contents($new_file));
				$dst = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $file_width, $file_height);
				imagedestroy($src);
				switch ($ext) {
					case "png":
						imagepng($dst, $new_file);
						break;
					case "jpg":
					case "jpeg":
						imagejpeg($dst, $new_file);
						break;
					case "gif":
						imagegif($dst, $new_file);
						break;
				}
				imagedestroy($dst);
			}
		} else {
			$sql_params["sizes"] = 0;
		}
		$output["data"] = $sql_params;
		if ($update_id) {
			$sql_where = [
				"id" => $update_id
			];
			$sql = prepareSQL("update", "media", $sql_params, $sql_where);
		} else {
			$sql = prepareSQL("insert", "media", $sql_params);
		}
		if ($sql) {
			if ($conn->query($sql)) {
			$output["success"] = true;
			if ($update_id) {
				$output["message"] = "File Updated";
			} else {
				$output["message"] = "File Uploaded";
			}
			} else {
				$output["message"] = "Query failed: " . $sql;
			}
		} else {
			$output["message"] = "Error preparing SQL statement";
		}
	} catch (RuntimeException $e) {
		// Validation failure
		$output["message"] = $e->getMessage();
	}
} else {
	// Missing $_FILES
	$output["message"] = "No `file` provided.";
}

// output results
$output = json_encode($output);
echo $output;
die();