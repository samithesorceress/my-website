<?php
require_once($php_root . "components/admin/header.php");
?>
<header id="view-all_actions_bar" class='card'>
	<div id="default_actions">
		<button type='button' class='btn cta sml' onClick="selectItems.selectAll()">
			<?php echo file_get_contents($htp_root . "src/icons/checkbox_checked.svg"); ?>
			<span>Select All</span>
		</button>
	</div>
	<div id='actions_for_selections' class='disabled'>
		<button type='button' class='btn cta sml' onClick="selectItems.deselectAll()">
			<?php echo file_get_contents($htp_root . "src/icons/clear.svg"); ?>
			<span>Clear Selection</span>
		</button>
		<button type='button' class='btn cta sml' data-action="edit">
			<?php echo file_get_contents($htp_root . "src/icons/edit.svg"); ?>
			<span>Edit Selected</span>
		</button>
		<button type='button' class='btn cta sml danger' data-action='delete'>
			<?php echo file_get_contents($htp_root . "src/icons/delete.svg"); ?>
			<span>Delete Selected</span>
		</button>
	</div>
</header>
<section id="view_all" class="card">
	<div class='card_contents'>
		<ul id='view_all_list' data-type="media">
		<?php
			$media_api = "listMedia?rows=5&order_by=id&order_dir=DESC";
			$media_results = xhrFetch($media_api);
			if (valExists("success", $media_results)) {
				$media_items = $media_results["data"];
				if ($media_items) {
					foreach ($media_items as $media_item) {
						echo "<li id='list_item_" . $media_item["id"] . "' data-key='" . $media_item["id"] . "' oncontextmenu='selectItems.toggle(event)'>";
							echo "<button class='btn cta fab sml' onClick='selectItems.toggle(event)'>";
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
									echo "' loading='lazy'/>";
								echo "</div>";
							echo "</a>";
						echo"</li>";
					}
					for ($i = 0; $i < 12; $i += 1) {
						echo "<li class='hidden-flex-item'></li>";
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
echo "<script src='" . $htp_root . "functions/mediaManager.js'></script>";
require_once($php_root . "components/admin/footer.php");