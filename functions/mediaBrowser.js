function openMediaBrowser(e) {
	var browser = document.createElement("div"),
		dismiss_zone = document.createElement("div"),
        trg = util.getTrg(e),
        id = util.makeID(6),
        size = util.getSize(trg);
        console.log(size);
	trg.parentNode.id = "media_target_" + id;
    browser.id = "media_browser_" + id;
    browser.className = "card media_browser";
    browser.innerHTML = "<ul class='media_library'></ul><footer class='actions_footer'><div class='field'><label for='new_media_upload'>Upload New:</label><input type='file' name='new_media_upload' id='new_media_upload' data-key='" + id + "' onchange='newMediaUpload()'></div></footer>";
	browser.style.top = size.top + "px";
	
	dismiss_zone.id = "dismiss_zone_" + id;
	dismiss_zone.className = "dismiss_zone";
	dismiss_zone.dataset.key = id;
	dismiss_zone.addEventListener("click", closeMediaBrowser);
	
	document.body.appendChild(dismiss_zone);
	document.body.appendChild(browser);
	
    util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/listMedia?order_by=id&order_dir=DESC", populateMediaBrowser, {"id":id, "attempt":1});
}
function closeMediaBrowser(e) {
	var trg = util.getTrg(e);
	var key = trg.dataset.key;
	var browser = document.getElementById("media_browser_" + key);
	var dismiss_zone = document.getElementById("dismiss_zone_" + key);
	document.body.removeChild(browser);
	document.body.removeChild(dismiss_zone);
}
function populateMediaBrowser(res, args) {
    var key = args.id,
        attempt = args.attempt,
        browser = document.getElementById("media_browser_" + key),
		media_library = util.getChildbyClassname(browser, "media_library")[0];
    console.log(res);

    if (res.success == true) {
        for(var i = 0; i < res.data.length; i += 1) {
            var media_data = res.data[i],
                container = document.createElement("li");
            
                container.id = "container_" + media_data.id;
                container.className = "media_container";
                container.dataset.key = key;
                if (media_data.shape) {
                    container.classList.add("shape_" + media_data.shape);
                }
                switch (media_data.type) {
                    case "video":
                        container.innerHTML += "<video src='" + htp_root + "uploads/" + media_data.src + "'/>";
                    break;
                    default:
                        container.innerHTML += "<img src='" + htp_root + "uploads/" + media_data.src + "." + media_data.ext + "' alt='" + media_data.alt + "' title='" + media_data.title + "'/>";
                }
                container.addEventListener("click", chooseMedia);
                media_library.appendChild(container);
                
        }
    } else {
        if (attempt < 3) {
            attempt += 1;
            util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/listMedia?rows=5&order_by=id&order_dir=DESC", populateMediaBrowser, {"id": key, "attempt": attempt});
            console.log("attempt #" + attempt + " failed, retrying...");
        } else {
            container.innerHTML += "<p>No Connection :(</p>";
        }
    }
}

function chooseMedia(e, id) {
    var trg = util.getTrg(e);
    var key = trg.dataset.key;
    var id = trg.id.replace("container_", "");
    var media_item = trg.childNodes[0];
    var field = document.getElementById("media_target_" + key);
    var field_children = field.childNodes;
    var media_container = util.getChildbyClassname(field, "media_container")[0];
    var cta = util.getChildbyClassname(field, "cta")[0];
	var browser = document.getElementById("media_browser_" + key);
	var dismiss_zone = document.getElementById("dismiss_zone_" + key);

    //save media id to hidden field
    for (var i = 0; i < field_children.length; i += 1) {
        var child = field_children[i];
        if (child.type == "hidden") {
            child.value = id;
        }
    }
    //render preview of chosen img
    media_container.innerHTML = "";
    media_container.appendChild(media_item);
    //update btn text
    cta.innerHTML = "<span>Replace</span>";
    //remove browser
	document.body.removeChild(browser);
	document.body.removeChild(dismiss_zone);

}

