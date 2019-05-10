<?php
$is_admin = false;
if (strpos($current_path, "admin") !== false) {
	$is_admin = true;
	require_once($php_root . "components/admin/header.php");
} else {
	require_once($php_root . "components/header.php");
}
echo "<main>";
echo "<h1>404</h1>";
echo "<p>Page not found.</p>";
echo "</main>";
if ($is_admin) {
	require_once($php_root . "components/admin/footer.php");
} else {
	require_once($php_root . "components/footer.php");
}