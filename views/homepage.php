<?php
require_once($php_root . "components/header.php");
require_once($php_root . "components/slideshow.php");
?><main id="main">
		<div id="spinner_0" class="spinner visible">
			<svg viewBox="0 0 50 50">
				<circle class="progress" cx="25" cy="25" r="20"/>
			</svg>
		</div>
		<h1 class="title"><?php echo $document_title; ?></h1>
		<article>
<?php require_once($php_root . "components/footer.php");