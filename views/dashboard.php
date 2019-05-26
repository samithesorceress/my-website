<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/admin/carousel.php");
require_once($php_root . "components/card.php");
require_once($php_root . "components/admin/ctas.php");

	$profile = "";
	$profile_photo = false;
	$bio = false;
	$links = false;
	$about_api = "list/about";
	$about_res = xhrFetch($about_api);
	if (valExists("success", $about_res)) {
		$profile_photo = $about_res["data"]["profile"];
		$bio = $about_res["data"]["bio"];
		$links = $about_res["data"]["links"];
		if ($links) {
			$links = json_decode($links, true);
		}
	}
	$profile = "<div class='profile'>";
		if ($profile_photo) {
			$profile .= mediaContainer($profile_photo, "round");
		}
		$profile .= "<div class='profile_info'><p><strong>Bio: </strong>" . $bio . "</p><p><strong>Links: </strong>";
			if ($links) {
				$links_html = "";
				foreach($links as $link) {
					$links_html .= "<a href='" . urldecode($link["url"]) . "'>" . $link["title"] . "</a>, ";
				}
				$links_html = rtrim($links_html, ", ");
				$profile .= $links_html;
			}
			$profile .= "</p>";
		$profile .= "</div>";
	$profile .= "</div>";
	echo card("About", false, $profile, ctas("about", ["update"]));

	echo card("Videos", false, carousel("videos"), ctas("videos", ["view-all", "new"]));
	
	echo card("Photosets", false, carousel("photosets"), ctas("photosets", ["view-all", "new"]));

	echo card("Store", false, carousel("store"), ctas("store", ["view-all", "new"]));

	echo card("Slideshow", false, carousel("slideshow"), ctas("slideshow", ["view-all", "new"]));

	echo card("Media Manager", false, carousel("media"), ctas("media", ["view-all", "upload"]));

require_once($php_root . "components/admin/footer.php");