<?php 
	require_once($GLOBALS["php_root"] . "components/mediaContainer.php");
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	<!-- document setup -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1">

	<!-- proper meta -->
    <?php
	echo "<title>" . $document_title . " · Sami the Sorceress</title>";
    
    echo (isset($document_author)) ? "<meta name='author' content='" . $document_author . "'>" : false;
    echo (isset($robots_txt)) ? "<meta name='robots' content='" . $robots_txt . "'>" : false;
    echo (isset($document_version)) ? "<meta name='version' content='" . $document_version . "'>" : false;
    echo (isset($last_updated)) ? "<meta name='creation_date' content='" . $last_updated . "'>" : false;
    echo (isset($lang)) ? "<meta name='language' content='" . $lang . "'>" : "<meta name='language' content='en'>";
    ?>
	<link rel="alternate" href="<?php echo $document_url; ?>" hreflang="x-default">
	<link rel="alternate" href="<?php echo $document_url; ?>" hreflang="en">
	<link rel="cannonical" href="<?php echo $document_url; ?>">

    <!--favicon-->
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $GLOBALS["htp_root"]; ?>apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $GLOBALS["htp_root"]; ?>favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $GLOBALS["htp_root"]; ?>favicon-16x16.png">
	<link rel="manifest" href="<?php echo $GLOBALS["htp_root"]; ?>site.webmanifest">
	<link rel="mask-icon" href="<?php echo $GLOBALS["htp_root"]; ?>safari-pinned-tab.svg" color="#00ccff">
	<meta name="msapplication-TileColor" content="#00ccff">
	<meta name="theme-color" content="#00ccff">

    <!--critical styles-->
	<style>
		<?php echo file_get_contents($GLOBALS["htp_root"] . "src/css/critical.css"); ?>
	</style>	
</head>
<body>
    
	<header id="app_header">
		<div id="notification_bar"><span>Under construction...</span></div>
		<ul class="static_nav">
			<li>
				<a href="<?php echo $GLOBALS["htp_root"]; ?>mailing-list/signup">
					<button id="mailing_list_btn" class="btn">
						<?php echo file_get_contents($GLOBALS["htp_root"] . "src/icons/mail.svg"); ?>
					</button>
				</a>
			</li>
			<li>
				<a id="app_title" class="title" href="<?php echo $GLOBALS["htp_root"]; ?>"><?php echo file_get_contents($GLOBALS["htp_root"] . "src/imgs/logo.svg"); ?></a>
			</li>
			<li>
				<a href="<?php echo $GLOBALS["htp_root"]; ?>search" class='header_btn'>
					<button id="search_btn" class="btn">
						<?php echo file_get_contents($GLOBALS["htp_root"] . "src/icons/search.svg"); ?>
					</button>
				</a>
			</li>
		</ul>
		<input type="checkbox" id="menu_checkbox" name="menu_checkbox" />
		<label for="menu_checkbox" id="menu_btn" class="btn">
			<?php echo file_get_contents($GLOBALS["htp_root"] . "src/icons/menu.svg"); ?>
		</label>
		<label for="menu_checkbox" id="menu_shadow"></label>
		<ul id="menu">
		<?php
		echo "<li><a href='" . rtrim($GLOBALS["htp_root"], "/") . "'>Homepage</a></li>";
		echo "<li><a href='" . $GLOBALS["htp_root"] . "about'>About</a></li>";
		echo "<li><a href='" . $GLOBALS["htp_root"] . "videos'>Videos</a></li>";
		echo "<li><a href='" . $GLOBALS["htp_root"] . "photosets'>Photosets</a></li>";
		echo "<li><a href='" . $GLOBALS["htp_root"] . "store'>Store</a></li>";
		echo "<li><a href='" . $GLOBALS["htp_root"] . "contact'>Contact</a></li>";
		?>
		</ul>
	</header>
	<main id='main'>