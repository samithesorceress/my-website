<?php
require_once($php_root . "core/publicRouter.php");

if (strpos($current_path, "admin") !== false) {
	jsLogs("checking login...");
	if ($user_name && $user_token) {
		jsLogs("has cookies");
		$verification = xhrFetch("verifyToken/index.php?user=" . $user_name . "&token=" . $user_token);
		if (valExists("success", $verification)) {
			$current_user_data = json_decode($verification["user_data"], true);
			jsLogs("token verified");
			require_once($php_root . "core/adminRouter.php");
		} else {
			jsLogs("bad token");
			header("Location: " . $htp_root . "login");
		}
	} else {
		jsLogs("no cookies");
		header("Location: " . $htp_root . "login");
	}
}