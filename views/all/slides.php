<?php
require_once($GLOBALS["php_root"] . "components/admin/header.php");
require_once($GLOBALS["php_root"] . "components/admin/actionsBar.php");
require_once($GLOBALS["php_root"] . "components/admin/viewAll.php");
echo viewAll("slides");
echo "<script src='" . $GLOBALS["htp_root"] . "functions/slideshowManager.js'></script>";
require_once($GLOBALS["php_root"] . "components/admin/footer.php");