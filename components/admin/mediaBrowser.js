var mediaBrowser = {
	open: function (e) {
		util.events.cancel(e);
		var trg = util.getTrg(e),
			key = "mb" + util.makeID(4),
			wrapper = document.createElement("div"),
			dismiss_zone = document.createElement("div"),
			dialog = document.createElement("div"),
			contents = document.createElement("div"),
			footer = document.createElement("footer"),
			field = document.createElement("div"),
			upload_new_btn = document.createElement("input"),
			load_more_container = document.createElement("div"),
			load_more_btn = document.createElement("button"),
			api_endpoint = "list/media",
			api_params = [];

		// give form field the id for later use
		trg.parentNode.id = "media_target_" + key;

		// make container/shadow
		wrapper.id = "dialog_wrapper_" + key;
		wrapper.className = "dialog_wrapper";
		wrapper.dataset.key = key;
		
		// click away elem
		dismiss_zone.className = "dismiss_zone";
		dismiss_zone.dataset.key = key;

		// dialog box
		dialog.id = "dialog_" + key;
		dialog.className = "card media_browser_dialog dialog";
		
		// grid container
		contents.id = "media_browser_contents_" + key;
		contents.className = "media_browser_contents grid";

		// load more
		load_more_container.className = "load_more_container";
		load_more_btn.id = "load_more_media_btn_" + key;
		load_more_btn.className = "btn cta sml";
		load_more_btn.dataset.key = key;
		load_more_btn.dataset.offset = 0;
		load_more_btn.innerHTML = util.icon("download") + "<span>Load More</span>";

		// footer
		footer.className = "actions_footer";

		// new upload field
		field.className = "field";
		field.innerHTML = "<label for='new_media_upload'>Upload New:</label>";
		upload_new_btn.id = "new_media_upload_" + key;
		upload_new_btn.name = "new_media_upload";
		upload_new_btn.type = "file";
		upload_new_btn.dataset.key = key;
		
		// api setup
		api_params["order_by"] = "id";
		api_params["order_dir"] = "DESC";
		api_params["rows"] = 5;
		util.xhrFetch(api_endpoint, api_params, mediaBrowser.populate, {"id": key, "attempt": 1});

		// build
		field.appendChild(upload_new_btn);
		footer.appendChild(field);
		load_more_container.appendChild(load_more_btn);
		contents.appendChild(load_more_container);
		dialog.appendChild(contents);
		dialog.appendChild(footer);
		wrapper.appendChild(dismiss_zone);
		wrapper.appendChild(dialog);
		document.body.appendChild(wrapper);

		//add listeners
		load_more_btn.addEventListener("click", mediaBrowser.loadMore);
		upload_new_btn.addEventListener("change", mediaBrowser.uploadNew);
		dismiss_zone.addEventListener("click", mediaBrowser.close);

		// display
		setTimeout(function (w, d) {
			w.classList.add("visible");
			d.classList.add("visible");
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
	loadMore: function (e) {
		var trg = util.getTrg(e),
			api_endpoint = "list/media",
			api_params = [];

		api_params["order_by"] = "id";
		api_params["order_dir"] = "DESC";
		api_params["rows"] = 5;
		api_params["offset"] = trg.dataset.offset;

		util.xhrFetch(api_endpoint, api_params, mediaBrowser.populate, {"id": trg.dataset.key, "attempt": 1});
	},
	populate: function (res, args) {
		var key = args.id,
			contents = document.getElementById("media_browser_contents_" + key),
			grid = document.createElement("ul"),
			existing_grid = util.getChildrenbyClassname(contents, "grid_contents"),
			hidden_items,
			load_more_btn = document.getElementById("load_more_media_btn_" + key),
			offset = 0;
		console.log(res);

		// if grid exists, link it with var. 
		if (existing_grid.length) {
			grid = existing_grid[0];
			// if it does exist, we also need to remove the hidden elements first
			hidden_items = util.getChildrenbyClassname(grid, "hidden_flex_item");
			for (var i = 0; i < hidden_items.length; i += 1) {
				grid.removeChild(hidden_items[i]);
			}

		// if grid does not exist, create it
		} else {
			grid.className = "grid_contents";
			contents.prepend(grid);
			grid = contents.childNodes[0];
		}
		
		// now populate
		if (res.success == true) {
			for(var i = 0; i < res.data.length; i += 1) {
				var media_data = res.data[i],
					grid_item = document.createElement("li"),
					media_container = document.createElement("div"),
					media_item = "";

					//build objects
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
							media_item = "<img src='" + htp_root + "uploads/" + media_data.src + "." + media_data.ext + "' alt='" + media_data.alt + "' title='" + media_data.title + "' data-ratio='" + media_data.ratio + "' data-shape='"
								if (media_data.ratio > 1) {
									media_item += "wide";
								} else {
									media_item += "tall";
								}
							media_item += "'/>";
					}
					media_container.innerHTML += "<div class='media_contents'>" + media_item + "</div>";
					
					// add to grid
					grid_item.appendChild(media_container);
					grid.appendChild(grid_item);
			}

			// add hidden flex items
			for (var i = 0; i < 12; i += 1) {
				grid.innerHTML += "<li class='hidden_flex_item grid_item square_grid_item'></li>";
			}

			// add event listeners
			for (var i = 0; i < grid.childNodes.length; i += 1) {
				var item = grid.childNodes[i];
				// not for hidden items tho
				if (!item.classList.contains("hidden_flex_item")) {
					item.childNodes[0].addEventListener("click", mediaBrowser.choose);
				}
			}

			// update load more btn 
			offset = parseInt(load_more_btn.dataset.offset) + 15;
			
			if (res.total > offset) {
				load_more_btn.dataset.offset = offset;
			} else {
				contents.removeChild(load_more_btn.parentNode);
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

		// transfer id
		hidden_input = util.getChildrenbyClassname(field, "media_id")[0];
		hidden_input.value = id;

		// render chosen img
		media_container = util.getChildrenbyClassname(field, "media_browser_field_contents")[0];
		media_container = media_container.childNodes[0];
		media_container.innerHTML = "";
		if (media_item.childNodes[0].dataset.ratio < 1.645) {
			media_item.childNodes[0].dataset.shape = "tall";
		} else {
			media_item.childNodes[0].dataset.shape = "wide";
		}
		media_container.appendChild(media_item);

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
		var contents = document.getElementById("media_browser_contents_" + key);
		
		// clear browser and add spinner
		contents.innerHTML = "";
		util.spinner.add(contents);

		// send data to backend
		if (file && url) {
			util.api.upload(file, mediaBrowser.processNew, key);
		} else {
			mediaBrowser.processNew({"success":false}, key);
		} 
	},
	processNew: function (res, key) {
		console.log("processing new upload", res);

		var dialog = document.getElementById("dialog_" + key),
			contents = document.getElementById("media_browser_contents_" + key),
			new_preview_container = document.createElement("div"),
			new_preview_contents = document.createElement("div"),
			new_preview_img = document.createElement("img"),
			ctas = document.createElement("div"),
			close_btn = document.createElement("button"),
			save_btn = document.createElement("button");
		var footer = util.getChildrenbyClassname(dialog, "actions_footer")[0];

		// init + empty
		contents.classList.add("processing_new");
		contents.classList.remove("grid");
		contents.innerHTML = "<h3>New Media</h3>";
		footer.innerHTML = "";

		// footer btns
		ctas.classList = "ctas";
		close_btn.id = "exit_new_media_" + key;
		save_btn.id = "save_new_media_" + key;
		close_btn.className = "btn cta pink";
		save_btn.className = "btn cta";
		close_btn.dataset.key = key;
		save_btn.dataset.key = key;

		if (res.success == true) {
			// prepare containers
			new_preview_container.className = "media_container hd_container preview_new_img";
			new_preview_container.dataset.src = res.data.src;
			new_preview_contents.className = "media_contents";
			
			// gen preview img
			new_preview_img.src = "http://127.0.0.1/sami-the-sorceress/uploads/" + res.data.src + "." + res.data.ext;
			new_preview_img.dataset.ratio = res.data.ratio;
			if (res.data.ratio < 1.645) {
				new_preview_img.dataset.shape = "tall";
			} else {
				new_preview_img.dataset.shape = "wide";
			}

			// combine & render
			new_preview_contents.appendChild(new_preview_img);
			new_preview_container.appendChild(new_preview_contents);
			contents.appendChild(new_preview_container);
			
			// add fields
			contents.innerHTML += "<div class='field'><label for='new_media_title_" + key + "'>Title</label><input id='new_media_title_" + key + "' class='dark' type='text' name='new_media_title_" + key + "' ></div><div class='field'><label for='new_media_alt_" + key + "'>Alt</label><textarea id='new_media_alt_" + key + "' class='dark' name='new_media_alt_" + key + "' ></textarea></div>";

			// add ctas
			close_btn.innerHTML = util.icon("delete") + "<span>Cancel</span>";
			close_btn.dataset.key = key;
			close_btn.dataset.src = res.data.src;
			close_btn.addEventListener("click", mediaBrowser.cancelNew);
			save_btn.innerHTML = util.icon("save") + "<span>Save</span>";
			save_btn.dataset.key = key;
			save_btn.dataset.src = res.data.src;
			save_btn.addEventListener("click", mediaBrowser.saveNew);
			ctas.appendChild(close_btn);
			ctas.appendChild(save_btn);

		} else {
			// failed - display error
			contents.innerHTML = "<h3>Error Uploading</h3><p>Something went wrong with that one. Check the internet connection and try again..</p><p>Accepted file types: .jpg, .jpeg, .png, .gif, .mp4</p>";

			// add exit btn
			close_btn.innerHTML = util.icon("exit") + "<span>Close</span>";
			close_btn.addEventListener("click", mediaBrowser.close);
			ctas.appendChild(close_btn);
		}
		footer.appendChild(ctas);
	},
	saveNew: function (e) {
		var trg = util.getTrg(e);
		var key = trg.dataset.key;
		console.log("saving new: " + key);
		var contents = document.getElementById("media_browser_contents_" + key);
		var media_item = util.getChildrenbyClassname(contents, "preview_new_img")[0];
		var src = media_item.dataset.src;
		var title_field = document.getElementById("new_media_title_" + key);
		var alt_field = document.getElementById("new_media_alt_" + key);
		var api_endpoint = "update/media";
		var api_params = [];
		
		// prepare data for update
		api_params["src"] = src;
		api_params["title"] = title_field.value || "";
		api_params["alt"] = alt_field.value || "";

		// send it
		util.xhrFetch(api_endpoint, api_params, mediaBrowser.finishNew, key);
	},
	finishNew: function (res, key) {
		console.log("finishing new", res);
		
		// get browser items
		var dialog = document.getElementById("dialog_" + key);
		var contents = document.getElementById("media_browser_contents_" + key);
		var media_item = util.getChildrenbyClassname(contents, "preview_new_img")[0].childNodes[0];
		var footer = util.getChildrenbyClassname(dialog, "actions_footer")[0],
			ctas = document.createElement("div"),
			close_btn = document.createElement("button");

		// get field items
		var field = document.getElementById("media_target_" + key);
		var hidden_input = util.getChildrenbyClassname(field, "media_id")[0];
			media_container = util.getChildrenbyClassname(field, "media_browser_field_contents")[0].childNodes[0],
			cta = util.getChildrenbyClassname(field, "cta")[0];
		
		if (res.success == true) {
			// transfer id
			hidden_input.value = res.data.id;

			// render chosen img
			media_container.innerHTML = "";
			media_container.appendChild(media_item);

			// update btn txt
			cta.innerHTML = util.icon("upload") + "<span>Replace</span>";

			// finish & remove browser
			mediaBrowser.close(key);
		} else {
			// handle error
			contents.innerHTML = "<h3>Error Saving</h3><p>Something went wrong, sorry.. The image likely still exists though. Close the browser and try to select it again.</p>";

			// add exit btn
			ctas.classList = "ctas";
			close_btn.id = "exit_new_media_" + key;
			close_btn.className = "btn cta pink";
			close_btn.dataset.key = key;
			close_btn.innerHTML = util.icon("exit") + "<span>Close</span>";
			close_btn.addEventListener("click", mediaBrowser.close);
			ctas.appendChild(close_btn);
			footer.appendChild(ctas);
		}
		
	},
	cancelNew: function (e) {
		var trg = util.getTrg(e);
		var key = trg.dataset.key,
			src = trg.dataset.src,
			api_endpoint = "delete/media",
			api_params = [];

		console.log("canceling", key);
		
		// setup api
		api_params["src"] = src;

		//send it
		util.xhrFetch(api_endpoint, api_params, mediaBrowser.finishCancel, key);
	},
	finishCancel: function (res, key) {
		console.log("finishing cancel", res);
		mediaBrowser.close(key);
	}
},
media_browser_btns = document.getElementsByClassName("media_browser_btn");

for (var i = 0; i < media_browser_btns.length; i += 1) {
	var btn = media_browser_btns[i];
	btn.addEventListener("click", mediaBrowser.open);
}