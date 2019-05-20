<?php
$errors = [];
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
				echo newFormField("media", "Image/Video", "file");
			echo "</div>";
			echo "<div class='card'>";
				echo newFormField("title", "Title");
				echo newFormField("alt", "Alt", "textarea");
				echo newFormField("public", "Public", "checkbox");
			echo "</div>";
			echo "<div class='field'><input id='save' name='save' type='submit' value='Save' onClick='form.clickSave()'></div>";
			?>
		</form>
	</article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");