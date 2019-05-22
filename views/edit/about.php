<?php
$errors = [];
// is attempting save
if (empty($_REQUEST) === false) {
	// save profile and bio - those are easy
	$attempt_url = "updateAbout?profile=" . $_REQUEST["profile"] . "&bio=" . urlencode($_REQUEST["bio"]);
	
	// then prepare json object of all the links
	$urls = [];
	$titles = [];
	$links_object = "[";
	foreach($_REQUEST as $key => $val) {
		if (strpos($key, "link") !== false) {
			if (strpos($key, "url") !== false) {
				$urls[] = $val;
			} elseif (strpos($key, "title") !== false) {
				$titles[] = $val;
			}
		}
	}
	for ($i = 0; $i < count($urls); $i += 1) {
		$links_object .= "{\"url\": \"" . urlencode($urls[$i]) . "\", \"title\": \"" . $titles[$i] . "\"},";
	}
	$links_object = rtrim($links_object, ",");
	$links_object .= "]";
	$links_object = urlencode($links_object);
	$attempt_url .= "&links=" . $links_object;
	$attempt = xhrFetch($attempt_url);
	if (valExists("success", $attempt)) {
		header("Location: http://127.0.0.1/sami-the-sorceress");
		die();
	} else {
		$errors[] = $attempt["message"];
	}
}













//BEGIN OUTPUT
require_once($php_root . "components/admin/header.php");
$about_api = "listAbout";
$about_res = xhrFetch($about_api);
$about_data = false;
if (valExists("success", $about_res)) {
	$about_data = $about_res["data"];
}
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
	$profile_photo = false;
	if (valExists("profile", $about_data)) {
		$profile_photo = $about_data["profile"];
	}
	echo newFormField("profile", "Profile Photo", "media_browser", 1, $profile_photo);
	
	echo "</div><div class='card'>";
	
	$bio_text = false;
	if (valExists("bio", $about_data)) {
		$bio_text = $about_data["bio"];
	}
	echo newFormField("bio", "Bio", "textarea", $bio_text);
	echo "</div>";

	$links = false;
	if (valExists("links", $about_data)) {
		$links = $about_data["links"];
	}
	echo "<div class='fields card' id='infinite_link_fields'>";
	echo "<h3>Links</h3><ul class='links_list'>";
	if ($links) {
		$links = json_decode($links, true);
		for($i = 0; $i < count($links); $i += 1) {
			$link = $links[$i];
			echo "<li class='field'><div><label for='link_url_" . $i . "'>Url</label><input id='link_url_" . $i . "' name='link_url_" . $i . "' type='text' value='" . urldecode($link["url"]) . "'/></div><div><label for='link_title_" . $i . "'>Title</label><input id='link_title_" . $i . "' name='link_title_" . $i . "' type='text' value='" . $link["title"] . "'/></div><button class='btn' type='button' onClick='removeLink()'>" . file_get_contents($htp_root . "src/icons/delete.svg") . "</button></li>";
		}
	} else {
		echo "<li class='field'><div><label for='link_1_url'>Url</label><input id='link_1_url' name='link_1_url' type='text'/></div><div><label for='link_1_title'>Title</label><input id='link_1_title' name='link_1_title' type='text'></div><button class='btn' type='button' onClick='removeLink()'>" . file_get_contents($htp_root . "src/icons/delete.svg") . "</button></li>";
	}
	echo "</ul>";
	echo "<div class='ctas align_left'><button class='cta btn' type='button' onClick='addNewLink()'>" . file_get_contents($htp_root . "src/icons/add.svg") . "<span>Add Link</span></button></div></div>";
	echo "<script src='" . $htp_root . "functions/infiniteLinks.js'></script>";
	echo newFormField("save", "Save", "submit", "Save","editAbout.saveChanges");
?>

</form>

<?php
echo "<script src='" . $htp_root . "functions/editAbout.js'></script>";
require_once($php_root . "components/admin/footer.php");