<?php
require_once($php_root . "components/header.php");
require_once($php_root . "components/card.php");
require_once($php_root . "components/slideshow.php");
require_once($php_root . "components/ctas.php");
require_once($php_root . "components/intro.php");
require_once($php_root . "components/thumbnail_group.php");
	
	echo intro($document_title, "~~~~");

	echo "<div class='card'><h2>Latest Videos</h2>";
		echo thumbnailGroup("videos", 1);
		echo ctas("videos", "view-all");
	echo "</div>";

	echo "<div class='card'><h2>Photosets</h2>";
		echo thumbnailGroup("photosets", 1);
		echo ctas("photosets", "view-all");
	echo "</div>";

	echo "<div class='card'><h2>Store</h2>";
		echo thumbnailGroup("store", 1);
		echo ctas("store", "view-all");
	echo "</div>";

	require_once($php_root . "components/footer.php");