		</article>
	</main>
	<footer id="footer">
		<nav id="footer_menu">
			<ul><?php
				echo "<li><a href='" . $GLOBALS["htp_root"] . "'>Homepage</a></li>";
				echo "<li><a href='" . $GLOBALS["htp_root"] . "about'>About</a></li>";
				echo "<li><a href='" . $GLOBALS["htp_root"] . "videos'>Videos</a></li>";
				echo "<li><a href='" . $GLOBALS["htp_root"] . "photosets'>Photosets</a></li>";
				echo "<li><a href='" . $GLOBALS["htp_root"] . "store'>Store</a></li>";
				echo "<li><a href='" . $GLOBALS["htp_root"] . "contact'>Contact</a></li>";
			?></ul>
		</nav>

	</footer>
	<?php
	if (isset($fonts)) {
		echo "<link href='https://fonts.googleapis.com/css?family=" . $fonts . "' rel='stylesheet' media='none' onload='if(media!=\"all\")media=\"all\"'>";
	}
	?>
	<link href="<?php echo $GLOBALS["htp_root"]; ?>src/css/App.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
	<script src="<?php echo $GLOBALS["htp_root"]; ?>functions/utils.js"></script>
	<script async="true" src="<?php echo $GLOBALS["htp_root"]; ?>components/slideshow.js"></script>
	<noscript>
		<?php
		if (isset($fonts)) {
			echo "<link href='https://fonts.googleapis.com/css?family=" . $fonts . "' rel='stylesheet' media='all'>";
		}
		?>
		<link href="<?php echo $GLOBALS["htp_root"]; ?>src/css/App.css" rel="stylesheet" media="all">
	</noscript>
</body>
</html>
