<?php
require_once($php_root . "components/admin/header.php");
?>

<section class="card">
	<h2 class="title">
		<?php echo file_get_contents($htp_root . "src/icons/text.svg"); ?>
		<span>About</span>
	</h2>
	<?php
	$about_api = "listAbout";
	$about_res = xhrFetch($about_api);
	$profile_photo = false;
	$bio = false;
	$links = false;
	if (valExists("success", $about_res)) {
		$profile_photo = $about_res["data"]["profile"];
		$bio = $about_res["data"]["bio"];
		$links = $about_res["data"]["links"];
		if ($links) {
			$links = json_decode($links, true);
		}
	}
	echo "<div class='profile'>";
		if ($profile_photo) {
			echo mediaContainer($profile_photo, "round");
		}
		echo "<div class='profile_info'>";
			echo "<p><strong>Bio: </strong>" . $bio . "</p>";
			echo "<p><strong>Links: </strong>";
			if ($links) {
				$links_html = "";
				foreach($links as $link) {
					$links_html .= "<a href='" . urldecode($link["url"]) . "'>" . $link["title"] . "</a>, ";
				}
				$links_html = rtrim($links_html, ", ");
				echo $links_html;
			}
			echo "</p>";
		echo "</div>";
	echo "</div>";
	?>
	<div class="ctas">
		<a href="<?php echo $admin_root; ?>edit/about">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/edit.svg"); ?>
				<span>Update</span>
			</button>
		</a>
	</div>
	</section>
<section class="card">
	<h2 class="title">
		<?php echo file_get_contents($htp_root . "src/icons/movie.svg"); ?>
		<span>Videos</span>
	</h2>
	<div class="carousel">
	<?php
		$videos_api = "listVideos?rows=3&order_by=id&order_dir=DESC";
		$videos_res = xhrFetch($videos_api);
		if (valExists("success", $videos_res)) {
			$videos = $videos_res["data"];
			if ($videos) {
				if (valExists("id", $videos)) { // only one result was returned
					$videos = [$videos];
				}
				foreach ($videos as $video) {
					echo "<li><a href='" . $admin_root . "edit/video/" . $video["id"] . "'>";
						echo mediaContainer($video["cover"], "wide", $video["title"]);
					echo "</a></li>";
				}
			} else {
				echo "<p>No Results</p>";
			}
		} else {
			echo "<p>No Results</p>";
		}
	?>
	</div>
	<div class="ctas">
		<a href="<?php echo $admin_root; ?>view-all/videos">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/eye.svg"); ?>
				<span>View All</span>
			</button>
		</a>
		<a href="<?php echo $admin_root; ?>new/video">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/add.svg"); ?>
				<span>New Video</span>
			</button>
		</a>
	</div>
</section>
<section class="card">
	<h2 class="title">
		<?php echo file_get_contents($htp_root . "src/icons/photo.svg"); ?>
		<span>Photosets</span>
	</h2>
	<div class="carousel">
	<?php
		$photosets_api = "listPhotosets?rows=3&order_by=id&order_dir=DESC";
		$photosets_res = xhrFetch($photosets_api);
		if (valExists("success", $photosets_res)) {
			$photosets = $photosets_res["data"];
			if ($photosets) {
				if (valExists("id", $photosets)) { // only one result was returned
					$photosets = [$photosets];
				}
				foreach ($photosets as $photoset) {
					echo "<li><a href='" . $admin_root . "edit/photoset/" . $photoset["id"] . "'>";
						echo mediaContainer($photoset["cover"], "wide", $photoset["title"]);
					echo "</a></li>";
				}
			} else {
				echo "<p>No Results</p>";
			}
		} else {
			echo "<p>No Results</p>";
		}
	?>
	</div>
	<div class="ctas">
		<a href="<?php echo $admin_root; ?>view-all/photosets">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/eye.svg"); ?>
				<span>View All</span>
			</button>
		</a>
		<a href="<?php echo $admin_root; ?>new/photoset">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/add.svg"); ?>
				<span>New Photoset</span>
			</button>
		</a>
	</div>
