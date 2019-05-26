<?php
require_once($php_root . "components/admin/header.php");
require_once($php_root . "components/admin/actionsBar.php");
?>
<section id="view_all" class="card">
	<div class='card_contents'>
		<ul id='view_all_list' data-type="video">
		<?php
			$video_api = "listVideos?rows=5&order_by=id&order_dir=DESC";
			$video_results = xhrFetch($video_api);
			if (valExists("success", $video_results)) {
				$video_items = $video_results["data"];
				if (valExists("id", $video_items)) { // only one result was returned
					$video_items = [$video_items];
				}
				if ($video_items) {
					foreach ($video_items as $video_item) {
						echo "<li id='list_item_" . $video_item["id"] . "' class='view_all_list_item wide' data-key='" . $video_item["id"] . "' oncontextmenu='selectItems.toggle(event)'>";
							echo "<button class='btn cta fab sml' onClick='selectItems.toggle(event)'>";
								echo file_get_contents($htp_root . "src/icons/checkbox_checked.svg");
								echo file_get_contents($htp_root . "src/icons/checkbox_unchecked.svg");
							echo "</button>";
							echo "<a href='" . $admin_root . "edit/video/" . $video_item["id"] . "'>";
								echo mediaContainer($video_item["cover"], "wide", $video_item["title"]);
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
echo "<script src='" . $htp_root . "functions/videoManager.js'></script>";
require_once($php_root . "components/admin/footer.php");