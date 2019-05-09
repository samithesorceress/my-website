<?php
require_once($php_root . "components/admin/header.php");
?>
<main>
	<h1 class="title">Dashboard</h1>
	<article>
		<section class="card">
			<h2 class="title">
				<img class="icon" src="<?php echo $htp_root; ?>src/icons/swarm.svg">
				<span>Media Manager</span>
			</h2>
			<ul class="carousel">
			<?php
				$media_api = "listMedia?rows=5&order_by=id&order_dir=DESC";
				$media_results = xhrFetch($media_api);
				if (valExists("success", $media_results)) {
					$media_items = $media_results["data"];
					if ($media_items) {
						foreach ($media_items as $media_item) {
							echo "<li class='media_container'>";
							switch ($media_item["type"]) {
								case "image": {
									echo "<img src='" . $htp_root . "uploads/" . $media_item["src"] . "." . $media_item["ext"] . "' data-shape='";
									if ($media_item["ratio"] > 1) {
										echo "wide";
									} else {
										echo "tall";
									}
									echo "'/>";
									break;
								}
								case "video": {

									break;
								}
							}
							echo "</li>";
						}
					}
				} else {
					echo "No Results";
				}
			?>
			</ul>
			<div class="ctas">
				<a href="<?php echo $htp_root; ?>media-manager">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg">
						<span>View All</span>
					</button>
				</a>
				<a href="<?php echo $htp_root; ?>new/media">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg">
						<span>Upload More</span>
					</button>
				</a>
			</div>
		</section>
		<section class="card">
			<h2 class="title">
				<img class="icon" src="<?php echo $htp_root; ?>src/icons/photos.svg">
				<span>Slideshow</span>
			</h2>
			<div class="carousel">
			<?php
				$slides_api = "listSlides?rows=5&order_by=id&order_dir=DESC";
				$slides_results = xhrFetch($slides_api);
				if (valExists("success", $slides_results)) {
					$slides_items = $slides_results["data"];
					if ($slides_items) {
						foreach ($slides_items as $slide_item) {
							echo "<li class='media_container'>";
								echo "<p>" . $slide_item["text"] .  "</p>";
							echo "</li>";
						}
					} else {
						echo "No Results";
					}
				} else {
					echo "No Results";
				}
			?>
			</div>
			<div class="ctas">
				<a href="<?php echo $htp_root; ?>slides-manager">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg">
						<span>View All</span>
					</button>
				</a>
				<a href="<?php echo $htp_root; ?>new/slide">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg">
						<span>New Slide</span>
					</button>
				</a>
			</div>
		</section>
		<section class="card">
			<h2 class="title">
				<img class="icon" src="<?php echo $htp_root; ?>src/icons/text.svg">
				<span>About</span>
			</h2>
			<?php
			$about_api = "listAbout";
			$about_results = xhrFetch($about_api);
			$profile_photo = false;
			$bio = false;
			$links = false;
			if (valExists("success", $about_results)) {
				$profile_photo = $about_results["data"]["profile"];
				$bio = $about_results["data"]["bio"];
				$links = $about_results["data"]["links"];
				if ($links) {
					$links = json_decode($links, true);
				}
			}
			
			echo "<div class='profile_photo'>";
				if ($profile_photo) {
					$profile_api = "listMedia?id=" . $profile_photo;
					$profile_data = false;
					$profile_res = xhrFetch($profile_api);
					if (valExists("success", $profile_res)) {
						$profile_data = $profile_res["data"];
					}
					if ($profile_data) {
						echo "<img src='" . $htp_root . "uploads/" . $profile_data["src"] . "." . $profile_data["ext"] . "' alt='" . $profile_data["alt"] . "' title='" . $profile_data["title"] . "' data-shape='";
						if ($profile_data["ratio"] > 1) {
							echo "wide";
						} else {
							echo "tall";
						}
						echo"'/>";
					}
				}
			echo"</div>";
			echo "<p><strong>Bio: </strong>" . $bio . "</p>";
			echo "<p><strong>Links: </strong></p>";
			if ($links) {
				echo "<ul>";
				foreach($links as $link) {
					echo "<li><a href='" . urldecode($link["url"]) . "'>" . $link["title"] . "</a></li>";
				}
				echo "</ul>";
			}
			?>
			<div class="ctas">
				<a href="<?php echo $htp_root; ?>edit-about">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/edit.svg">
						<span>Edit</span>
					</button>
				</a>
			</div>
		</section>
		<section class="card">
			<h2 class="title">
				<img class="icon" src="<?php echo $htp_root; ?>src/icons/movie.svg">
				<span>Videos</span>
			</h2>
			<div class="carousel">No Results</div>
			<div class="ctas">
				<a href="<?php echo $htp_root; ?>video-manager">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg">
						<span>View All</span>
					</button>
				</a>
				<a href="<?php echo $htp_root; ?>new/video">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg">
						<span>New Video</span>
					</button>
				</a>
			</div>
		</section>
		<section class="card">
			<h2 class="title">
				<img class="icon" src="<?php echo $htp_root; ?>src/icons/photo.svg">
				<span>Photosets</span>
			</h2>
			<div class="carousel">No Results</div>
			<div class="ctas">
				<a href="<?php echo $htp_root; ?>photoset-manager">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg">
						<span>View All</span>
					</button>
				</a>
				<a href="<?php echo $htp_root; ?>new/photoset">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg">
						<span>New Photoset</span>
					</button>
				</a>
			</div>
		</section>
		<section class="card">
			<h2 class="title">
				<img class="icon" src="<?php echo $htp_root; ?>src/icons/cart.svg">
				<span>Store</span>
			</h2>
			<div class="carousel">No Results</div>
			<div class="ctas">
				<a href="<?php echo $htp_root; ?>store-manager">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg">
						<span>View All</span>
					</button>
				</a>
				<a href="<?php echo $htp_root; ?>new/store-item">
					<button class="btn cta">
						<img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg">
						<span>New Store Item</span>
					</button>
				</a>
			</div>
		</section>
	</article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");