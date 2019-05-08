<?php
setcookie("user_name", "logged-out", time() + (86400 * 30), "/");
setcookie("user_token", "logged-out", time() + (86400 * 30), "/");
header("Location: " . $htp_root);
die();