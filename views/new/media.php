<?php
$errors = [];
// is attempting save
if (empty($_FILES) === false) {
	//api vars
	$api_endpoint = "newMedia";
	$api_params = [];
	
	//file vars
	$tmp_name = $_FILES["media"]["tmp_name"];
	$file_err = $_FILES["media"]["error"];
	$file_size = $_FILES["media"]["size"];

	
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
			$finfo->file($tmp_name),
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
		switch($ext) {
			case "mp4":
				$api_params["type"] = "video";
				break;
			default:
				$api_params["type"] = "image";
		}
		
		// Gen hash name
		$date = date('m/d/Y h:i:s a', time());
		$new_name = sha1($tmp_name . $date);
		if (!move_uploaded_file(
			$tmp_name,
			sprintf($php_root . 'uploads/%s.%s',
				$new_name,
				$ext
			)
		)) {
			throw new RuntimeException('Failed to move uploaded file.');
		}

		// Successfully uploaded, now let's add it to the database.
		$api_params["src"] = $new_name;
		$api_params["ext"] = $ext;
		
		// check for non-required values, and add them if they exist
		if (empty($_REQUEST) === false) {
			$data = getValues($_REQUEST);

			if (valExists("title", $data)) {
				$api_params["title"] = $data["title"];
			}
			if (valExists("alt", $data)) {
				$api_params["alt"] = $data["alt"];
			}
			if (valExists("public", $data)) {
				$api_params["public"] = 1;
			} else {
				$api_params["public"] = 0;
			}	
		} else {
			$api_params["public"] = 0;
		}

		// Make request
		$media_req = xhrFetch($api_endpoint, $api_params);
		if (valExists("success", $media_req)) {
			header("Location: " . $admin_root . "view-all/media");
			die();
		} else {
			$errors[] = "Server error: " . $media_req["message"];
		}
	} catch (RuntimeException $e) {
		// Validation failure
		$errors[] = $e->getMessage();
	}
} elseif (empty($_REQUEST) === false) {
	// Missing $_FILES input
	$errors[] = "Please select an image or video.";
}






//BEGIN OUTPUT

require_once($php_root . "components/admin/header.php");
?>
<main>
    <h1 class="title"><?php echo $document_title; ?></h1>
    <article>
		<?php
			if (count($errors) > 0) {
				echo "<div class='errors'>";
				foreach($errors as $error) {
					echo "<p>" . $error . "</p>";
				}
				echo "</div>";
			}
		?>
		<form enctype="multipart/form-data" action="<?php echo $htp_root . $current_path; ?>" method="POST">
			<?php
			echo "<div class='card'>";
				echo newFormField("media", "Image/Video", "file");
			echo "</div>";
			echo "<div class='card'>";
				echo newFormField("title", "Title");
				echo newFormField("alt", "Alt", "textarea");
				echo newFormField("public", "Public", "checkbox");
			echo "</div>";
			echo newFormField("save", "Save", "submit", "Save");
			?>
		</form>
	</article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");