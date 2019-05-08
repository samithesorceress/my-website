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
                                    echo "<img src='" . $htp_root . "uploads/" . $media_item["src"] . "." . $media_item["ext"] . "' height='144px'>";
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
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg"><a href="<?php echo $htp_root; ?>media-manager">View All</a></button>
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg"><a href="<?php echo $htp_root; ?>new/media">Upload More</a></button>
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
                if (valExists("success", $slides_results) && $slides_results["success"] == true) {
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
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg"><a href="<?php echo $htp_root; ?>slides-manager">View All</a></button>
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg"><a href="<?php echo $htp_root; ?>new/slide">New Slide</a></button>
            </div>
        </section>
        <section class="card">
            <h2 class="title">
                <img class="icon" src="<?php echo $htp_root; ?>src/icons/text.svg">
                <span>About</span>
			</h2>
			<div class="ctas">
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg"><a href="<?php echo $htp_root; ?>edit-about">Edit</a></button>
            </div>
        </section>
        <section class="card">
            <h2 class="title">
                <img class="icon" src="<?php echo $htp_root; ?>src/icons/movie.svg">
                <span>Videos</span>
            </h2>
            <div class="carousel">No Results</div>
            <div class="ctas">
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg"><a href="<?php echo $htp_root; ?>video-manager">View All</a></button>
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg"><a href="<?php echo $htp_root; ?>new/video">New Video</a></button>
            </div>
        </section>
        <section class="card">
            <h2 class="title">
                <img class="icon" src="<?php echo $htp_root; ?>src/icons/photo.svg">
                <span>Photosets</span>
            </h2>
            <div class="carousel">No Results</div>
            <div class="ctas">
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg"><a href="<?php echo $htp_root; ?>photoset-manager">View All</a></button>
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg"><a href="<?php echo $htp_root; ?>new/photoset">New Photoset</a></button>
            </div>
        </section>
        <section class="card">
            <h2 class="title">
                <img class="icon" src="<?php echo $htp_root; ?>src/icons/cart.svg">
                <span>Store</span>
            </h2>
            <div class="carousel">No Results</div>
            <div class="ctas">
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/eye.svg"><a href="<?php echo $htp_root; ?>store-manager">View All</a></button>
                <button class="btn cta"><img class="icon" src="<?php echo $htp_root; ?>src/icons/upload.svg"><a href="<?php echo $htp_root; ?>new/store-item">New Store Item</a></button>
            </div>
        </section>
    </article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");