var mediaBrowser = {
	open: function (e) {
		util.events.cancel(e);
		var trg = util.getTrg(e),
			key = util.makeID(6),
			wrapper = document.createElement("div"),
			dialog = document.createElement("div"),
			contents = document.createElement("div"),
			footer = document.createElement("footer"),
			field = document.createElement("div"),
			newBtn = document.createElement("input"),
			api_endpoint = "list/media",
			api_params = [];

		trg.parentNode.id = "media_target_" + key;

		wrapper.id = "dialog_wrapper_" + key;
		wrapper.className = "dialog_wrapper";
		wrapper.dataset.key = key;
	//	wrapper.addEventListener("click", mediaBrowser.close);

		dialog.id = "dialog_" + key;
		dialog.className = "card media_browser_dialog dialog";
		
		contents.id = "media_browser_contents_" + key;
		contents.className = "media_browser_contents grid";

		field.className = "field";
		field.innerHTML = "<label for='new_media_upload'>Upload New:</label>";

		newBtn.id = "new_media_upload";
		newBtn.name = "new_media_upload";
		newBtn.type = "file";
		newBtn.dataset.key = key;
		newBtn.addEventListener("change", mediaBrowser.uploadNew);
		
		api_params["order_by"] = "id";
		api_params["order_dir"] = "DESC";
		api_params["rows"] = 5;
		util.xhrFetch(api_endpoint, api_params, mediaBrowser.populate, {"id": key, "attempt": 1});

		field.appendChild(newBtn);
		footer.appendChild(field);
		dialog.appendChild(contents);
		dialog.appendChild(footer);
		wrapper.appendChild(dialog)
		document.body.appendChild(wrapper);

		setTimeout(function (w, d) {
			w.classList.add("visible");
			dialog.classList.add("visible");
		}, 10, wrapper, dialog);
	},
	close: function (e) {
		var trg = util.getTrg(e),
			key, dialog, wrapper;

		if (!util.isElem(trg)) {
			key = e;
		} else {
			key = trg.dataset.key;
		}
		dialog = document.getElementById("dialog_" + key);
		wrapper = document.getElementById("dialog_wrapper_" + key);
		
		dialog.classList.remove("visible");
		wrapper.classList.remove("visible");

		setTimeout(function (w) {
			document.body.removeChild(w);
		}, 600, wrapper);
	},
	populate: function (res, args) {
		var key = args.id,
			library = document.getElementById("media_browser_contents_" + key),
			grid = document.createElement("ul");
		console.log(res);

		grid.className = "grid_contents";

		if (res.success == true) {
			for(var i = 0; i < res.data.length; i += 1) {
				var media_data = res.data[i],
					grid_item = document.createElement("li"),
					media_container = document.createElement("div"),
					media_item = "";

					grid_item.className = "grid_item square_grid_item";

					media_container.id = "container_" + media_data.id;
					media_container.className = "media_container";
					media_container.dataset.key = key;

					if (media_data.shape) {
						media_container.classList.add("shape_" + media_data.shape);
					}
					switch (media_data.type) {
						case "video":
							media_item = "<video src='" + htp_root + "uploads/" + media_data.src + "'/>";
						break;
						default:
							media_item = "<img src='" + htp_root + "uploads/" + media_data.src + "." + media_data.ext + "' alt='" + media_data.alt + "' title='" + media_data.title + "' data-shape='"
								if (media_data.ratio > 1) {
									media_item += "wide";
								} else {
									media_item += "tall";
								}
							media_item += "'/>";
					}
					media_container.innerHTML += "<div class='media_contents'>" + media_item + "</div>";
					
					grid_item.appendChild(media_container);
					grid.appendChild(grid_item);
			}
			for (var i = 0; i < 12; i += 1) {
				grid.innerHTML += "<li class='hidden_flex_item grid_item square_grid_item'></li>";
			}
			library.appendChild(grid);
			grid = library.childNodes[0];
			for (var i = 0; i < grid.childNodes.length; i += 1) {
				var item = grid.childNodes[i];
				if (!item.classList.contains("hidden_flex_item")) {
					item.childNodes[0].addEventListener("click", mediaBrowser.choose);
				}

			}
		}
	},
	choose: function (e, id) {
		var trg = util.getTrg(e);
		var key = trg.dataset.key,
			field, media_container, hidden_input, cta;
		var id = trg.id.replace("container_", "");
		var media_item = trg.childNodes[0];
		
		// get parent
		field = document.getElementById("media_target_" + key);
		util.addStyle(field, "background", "red");
		// transfer id
		hidden_input = util.getChildrenbyClassname(field, "media_id")[0];
		hidden_input.value = id;

		// render chosen img
		media_container = util.getChildrenbyClassname(field, "media_browser_field_contents")[0];
		media_container = media_container.childNodes[0];
		media_container.childNodes[0].appendChild(media_item);

		// update btn txt
		cta = util.getChildrenbyClassname(field, "cta")[0];
		cta.innerHTML = util.icon("upload") + "<span>Replace</span>";
		
		// finish & remove browser
		mediaBrowser.close()
	},
	uploadNew: function (e) {
		console.log("uploading new");
		var trg = util.getTrg(e);
		var file = trg.files[0];
		var url = URL.createObjectURL(file);
		var key = trg.dataset.key;
		if (file && url) {
			// send data to backend
			util.api.upload(file, mediaBrowser.processNew, key);
		} else {
			//handle error
		} 
	},
	processNew: function (res, key) {
		console.log("callback fired");
		console.log(res);

		var browser = document.getElementById("media_browser_" + key),
			library = util.getChildrenbyClassname(browser, "media_library")[0],
			footer = util.getChildrenbyClassname(browser, "actions_footer")[0],
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
			library.innerHTML = "<h3>New Media</h3><div class='field'><img class='preview_new_img' src='http://127.0.0.1/sami-the-sorceress/uploads/" + res.data.src + "." + res.data.ext + "' data-shape='";
			if (res.data.ratio > 1) {
				library.innerHTML += "wide";
			} else {
				library.innerHTML += "tall";
			}
			library.innerHTML += "' loading='lazy'></div><div class='field'><label for='new_media_title_" + key + "'>Title</label><input type='text' name='new_media_title_" + key + "' id='new_media_title_" + key + "'></div><div class='field'><label for='new_media_alt_" + key + "'>Alt</label><textarea name='new_media_alt_" + key + "' id='new_media_alt_" + key + "'></textarea></div>";

			closeBtn.innerHTML = "<span>Cancel</span>";
			saveBtn.dataset.key = key;
			closeBtn.addEventListener("click", mediaBrowser.close);

			saveBtn.innerHTML = "<span>Save</span>";
			saveBtn.dataset.key = key;
			saveBtn.dataset.src = res.data.src;
			saveBtn.addEventListener("click", mediaBrowser.save);
			
			footer.appendChild(closeBtn);
			footer.appendChild(saveBtn);

		} else {
			library.innerHTML = "<h3>Error Uploading</h3><p>Something went wrong with that upload. Check the internet connection and try again..</p>";

			closeBtn.innerHTML = "<span>Close</span>";
			closeBtn.addEventListener("click", mediaBrowser.close);
			
			footer.appendChild(closeBtn);
		}
	},
	uploadReplacement: function (e) {
		console.log("uploading replacement");
		var trg = util.getTrg(e);
		var file = trg.files[0];
		var url = URL.createObjectURL(file);
		var key = trg.dataset.key;
		var field_children = document.getElementById("media_target_" + key).children;
		var id = field_children[field_children.length - 1].id.replace("media_file_", "").replace("_browser", "");
		console.log(id);
		if (file && url) {
			// send data to backend
			util.api.upload(file, mediaBrowser.updateContainer, key, id);
		} else {
			//handle error
		} 
	},
	processReplacement: function (res, key) {
		console.log("processing replacement");
		console.log(res);
	}, 
	saveDetails: function (e) {
		var trg = util.getTrg(e);
		var key = trg.dataset.key;
		var src = trg.dataset.src
		var browser = document.getElementById("media_browser_" + key);
		var library = util.getChildrenbyClassname(browser, "media_library")[0];
		var footer = util.getChildrenbyClassname(browser, "actions_footer")[0];
		var titleField = document.getElementById("new_media_title_" + key);
		var altField = document.getElementById("new_media_alt_" + key);

		library.innerHTML = "";
		footer.innerHTML = "";

		util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/updateMediaInfo?src=" + src + "&title=" + titleField.value + "&alt=" + altField.value, finishNewMedia, key);
	},
	updateContainer (res, args) {
		console.log("updaing container");
		console.log(res);
		if (res.success == true) {
			var data = res.data;
			var id = data.id;
			var key = args;
			var field = document.getElementById("media_target_" + key);
			var field_children = field.childNodes;
			var media_container = util.getChildrenbyClassname(field, "media_container")[0];
			var cta = util.getChildrenbyClassname(field, "cta")[0];
			var browser = document.getElementById("media_browser_" + key);
			var dismiss_zone = document.getElementById("dismiss_zone_" + key);
			var img = document.createElement("img");

			//save media id to hidden field
			for (var i = 0; i < field_children.length; i += 1) {
				var child = field_children[i];
				if (child.type == "hidden") {
					child.value = id;
				}
			}

			//render preview of chosen img
			media_container.innerHTML = "";
			img.src = "http://127.0.0.1/sami-the-sorceress/uploads/" + data.src + "." + data.ext;
			if (data.ratio > 1) {
				img.dataset.shape = "wide";
			} else {
				img.dataset.shape = "tall";
			}
			img.setAttribute("loading", "lazy");
			media_container.appendChild(img);
			//update btn text
			cta.innerHTML = "<span>Replace</span>";
			//remove browser
			mediaBrowser.close(key);
		} else {
			//error
		}
	}
},
media_browser_btns = document.getElementsByClassName("media_browser_btn");

for (var i = 0; i < media_browser_btns.length; i += 1) {
	var btn = media_browser_btns[i];
	btn.addEventListener("click", mediaBrowser.open);
}