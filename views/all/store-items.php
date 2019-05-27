<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/admin/actionsBar.php");
require_once($php_root . "components/admin/viewAll.php");
echo viewAll("store-items");
echo "<script src='" . $htp_root . "functions/storeManager.js'></script>";
require_once($php_root . "components/admin/footer.php");