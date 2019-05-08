<?php
$errors = [];
// is attempting save
if (empty($_FILES) === false || empty($_REQUEST) === false) {
	if (empty($_REQUEST["img"]) === false) {
		if (empty($_REQUEST["text"]) === false) {
			if (empty($_REQUEST["url"]) === false) {
				$attempt_url = "newSlide?img=" . $_REQUEST["img"] . "&text=" . urlencode($_REQUEST["text"]) . "&url=" . urlencode($_REQUEST["url"]) . "&public=" . $_REQUEST["public"];
				$attempt = xhrFetch($attempt_url);
				if (valExists("success", $attempt)) {
					header("Location: http://127.0.0.1/sami-the-sorceress");
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
    <h1 class="title">Edit About</h1>
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
			echo newFormField("profile", "Profile Photo", "media_browser", 1);
			echo "</div><div class='card'>";
			echo newFormField("bio", "Bio", "textarea");
			echo "</div>";
			echo "<div class='fields card' id='infinite_link_fields'>";
				echo "<h3>Links</h3><ul>";
				echo "<li class='field'><label for='link_1_url'><span>Url</span><input id='link_1_url' name='link_1_url' type='text'/></label><label><span>Title</span><input id='link_1_title' name='link_1_title' type='text'></li>";
				echo "</ul>";
			echo "<button class='cta btn'>Add</button></div>";
			echo newFormField("save", "Save", "submit", "Save");
        ?>
        </form>
    </article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");