</section>
<section class="card">
	<h2 class="title">
		<?php echo file_get_contents($htp_root . "src/icons/cart.svg"); ?>
		<span>Store</span>
	</h2>
	<div class="carousel">
	<?php
		$store_api = "listStoreItems?rows=3&order_by=id&order_dir=DESC";
		$store_res = xhrFetch($store_api);
		if (valExists("success", $store_res)) {
			$store_items = $store_res["data"];
			if ($store_items) {
				if (valExists("id", $store_items)) { // only one result was returned
					$store_items = [$store_items];
				}
				foreach ($store_items as $store_item) {
					echo "<li><a href='" . $admin_root . "edit/store-item/" . $store_item["id"] . "'>";
						echo mediaContainer($store_item["cover"], "wide", $store_item["title"]);
					echo "</a></li>";
				}
			} else {
				echo "<p>No Results</p>";
			}
		} else {
			echo "<p>No Results</p>";
		}
	?>
	</div>
	<div class="ctas">
		<a href="<?php echo $admin_root; ?>view-all/store-items">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/eye.svg"); ?>
				<span>View All</span>
			</button>
		</a>
		<a href="<?php echo $admin_root; ?>new/store-item">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/add.svg"); ?>
				<span>New Store Item</span>
			</button>
		</a>
	</div>
</section>
<section class="card">
	<h2 class="title">
		<?php echo file_get_contents($htp_root . "src/icons/photos.svg"); ?>
		<span>Slideshow</span>
	</h2>
	<div class="carousel">
	<?php
		$slides_api = "listSlides?rows=3&order_by=id&order_dir=DESC";
		$slides_results = xhrFetch($slides_api);
		if (valExists("success", $slides_results)) {
			$slides_items = $slides_results["data"];
			if ($slides_items) {
				if (valExists("id", $slides_items)) { // only one result was returned
					$slides_items = [$slides_items];
				}
				foreach ($slides_items as $slide_item) {
					echo "<li><a href='" . $admin_root . "edit/slide/" . $slide_item["id"] . "'>";
						echo mediaContainer($slide_item["img"], "wide", $slide_item["text"]);
					echo "</a></li>";
				}
			} else {
				echo "<p>No Results</p>";
			}
		} else {
			echo "<p>No Results</p>";
		}
	?>
	</div>
	<div class="ctas">
		<a href="<?php echo $admin_root; ?>view-all/slides">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/eye.svg"); ?>
				<span>View All</span>
			</button>
		</a>
		<a href="<?php echo $admin_root; ?>new/slide">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/add.svg"); ?>
				<span>New Slide</span>
			</button>
		</a>
	</div>
</section>
<section class="card">
	<h2 class="title">
		<?php echo file_get_contents($htp_root . "src/icons/swarm.svg"); ?>
		<span>Media Manager</span>
	</h2>
	<ul class="carousel">
	<?php
		$media_api = "listMedia?rows=3&order_by=id&order_dir=DESC";
		$media_results = xhrFetch($media_api);
		if (valExists("success", $media_results)) {
			$media_items = $media_results["data"];
			if ($media_items) {
				if (valExists("id", $media_items)) { // only one result was returned
					$media_items = [$media_items];
				}
				foreach ($media_items as $media_item) {
					echo "<li><a href='" . $admin_root . "edit/media/" . $media_item["id"] . "'>";
						echo mediaContainer($media_item);
					echo "</a></li>";
				}
			}
		} else {
			echo "No Results";
		}
	?>
	</ul>
	<div class="ctas">
		<a href="<?php echo $admin_root; ?>view-all/media">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/eye.svg"); ?>
				<span>View All</span>
			</button>
		</a>
		<a href="<?php echo $admin_root; ?>new/media">
			<button class="btn cta sml">
				<?php echo file_get_contents($htp_root . "src/icons/upload.svg"); ?>
				<span>Upload More</span>
			</button>
		</a>
	</div>
</section>
<?php
require_once($php_root . "components/admin/footer.php");