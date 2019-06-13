<?php
	$api_endpoint = "list/slides";
	$api_params = [
		"public" => 1
	];
	$api_res = xhrFetch($api_endpoint, $api_params);
//	echo "<pre>";
//	print_r($api_res);
//	echo "</pre>";
	if (valExists("success", $api_res)) {
		$slides = $api_res["data"];
		if (valExists("id", $slides)) {
			$slides = [$slides];
		}
		
		
		echo "<div id='slideshow'>";
			echo "<ul class='slide_nav'>";
				
			echo "</ul>";
			echo "<ul class='slides_container'>";
			foreach($slides as $slide) {
				echo "<li class='slide'>";
				$api_endpoint = "list/media";
				$api_params = [
					"id" => $slide["cover"]
				];
				$api_res = xhrFetch($api_endpoint, $api_params);
				if (valExists("success", $api_res)) {
					$cover = $api_res["data"];
					switch ($cover["type"]) {
						case "image":
							echo "<img src='" . $htp_root . "uploads/" . $cover["src"] . "." . $cover["ext"] . "'";
							if (valExists("sizes", $cover)) {
								$sizes = intval($cover["sizes"]);
								$srcset = "srcset='";
								for ($i = 0; $i < $sizes; $i += 1) {
									$size = ($i + 1) * 200;
									$srcset .= $htp_root . "uploads/" . $cover["src"] . "_" . $size . "w." . $cover["ext"] . " " . $size . "w, ";
								}
								$srcset = rtrim($srcset, ", ") . "'";
								echo $srcset;
							}
							break;
						case "video":
							echo "<video src='" . $htp_root . "uploads/" . $cover["src"] . "." . $cover["ext"] . "'";
							break;
					}
					echo " data-shape='";
					$ratio = 2.16;
					if ($cover["ratio"] > $ratio) {
						echo "wide";
					} else {
						echo "tall";
					}
					echo "' loading='lazy' />";
				}
				echo "</li>";
			}
			echo "</ul>";
			echo "<footer class='slideshow_footer'>";
			echo "</footer>";
		echo "</div>";
	}
?>