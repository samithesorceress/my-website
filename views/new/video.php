<?php
$errors = [];
// is attempting save
if (empty($_REQUEST) === false) {

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
				echo newFormField("cover", "Cover", "media_browser", 1);
				echo newFormField("preview", "Preview View", "media_browser", 1);
			echo "</div>";
			echo "<div class='card'>";
				echo newFormField("title", "Title");
				echo newFormField("desc", "Description", "textarea");
				echo newFormField("tags", "Tags", "textarea");
				echo newFormField("price", "Price");
			echo "</div>";
			echo "<div class='card'>";
				echo "<h3>Purchase Links</h3>";
				// todo: infinite link fields
			echo "</div>";
			echo "<div class='card'>";
				echo newFormField("date", "Release Date", "date");
				echo newFormField("public", "Public", "checkbox");
			echo "</div>";
			echo newFormField("save", "Save", "submit", "Save");
			?>
		</form>
	</article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");