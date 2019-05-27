		</article>
	</main>
	<footer id="footer">
		<nav id="footer_menu">
			<ul><?php
				echo "<li><a href='" . $htp_root . "logout'>Logout</a></li>";
			?></ul>
		</nav>

	</footer>
	<?php
	if (isset($fonts)) {
		echo "<link href='https://fonts.googleapis.com/css?family=" . $fonts . "' rel='stylesheet' media='none' onload='if(media!=\"all\")media=\"all\"'>";
	}
	?>
	<link href="<?php echo $htp_root; ?>src/css/App.css" rel="stylesheet" media="all">
	<link href="<?php echo $htp_root; ?>src/css/Admin.css" rel="stylesheet" media="all">
	<script src="<?php echo $htp_root; ?>functions/utils.js"></script>
	<script src="<?php echo $htp_root; ?>functions/forms.js"></script>
	<script src="<?php echo $htp_root; ?>components/dialogBox.js"></script>
	<script src="<?php echo $htp_root; ?>components/admin/mediaBrowser.js"></script>
	<script src="<?php echo $htp_root; ?>functions/selectItems.js"></script>
	<link href="<?php echo $htp_root; ?>src/css/App.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
	<noscript>
		<?php
		if (isset($fonts)) {
			echo "<link href='https://fonts.googleapis.com/css?family=" . $fonts . "' rel='stylesheet' media='all'>";
		}
		?>
	</noscript>
</body>
</html>
