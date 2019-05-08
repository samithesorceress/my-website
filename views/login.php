<?php

$isLoggedIn = false;
$login_msg = false;


// If currently attempting login
if (empty($_REQUEST) === false) {
    jsLogs("checking login credentials");
    
    // Sanitize provided data
	$email_input = addslashes($_REQUEST["email"]);
	$password_input = addslashes($_REQUEST["password"]);
	jsLogs("un: " . $email_input);
	jsLogs("pw: " . $password_input);
    
    // Send attempt to api
	$attempt_url = "login?email=" . $email_input . "&password=" . $password_input;
	$attempt = xhrFetch($attempt_url);
    

    // API Success (the field to check here will differ on ur own api)
    if (valExists("success", $attempt)) {
		jsLogs("correct email
 + password");
        $isLoggedIn = true;
        
		// Save token, again this field might be called something else
		$returnedUsername = $attempt["username"];
		$returnedToken = $attempt["token"];
        jsLogs($returnedToken);
        
        // Commit results to cookies
		setcookie("user_name", $returnedUsername, time() + (86400 * 30), "/");
		setcookie("user_token", $returnedToken, time() + (86400 * 30), "/");
        
        // And finally route to the next page
        //header("Location: " . $htp_root);
        require_once($php_root . "core/adminRouter.php");
	} else {
		$login_msg = "Incorrect email or password.";
		jsLogs("Incorrect email or password.");
    }
    // Not logged in
}

if (!$isLoggedIn) {
	jsLogs("requires login");
    require_once($php_root . "components/header.php");
    echo "<main>";
	echo "<h1>Login</h1>";
	if ($login_msg) {
		echo "<p class='error_msg'>" . $login_msg . "</p>";
	}
	echo "<form action='" . $htp_root . "login' method='POST'>";
		echo newFormField("email", "Email", "email");
		echo newFormField("password", "Password", "password");
		echo newFormField("login", "Login", "submit", "Login");
    echo "</form>";
    echo "</main>";
	require_once($php_root . "components/footer.php");
}
