<?php
require_once($php_root . "components/header.php");
require_once($php_root . "components/card.php");
require_once($php_root . "components/slideshow.php");
require_once($php_root . "components/ctas.php");
require_once($php_root . "components/intro.php");
require_once($php_root . "components/thumbnail_group.php");
	
	echo intro($document_title, "~~~~");
	echo card("Videos", false, thumbnailGroup("videos", 3), ctas("videos", "view-all"));
	echo card("Photosets", false, thumbnailGroup("photosets", 1), ctas("photosets", "view-all"));
	echo card("Store", false, thumbnailGroup("store", 1), ctas("store", "view-all"));
require_once($php_root . "components/footer.php");