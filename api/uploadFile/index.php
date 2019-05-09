<?php
$root = $_SERVER['DOCUMENT_ROOT'] . "/sami-the-sorceress/";
require_once($root . "api/core/init.php");
require_once($root . "api/core/defaults.php");
require_once($root . "api/core/functions/security.php");
require_once($root . "api/core/functions/valExists.php");

	if (empty($_FILES) === false) {
		if (valExists("file", $_FILES)) {
			$new_file = $_FILES["file"];
			list($file_width, $file_height, $file_type, $file_attr) = getimagesize($new_file["tmp_name"]);
			try {
				$output["data"] = [];
				// Undefined | Multiple Files | $_FILES Corruption Attack
				// If this request falls under any of them, treat it invalid.
				if (
					!isset($new_file['error']) ||
					is_array($new_file['error'])
				) {
					throw new RuntimeException('Invalid parameters.');
				}
			
				// Check $new_file['error'] value.
				switch ($new_file['error']) {
					case UPLOAD_ERR_OK:
						break;
					case UPLOAD_ERR_NO_FILE:
						throw new RuntimeException('No file sent.');
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						throw new RuntimeException('Exceeded filesize limit.');
					default:
						throw new RuntimeException('Unknown errors.');
				}
			
				// You should also check filesize here. 
				if ($new_file['size'] > 5000000000) { //5 gigs
					throw new RuntimeException('Exceeded filesize limit.');
				}
			
				// DO NOT TRUST $new_file['mime'] VALUE !!
				// Check MIME Type by yourself.
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				if (false === $ext = array_search(
					$finfo->file($new_file['tmp_name']),
					array(
						"jpg" => "image/jpeg",
						"png" => "image/png",
						"gif" => "image/gif",
						"mp4" => "video/mp4"
					),
					true
				)) {
					throw new RuntimeException('Invalid file format.');
				}
				switch($ext) {
					case "mp4":
					case "mov":
						$output["data"]["type"] = "video";
						break;
					default:
						$output["data"]["type"] = "image";
				}
				
				// You should name it uniquely.
				// DO NOT USE $new_file['name'] WITHOUT ANY VALIDATION !!
				// On this example, obtain safe unique name from its binary data.
				$new_file_name = sha1($new_file['tmp_name']);
				if (!move_uploaded_file(
					$new_file['tmp_name'],
					sprintf("../../uploads/%s.%s",
						$new_file_name,
						$ext
					)
				)) {
					throw new RuntimeException('Failed to move uploaded file.');
				}
				
				if ($output["data"]["type"] == "image") {
					$ratio = $file_width / $file_height;
					if ($file_width > 200) {
						$target_filename = "../../uploads/" . $new_file_name . "." . $ext;
						$total_sizes = floor($file_width / 200);

						// make diff sizes
						for ($i = 1; $i <= $total_sizes; $i += 1) {

							$target_size = $i * 200;
							$new_width = $target_size;
							$new_height = $target_size / $ratio;
							$current_working_file = "../../uploads/" . $new_file_name . "_" . $target_size . "w." . $ext;

							// first make a copy of the file
							if (!copy($target_filename, $current_working_file)) {
								throw new RuntimeException('Failed to move uploaded file.');
							}

							// then resize that new file
							$src = imagecreatefromstring(file_get_contents($current_working_file));
							$dst = imagecreatetruecolor($new_width, $new_height);
							imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $file_width, $file_height);
							imagedestroy($src);
							switch ($ext) {
								case "png":
									imagepng($dst, $current_working_file);
									break;
								case "jpg":
								case "jpeg":
									imagejpeg($dst, $current_working_file);
									break;
								case "gif":
									imagegif($dst, $current_working_file);
									break;
							}
							imagedestroy($dst);
						}
					} else {
						$total_sizes = 0;
					}
				}



				$output["data"]["src"] = $new_file_name;
				$output["data"]["ext"] = $ext;

				$sql_ins .= "`media` (src, ext, type, sizes, max_size, ratio) ";
				$sql_vals .= "'" . $new_file_name . "', '" . $ext . "', '" . $output["data"]["type"] . "', '" . $total_sizes . "', '" . $file_width . "', '" . $ratio . "')";

				$sql = $sql_ins . $sql_vals;
				if ($conn->query($sql)) {
					$output["success"] = true;
					$output["message"] = "File Uploaded";
				} else {
					$output["success"] = false;
					$output["message"] = "Query failed: " . $sql;
				}
			} catch (RuntimeException $e) {
	
				echo $e->getMessage();
			
			}
		} else {
			$output["success"] = false;
			$output["message"] = "nothing was uploaded";
			$output["data"] = "empty";
		}
	} else {
		$output["success"] = false;
		$output["message"] = "nothing was uploaded";
		$output["data"] = "empty";
	}

// output results
$output = json_encode($output);
echo $output;
die();