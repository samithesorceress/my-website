<?php
require_once($GLOBALS["php_root"] . "components/header.php");
require_once($GLOBALS["php_root"] . "components/intro.php");
require_once($GLOBALS["php_root"] . "components/card.php");

echo intro("Contact");
//get data
$about_data = false;
$api_endpoint  = "list/about";
$api_res = xhrFetch($api_endpoint);
if (valExists("success", $api_res)) {
	$about_data = $api_res["data"];
}
if (valExists("links", $about_data)) {
	$links = json_decode($about_data["links"], true);
	echo "<ul>";
	foreach($links as $link) {
		echo "<li><a href='" . $link["url"] . "'>" . $link["title"] . "</a></li>";
	}
}

require_once($GLOBALS["php_root"] . "components/footer.php");