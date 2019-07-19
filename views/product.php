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
	if (valExists("stream", $product)) {
		echo preview($product["stream"]);
	} elseif (valExists("preview", $product)) {
		echo preview($product["preview"]);
	} elseif (valExists("previews", $product)) {
		echo preview($product["previews"]);
	}
	echo "<article><section class='product_card_group'><div class='card product_section'>";
	echo "<h1>" . $document_title . "</h1>";
	echo "<p class='desc'>" . $product["description"] . "</p>";
	if (valExists("tags", $product)) {
		$tags = explode(",", str_replace(", ", ",", $product["tags"]));

		echo "<br/><span>Tags:</span><ul class='tags'>";

		

		foreach($tags as $tag) {
			echo "<li class='tag'><a href='" . $GLOBALS["htp_root"] . "search/" . $tag . "'>" . $tag . "</a></li>";
		}

		echo "</ul>";
	}
	echo "</div>";
	if (valExists("links", $product)) {
		$links = json_decode($product["links"], true);
		
		echo "<div class='card product_section'><h2>Purchase:</h2><ul id='product_links'>";
		for ($i = 0; $i < count($links); $i += 1) {
			$link = $links[$i];
			echo "<li class='product_link'><a href='" . $link["url"] . "'><button type='button' class='btn cta";
			if ($i == 0) { 
				echo " buy";
			}
			echo"'>" . $link["title"] . "</button></a></li>";
		}
		echo "</ul></aside>";
	}
} else {

	$document_title = "404";
	require_once($php_root . "components/header.php");
	echo intro("404", "Unable to find this " . $current_category);
	echo card("Maybe try one of these pages...");
}

require_once($php_root . "components/footer.php");