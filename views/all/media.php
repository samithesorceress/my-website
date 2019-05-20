<?php
require_once($php_root . "components/admin/header.php");
?>
<header id="view-all_actions_bar" class='card'>
	<div>
		<?php echo newFormField("select_all","Select All","checkbox"); ?>
	</div>
	<div id='actions_for_selections' class='disabled'>
		<button type='button' class='btn cta sml'>
			<?php echo file_get_contents($htp_root . "src/icons/edit.svg"); ?>
			<span>Edit</span>
		</button>
		<button type='button' class='btn cta sml danger'>
			<?php echo file_get_contents($htp_root . "src/icons/delete.svg"); ?>
			<span>Delete</span>
		</button>
	</div>
</header>
<section id="view_all" class="card">
	<div class='card_contents'>
		<ul class='view_all_list'>
		<?php
			$media_api = "listMedia?rows=5&order_by=id&order_dir=DESC";
			$media_results = xhrFetch($media_api);
			if (valExists("success", $media_results)) {
				$media_items = $media_results["data"];
				if ($media_items) {
					foreach ($media_items as $media_item) {
						echo "<li data-key='" . $media_item["id"] . "'>";
							echo "<button class='btn cta fab sml'>";
								echo file_get_contents($htp_root . "src/icons/checkbox_checked.svg");
								echo file_get_contents($htp_root . "src/icons/checkbox_unchecked.svg");
							echo "</button>";
							echo "<a href='" . $admin_root . "edit/media/" . $media_item["id"] . "'>";
								echo "<div class='media_container'>";
									switch ($media_item["type"]) {
										case "image": {
											echo "<img src='" . $htp_root . "uploads/" . $media_item["src"] . "." . $media_item["ext"] . "'";
											break;
										}
										case "video": {
											echo "<video src='" . $htp_root . "uploads/" . $media_item["src"] . "." . $media_item["ext"] . "'";
											break;
										}
									}
									echo " data-shape='";
									if ($media_item["ratio"] > 1) {
										echo "wide";
									} else {
										echo "tall";
									}
									echo "'/>";
								echo "</div>";
							echo "</a>";
						echo"</li>";
					}
				}
			} else {
				echo "No Results";
			}
	?>

	</ul>
	</div>
</section>

<?php
require_once($php_root . "components/admin/footer.php");