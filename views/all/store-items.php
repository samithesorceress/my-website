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
		<ul id='view_all_list' data-type="store-item">
		<?php
			$store_api = "listStoreItems?rows=5&order_by=id&order_dir=DESC";
			$store_results = xhrFetch($store_api);
			if (valExists("success", $store_results)) {
				$store_items = $store_results["data"];
				if (valExists("id", $store_items)) { // only one result was returned
					$store_items = [$store_items];
				}
				if ($store_items) {
					foreach ($store_items as $store_item) {
						echo "<li id='list_item_" . $store_item["id"] . "' class='wide' data-key='" . $store_item["id"] . "' oncontextmenu='selectItems.toggle(event)'>";
							echo "<button class='btn cta fab sml' onClick='selectItems.toggle(event)'>";
								echo file_get_contents($htp_root . "src/icons/checkbox_checked.svg");
								echo file_get_contents($htp_root . "src/icons/checkbox_unchecked.svg");
							echo "</button>";
							echo "<a href='" . $admin_root . "edit/store-item/" . $store_item["id"] . "'>";
								echo mediaContainer($store_item["cover"], "wide", $store_item["title"]);
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
echo "<script src='" . $htp_root . "functions/storeManager.js'></script>";
require_once($php_root . "components/admin/footer.php");