<?php
$errors = [];
// is attempting save
if (empty($_REQUEST) === false) {
	if (empty($_REQUEST["img"]) === false) {
		if (empty($_REQUEST["text"]) === false) {
			if (empty($_REQUEST["url"]) === false) {
				$attempt_url = "newSlide?img=" . $_REQUEST["img"] . "&text=" . urlencode($_REQUEST["text"]) . "&url=" . urlencode($_REQUEST["url"]) . "&public=" . $_REQUEST["public"];
				$attempt = xhrFetch($attempt_url);
				if (valExists("success", $attempt)) {
					header("Location: http://127.0.0.1/sami-the-sorceress/view-all/slides");
					die();
				} else {
					$errors[] = $attempt["message"];
				}
			} else {
				$errors[] = "Missing url.";
			}
		} else {
			$errors[] = "Missing text.";
		}
	} else {
		$errors[] = "Please choose an image.";
	}
}





//BEGIN OUTPUT

require_once($php_root . "components/admin/header.php");
?>
<main>
	<h1 class="title"><?php echo $document_title; ?></h1>
	<article>
		<?php
			if (count($errors) > 0) {
				echo "<div class='errors'>";
				foreach($errors as $error) {
					echo "<p>" . $error . "</p>";
				}
				echo "</div>";
			}
		?>
		<form enctype="multipart/form-data" action="<?php echo $htp_root . $current_path; ?>" method="POST">
			<?php
			echo "<div class='card'>";
				echo newFormField("img", "Image", "media_browser", 1);
			echo "</div>";
			echo "<div class='card'>";
				echo newFormField("text", "Text");
				echo newFormField("url", "Url");
				echo newFormField("public", "Public", "checkbox");
			echo "</div>";
			echo newFormField("save", "Save", "submit", "Save");
		?>
		</form>
	</article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");