<?php 
	require_once($php_root . "components/mediaContainer.php");
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
	echo "<title>" . $document_title . "</title>";
    
    echo (isset($document_author)) ? "<meta name='author' content='" . $document_author . "'>" : false;
    echo "<meta name='robots' content='NOINDEX NOFOLLOW'>";
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
<body class='admin'>
	<header id="app_header" class="admin_header">
    <div id="notification_bar"><span>Changes Saved!</span></div>
		<nav>
			<ul>
				<li>
					<a id="app_title" class="title" href="<?php echo $htp_root; ?>admin">Admin Zone</a>
				</li>
				<li>
					<a href="<?php echo $htp_root; ?>" target="_blank" title="View Site">
						<button class="btn">	
							 <?php echo file_get_contents($htp_root . "src/icons/public.svg"); ?>
						</button>
					</a>
				</li>
			</ul>
		</nav>
	</header>
	<main id="main">
		<div id="spinner_0" class="spinner visible">
			<svg viewBox="0 0 50 50">
				<circle class="progress" cx="25" cy="25" r="20"/>
			</svg>
		</div>
		<h1 class="title"><?php echo $document_title; ?></h1>
		<article>