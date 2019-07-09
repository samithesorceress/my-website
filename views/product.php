<?php
require_once($php_root . "components/intro.php");
require_once($php_root . "components/preview.php");
require_once($php_root . "components/card.php");

$api_endpoint  = "list/" . $current_category;
$api_params = [
	"url" => $current_url_slug
];
$api_res = xhrFetch($api_endpoint, $api_params);
if (valExists("success", $api_res)) {
	$product = $api_res["data"];
	$document_title = $product["title"];
	require_once($php_root . "components/header.php");
	echo intro();
	echo preview("test");
	echo card($document_title, $product["description"]);
} else {
	$document_title = "404";
	require_once($php_root . "components/header.php");


	echo intro("404", "Unable to find this " . $current_category);
	echo card("Maybe try one of these pages...");
}

require_once($php_root . "components/footer.php");