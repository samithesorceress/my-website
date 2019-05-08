<?php
$errors = [];
// is attempting save
if (empty($_FILES) === false || empty($_REQUEST) === false) {


    switch($_REQUEST["item_type"]) {
        case "media": {
            try {
                $attempt_url = "new_media";
                // Undefined | Multiple Files | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (
                    !isset($_FILES['img']['error']) ||
                    is_array($_FILES['img']['error'])
                ) {
                    throw new RuntimeException('Invalid parameters.');
                }
            
                // Check $_FILES['upfile']['error'] value.
                switch ($_FILES['img']['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new RuntimeException('No file sent.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException('Exceeded filesize limit.');
                    default:
                        throw new RuntimeException('Unknown errors.');
                }
            
                // You should also check filesize here. 
                if ($_FILES['img']['size'] > 5000000000) { //5 gigs
                    throw new RuntimeException('Exceeded filesize limit.');
                }
            
                // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
                // Check MIME Type by yourself.
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (false === $ext = array_search(
                    $finfo->file($_FILES['img']['tmp_name']),
                    array(
                        "jpg" => "image/jpeg",
                        "png" => "image/png",
                        "gif" => "image/gif",
                        "mp4" => "video/mp4"
                    ),
                    true
                )) {
                    throw new RuntimeException('Invalid file format.');
                }
                $attempt_url .= "?type=";
                switch($ext) {
                    case "mp4":
                        $attempt_url .= "video";
                        break;
                    default:
                        $attempt_url .= "image";
                }
                
                // You should name it uniquely.
                // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
                // On this example, obtain safe unique name from its binary data.
                if (!move_uploaded_file(
                    $_FILES['img']['tmp_name'],
                    sprintf($php_root . 'uploads/%s.%s',
                        sha1($_FILES['img']['tmp_name']),
                        $ext
                    )
                )) {
                    throw new RuntimeException('Failed to move uploaded file.');
                }
                $attempt_url .= "&src=" . sha1($_FILES['img']['tmp_name']) . "." . $ext;
                if (isset($_REQUEST["title"])) {
                    $attempt_url .= "&title=" . urlencode($_REQUEST["title"]);
                }
                if (isset($_REQUEST["alt"])) {
                    $attempt_url .= "&alt=" . urlencode($_REQUEST["alt"]);
                }
                $attempt_url .= "&public=";
                if (isset($_REQUEST["public"])) {
                    if ($_REQUEST["public"] == true) {
                        $attempt_url .= "1";
                    } else {
                        $attempt_url .= "0";
                    }
                } else {
                    $attempt_url .= "0";
                }
                $attempt = xhrFetch($attempt_url);
                if (valExists("success", $attempt)) {
                    echo 'File is uploaded successfully.';
                } else {
                    echo "Error processing upload: ". $attempt["message"];
                }
            
            } catch (RuntimeException $e) {
            
                echo $e->getMessage();
            
            }
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

$item_type = str_replace("new/", "", $current_path);
?>
<main>
    <h1 class="title">New <?php echo ucSmart($item_type); ?></h1>
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
                case "media":
                    echo newFormField("img", "Image", "file");
                    echo newFormField("title", "Title");
                    echo newFormField("alt", "Alt", "textarea");
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