<?php
setcookie("user_name", "logged-out", time() + (86400 * 30), "/");
setcookie("user_token", "logged-out", time() + (86400 * 30), "/");
header("Location: " . $GLOBALS["htp_root"]);
die();