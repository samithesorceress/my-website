<?php
require_once($php_root . "components/header.php");
require_once($php_root . "components/card.php");
require_once($php_root . "components/slideshow.php");
require_once($php_root . "components/ctas.php");
require_once($php_root . "components/intro.php");
	
	echo intro($document_title, "~~~~");
	echo card("Videos", false, false, ctas("videos", "view-all"));
	echo card("Photosets", false, false, ctas("photosets", "view-all"));
	echo card("Store", false, false, ctas("store", "view-all"));
require_once($php_root . "components/footer.php");