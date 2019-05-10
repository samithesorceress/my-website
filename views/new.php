<?php
$errors = [];
// is attempting save
if (empty($_FILES) === false || empty($_REQUEST) === false) {


    switch($_REQUEST["item_type"]) {
        case "media": {
            
            break;
		}
		case "slide": {
			if (empty($_REQUEST["img"]) === false) {
				if (empty($_REQUEST["text"]) === false) {
					if (empty($_REQUEST["url"]) === false) {
						$attempt_url = "newSlide?img=" . $_REQUEST["img"] . "&text=" . urlencode($_REQUEST["text"]) . "&url=" . urlencode($_REQUEST["url"]) . "&public=" . $_REQUEST["public"];
						$attempt = xhrFetch($attempt_url);
						if (valExists("success", $attempt)) {
							header("Location: http://127.0.0.1/sami-the-sorceress");
							die();
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
			break;
		}
        case "video": {
            //////
            break;
        }
        case "photoset": {
            //////
            break;
        }
        case "store-item": {
            //////
            break;
        }
    }
}













//BEGIN OUTPUT

require_once($php_root . "components/admin/header.php");
?>
<main>
    <h1 class="title"><? echo $document_title; ?></h1>
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
            echo newFormField("item_type", "", "hidden", $item_type);
            switch($item_type) {
                    break;
                case "slide":
                    echo newFormField("img", "Image", "media_browser", 1);
                    echo newFormField("text", "Text");
                    echo newFormField("url", "Url");
                    break;
                case "video":
                case "photoset":
                case "store-item": 
                    echo newFormField("title", "Title");
                    echo newFormField("desc", "Description", "textarea");
                    echo newFormField("tags", "Tags", "textarea");
                    echo newFormField("cover", "Cover", "file");
                    if ($item_type == "video") {
                        echo newFormField("preview", "Preview Video", "file");
                    } else {
                        echo newFormField("previews", "Preview(s)", "files");
                    }
                    echo newFormField("price", "Price");
                    echo "<div class='card'><h3 class='title'>Purchase Links</h3><div class='two_columns'>";
                        echo newFormField("cta_name_1", "Site Name #1");
                        echo newFormField("cta_url_1", "Site Url #1");
                        echo newFormField("cta_name_2", "Site Name #2");
                        echo newFormField("cta_url_2", "Site Url #2");
                        echo newFormField("cta_name_3", "Site Name #3");
                        echo newFormField("cta_url_3", "Site Url #3");
                        echo newFormField("cta_name_4", "Site Name #4");
                        echo newFormField("cta_url_4", "Site Url #4");
                    echo"</div></div>";
                    echo newFormField("date", "Date", "date");
                    break;
            }
            //defaults, they all need these
            echo newFormField("public", "Public", "checkbox");
            echo newFormField("save", "Save", "submit", "Save");
        ?>
        </form>
    </article>
</main>
<?php
require_once($php_root . "components/admin/footer.php");