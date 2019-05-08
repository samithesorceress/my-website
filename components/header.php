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
	<link rel="icon" href="<?php echo $htp_root; ?>favicon.png" type="image/x-icon"/>

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
					<a id="app_title" class="title" href="<?php echo $htp_root; ?>">Sami the Sorceress</a>
				</li>
				<li>
					<button id="search_btn" class="btn">
						<a href="<?php echo $htp_root; ?>search">
							<img src="<?php echo $htp_root; ?>src/icons/search.svg" class="icon">
						</a>
					</button>
				</li>
			</ul>
		</nav>
	</header>