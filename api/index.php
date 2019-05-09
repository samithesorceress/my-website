<?php
require_once "core/init.php";
require_once "core/functions/security.php";
require_once "core/functions/values.php";

	// run sql
function runSQL($sql_command) {
	// Database connect
	$host = "68.66.224.29";
	$db = "sammurph_xxx";
	$user = "sammurph_samii";
	$pass = "SNZGydY6tLpc5qe";

	$conn = new mysqli($host, $user, $pass, $db);

	if ($conn->connect_error) {
		die("<p>Failed to connect to MySQL: " . $conn->connect_error . "</p>");
	}
	$rows = array();
	$result = $conn->query($sql_command);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	if (count($rows) == 1) {
		$rows = $rows[0];
	}
	return $rows;
}

// Check for query
if (empty($_REQUEST) === false) {
    $data = [];

	// Collect the requests
    foreach ($_REQUEST as $key => $value) {
		if ($key !== "file") {
			// Then sanitize them
			$data[$key] = addslashes($value);
		}
    }

	// Prepare to build sql query
	$sql = false;
	$sql_sel = "SELECT * FROM ";
	$sql_ins = "INSERT INTO ";
	$sql_vals = "VALUES (";
	$sql_whr = false;
	$sql_ord = false;
	$sql_lmt = " LIMIT ";
	$output = [];

	// Carry out ACTIONS
	if (valExists("action", $data)) {
		switch($data["action"]) {
			
			// Login ACTION
			case "login": {
				if (valExists("email", $data) && valExists("password", $data)) {
					
					// Lookup DB data for provided email
					$sql = $sql_sel . "`users` WHERE `email`='" . $data["email"] . "'";
					$rows = runSQL($sql);
					$res = json_encode($rows);

					// Prepare PW hash to match against
					$pass_hash = hash('sha256', $data["password"] . $rows["salt"]);

					//	Verify password in exchange for token
					if ($pass_hash == $rows["password"]) {
						$output["success"] = true;
						$output["message"] = "correct email and password combo";
						$output["username"] = $rows["username"];
						//make this hash something else 4 production lol
						$output["token"] = hash('sha256', $rows["token"] . $rows["salt"]);
						$output["user_data"] = $res;
					} else {
						$output["success"] = false;
						$output["message"] = "Bad credentials.";
					}
				} else {
					$output["success"] = false;
					$output["message"] = "Email and password required.";
				}
				break;
			}

			// Verify Token ACTION
			case "verify_token": {
				if (valExists("token", $data) && valExists("user", $data)) {

					// Lookup DB data for provided id
					$sql = $sql_sel . "`users` WHERE `username`='" . $data["user"] . "'";
					$rows = runSQL($sql);
					$res = json_encode($rows);

					// Prepare token to match against
					$gen_token = hash('sha256', $rows["token"] . $rows["salt"]);
					
					//Verify token 
					if ($data["token"] == $gen_token) {
						$output["success"] = true;
						$output["message"] = "Token verified.";
						$output["user_data"] = $res;
					} else {
						$output["success"] = false;
						$output["message"] = "Token missmatch! Invalid session should be deleted.";
					}
				} else {
					$output["success"] = false;
					$output["message"] = "ID and token required.";
				}
				break;
			}
			case "new_media": {
				if (valExists("src", $data)) {
					$sql_ins .= "`media` (src";
					$sql_vals .= "'" . $data["src"] . "'";
					if (valExists("title", $data)) {
						$sql_ins .= ",title";
						$sql_vals .= ",'" . $data["title"] . "'";
					}
					if (valExists("alt", $data)) {
						$sql_ins .= ",alt";
						$sql_vals .= ",'" . $data["title"] . "'";
					}
					if (valExists("type", $data)) {
						$sql_ins .= ",type,public)";
						$sql_vals .= ",'" . $data["type"] . "'";
						if (valExists("public", $data)) {
							$sql_vals .= ",'" . $data["public"] . "')";
						} else {
							$sql_vals .= ",'0')";
						}
						$sql = $sql_ins . $sql_vals;
						if ($conn->query($sql)) {
							$output["success"] = true;
							$output["message"] = "File Uploaded";
						} else {
							$output["success"] = false;
							$output["message"] = "Query failed: " . $sql;
						}
					} else {
						$output["success"] = false;
						$output["message"] = "Type and Visibility required.";
					}
				} else {
					$output["success"] = false;
					$output["message"] = "No image path provided.";
				}
				break;
			}
			case "list_media": {
				$sql_sel .= "`media`";
				// single img
				if (valExists("id", $data)) {
					$sql_sel .= " WHERE `id`='" . $data["id"] . "'";

				// paginate
				} else {
					//type
					if (valExists("type", $data)) {
						$sql_sel .= " WHERE `type`='" . $data["type"] . "'";
					}
					//order
					if (valExists("order_by",$data)) {
						$sql_ord = " ORDER BY " . $data["order_by"];
						if (valExists("order_dir", $data)) {
							$sql_ord .= " " . $data["order_dir"];
						}
					}
					$pagination_start = 0;
					$pagination_end = 5;
					if (valExists("offset", $data)) {
						$pagination_start = $data["offset"];
					}
					if (valExists("rows", $data)) {
						$pagination_end = (string)$pagination_start + ((int)$data["rows"] * 5);
					}
					if ($pagination_start) {
						$sql_lmt .= $pagination_start . ", ";
					}
					$sql_lmt .= $pagination_end;
				}
				$sql = $sql_sel . $sql_ord . $sql_lmt;
				$rows = runSQL($sql);
				if ($rows) {
					$output["success"] = true;
				} else {
					$output["success"] = false;
				}
				$output["data"] = $rows;
				break;
			}
			case "handle_file_upload": {
				if (empty($_FILES) === false) {
					if (valExists("file", $_FILES)) {
						$new_file = $_FILES["file"];
						list($file_width, $file_height, $file_type, $file_attr) = getimagesize($new_file["tmp_name"]);
						$output["error"][] = "file exists";
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
							$new_file_name = sha1($_FILES["file"]["tmp_name"]);
							if (!move_uploaded_file(
								$new_file['tmp_name'],
								sprintf("../uploads/%s.%s",
									$new_file_name,
									$ext
								)
							)) {
								throw new RuntimeException('Failed to move uploaded file.');
							}
							
							if ($output["data"]["type"] == "image") {
								$ratio = $file_width / $file_height;
								if ($file_width > 200) {
									$target_filename = "../uploads/" . $new_file_name . "." . $ext;
									$total_sizes = floor($file_width / 200);

									// make diff sizes
									for ($i = 1; $i <= $total_sizes; $i += 1) {

										$target_size = $i * 200;
										$new_width = $target_size;
										$new_height = $target_size / $ratio;
										$current_working_file = "../uploads/" . $new_file_name . "_" . $target_size . "w." . $ext;

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
				break;
			}
			// Invalid ACTION
			default: {
				$output["success"] = false;
				$output["message"] = "Invalid or restricted action.";
			}
		}

	} else {
		// no ACTION
		$output["success"] = false;
		$output["message"] = "Please provide an action.";
	}
} else { 
	// empty REQUEST
	$output["success"] = false;
	$output["message"] = "Please provide a query and action.";
}


// Format and print
$output = json_encode($output);
echo $output;
die();