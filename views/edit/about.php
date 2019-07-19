<?php
require_once($GLOBALS["php_root"] . "components/admin/header.php");
require_once($GLOBALS["php_root"] . "components/card.php");
$about_api = "list/about";
$about_res = xhrFetch($about_api);
$about_data = false;
if (valExists("success", $about_res)) {
	$about_data = $about_res["data"];
}

	$profile_photo = false;
	if (valExists("profile", $about_data)) {
		$profile_photo = urldecode($about_data["profile"]);
	}
	
	echo card(
		false, 
		false, 
		newFormField("profile", "Profile Photo", "media_browser", 1, $profile_photo)
	);
	
	$bio_text = false;
	if (valExists("bio", $about_data)) {
		$bio_text = urldecode($about_data["bio"]);
	}
	echo card(
		false, 
		false, 
		newFormField("bio", "Bio", "textarea", $bio_text)
	);

	$links = false;
	if (valExists("links", $about_data)) {
		$links = $about_data["links"];
	}
	echo card(
		false, 
		false, 
		newFormField("links", "Links", "links", $links)
	);
	
	echo newFormField("save", "Save", "submit", "Save","editAbout.saveChanges");

echo "<script src='" . $GLOBALS["htp_root"] . "functions/infiniteLinks.js'></script>";
echo "<script src='" . $GLOBALS["htp_root"] . "functions/editAbout.js'></script>";
require_once($GLOBALS["php_root"] . "components/admin/footer.php");