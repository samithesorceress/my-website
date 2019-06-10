<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	<!-- document setup -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1">

	<!-- proper meta -->
    <?php
	echo "<title>" . $document_title . "</title>";
    
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
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $htp_root; ?>apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $htp_root; ?>favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $htp_root; ?>favicon-16x16.png">
	<link rel="manifest" href="<?php echo $htp_root; ?>site.webmanifest">
	<link rel="mask-icon" href="<?php echo $htp_root; ?>safari-pinned-tab.svg" color="#00ccff">
	<meta name="msapplication-TileColor" content="#00ccff">
	<meta name="theme-color" content="#00ccff">

    <!--critical styles-->
	<style>
		<?php echo file_get_contents($htp_root . "src/css/critical.css"); ?>
	</style>	
</head>
<body>
    
	<header id="app_header">
		<div id="notification_bar"><span>Under construction...</span></div>
		<nav>
			<ul>
				<li>
					<button id="menu_btn" class="btn">
						<?php echo file_get_contents($htp_root . "src/icons/menu.svg"); ?>
					</button>
				</li>
				<li>
					<a id="app_title" class="title" href="<?php echo $htp_root; ?>">sami the sorceress</a>
				</li>
				<li>
					<button id="search_btn" class="btn">
						<a href="<?php echo $htp_root; ?>search">
							<?php echo file_get_contents($htp_root . "src/icons/search.svg"); ?>
						</a>
					</button>
				</li>
			</ul>
		</nav>
	</header>
	