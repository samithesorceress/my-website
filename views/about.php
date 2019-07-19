<?php
require_once($GLOBALS["php_root"] . "components/header.php");
require_once($GLOBALS["php_root"] . "components/intro.php");
require_once($GLOBALS["php_root"] . "components/card.php");

echo intro("About");
//get data
$about_data = false;
$api_endpoint  = "list/about";
$api_res = xhrFetch($api_endpoint);
if (valExists("success", $api_res)) {
	$about_data = $api_res["data"];
}
if (valExists("bio", $about_data)) {
	echo card("Sami the Sorceress", urldecode($about_data["bio"]));
}

require_once($GLOBALS["php_root"] . "components/footer.php");