function newMediaUpload(e) {
    console.log("uploading new");
    var trg = util.getTrg(e);
    var file = trg.files[0];
    //var url = URL.createObjectURL(file);
    var key = trg.dataset.key;
    if (file && url) {
        // send data to backend
        util.api.upload(file, processNewMedia, key);
    } else {
        //handle error
    } 
}
function processNewMedia(res, key) {
	console.log("callback fired");
	console.log(res);

	var browser = document.getElementById("media_browser_" + key),
		library = util.getChildbyClassname(browser, "media_library")[0],
		footer = util.getChildbyClassname(browser, "actions_footer")[0],
		closeBtn = document.createElement("button");
		saveBtn = document.createElement("button");
	closeBtn.id = "exit_new_media_" + key;
	saveBtn.id = "save_new_media_" + key;
	closeBtn.className = "btn cta";
	saveBtn.className = "btn cta";
	closeBtn.dataset.key = key;
	saveBtn.dataset.key = key;
	
	footer.innerHTML = "";

	if (res.success == true) {		
		library.innerHTML = "<h3>New Media</h3><div class='field'><img class='preview_new_img' src='http://127.0.0.1/sami-the-sorceress/uploads/" + res.data.src + "." + res.data.ext + "'></div><div class='field'><label for='new_media_title_" + key + "'>Title</label><input type='text' name='new_media_title_" + key + "' id='new_media_title_" + key + "'></div><div class='field'><label for='new_media_alt_" + key + "'>Alt</label><textarea name='new_media_alt_" + key + "' id='new_media_alt_" + key + "'></textarea></div>";

		closeBtn.innerHTML = "<span>Cancel</span>";
		saveBtn.dataset.key = key;
		closeBtn.addEventListener("click", closeMediaBrowser);

		saveBtn.innerHTML = "<span>Save</span>";
		saveBtn.dataset.key = key;
		saveBtn.dataset.src = res.data.src;
		saveBtn.addEventListener("click", saveMediaDetails);
		
		footer.appendChild(closeBtn);
		footer.appendChild(saveBtn);

	} else {
		library.innerHTML = "<h3>Error Uploading</h3><p>Something went wrong with that upload. Check the internet connection and try again..</p>";

		closeBtn.innerHTML = "<span>Close</span>";
		closeBtn.addEventListener("click", closeMediaBrowser);
		
		footer.appendChild(closeBtn);
	}
}

function saveMediaDetails(e) {
	var trg = util.getTrg(e);
	var key = trg.dataset.key;
	var src = trg.dataset.src
	var browser = document.getElementById("media_browser_" + key);
	var library = util.getChildbyClassname(browser, "media_library")[0];
	var footer = util.getChildbyClassname(browser, "actions_footer")[0];
	var titleField = document.getElementById("new_media_title_" + key);
	var altField = document.getElementById("new_media_alt_" + key);

	library.innerHTML = "";
	footer.innerHTML = "";

	util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/updateMediaInfo?src=" + src + "&title=" + titleField.value + "&alt=" + altField.value, finishNewMedia, key);
}

function finishNewMedia(res, args) {
	console.log("callback fired");
	console.log(res);
	if (res.success == true) {
		var id = res.data.id;
		var key = args;
		var field = document.getElementById("media_target_" + key);
		var field_children = field.childNodes;
		var media_container = util.getChildbyClassname(field, "media_container")[0];
		var cta = util.getChildbyClassname(field, "cta")[0];
		var browser = document.getElementById("media_browser_" + key);
		var dismiss_zone = document.getElementById("dismiss_zone_" + key);

		//save media id to hidden field
		for (var i = 0; i < field_children.length; i += 1) {
			var child = field_children[i];
			if (child.type == "hidden") {
				child.value = id;
			}
		}

		//render preview of chosen img
		media_container.innerHTML = "<img src='http://127.0.0.1/sami-the-sorceress/uploads/" + res.data.src + "." + res.data.ext + "'/>";
		//update btn text
		cta.innerHTML = "<span>Replace</span>";
		//remove browser
		document.body.removeChild(browser);
		document.body.removeChild(dismiss_zone);

	} else {
		//error
	}
}