var mediaManager = {
	actions: {
		edit: function (e) {
			util.events.cancel(e);
			var ul = document.getElementById("view_all_list"),
				list_items = ul.children,
				url = "";
			
			url += ul.dataset.type + "/";
			for (var i = 0; i < list_items.length; i += 1) {
				list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					url += list_item.dataset.key + "%2C";
				}
			}
			url = url.replace(/%2C+$/,'');

			window.location.href = "http://127.0.0.1/sami-the-sorceress/admin/edit/" + url;
		},
		delete: function (e) {
			util.events.cancel(e);
			var ul = document.getElementById("view_all_list"),
				list_items = ul.children,
				selected = [];

			for (var i = 0; i < list_items.length; i += 1) {
				list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					selected.push(list_item.dataset.key);
				}
			}
			console.log("selected : ");
			console.dir(selected);

			if (selected.length) {
				dialogBox.add("confirmation", "Are you sure you want to delete these items? This action cannot be undone.", "main",mediaManager.confirmDelete, selected);
			}
		}
	},
	confirmDelete: function (res, args) {
		var api_endpoint = "deleteMedia",
			api_params = "?id=";
			
		if (res === true) {
			for (var i = 0; i < args.length; i += 1) {
				var id = args[i];
				api_params += id + "%2C";
			}
			api_params = api_params.replace(/%2C+$/,'');

			util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/" + api_endpoint + api_params, selectItems.updateGrid);
		}
	},
	updateGrid: function (res) {
		console.log("updating grid");
		var ids = false;
		if (res.success == true) {
			console.log("success");
			ids = res.data;
			console.log(ids);
			for(var i = 0; i < ids.length; i += 1) {
				var id = ids[i],
					elem = document.getElementById("list_item_" + id);

				elem.classList.remove("visible");
				setTimeout(function (elem) {
					elem.parentNode.removeChild(elem);
				}, 6E2, elem);
			}
		}
	},
	saveChanges: function (inputs) {
		console.log("saving edits!");
		console.log(inputs);
		var items = {},
			api_endpoint = "updateMediaInfo",
			api_params = [];
		for (var key in inputs) {
			var value = inputs[key],
				id = false;
			switch(true) {
				case(key.includes("file")):
					id = key.replace("media_file_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["src"] = value;
					break;
				case(key.includes("title")):
					id = key.replace("media_title_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["title"] = value;
					break;
				case(key.includes("alt")):
					id = key.replace("media_alt_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["alt"] = value;
					break;
				case(key.includes("public")):
					id = key.replace("media_public_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["public"] = value;
					break;
			}
		}
		for (var id in items) {
			var item = items[id];
			api_params["id"] = id;
			for(var key in item) {
				var val = item[key];
				api_params[key] = val;
			}
			util.xhrFetch(api_endpoint, api_params, mediaManager.validateSave);
		}
		console.dir(items);
	},
	saveNew: function (inputs) {
		console.log("saving new media");
		console.log(inputs);
		var api_endpoint = "uploadFile",
			api_params = [],
			file, url;
		if (util.valExists("src", inputs)) {
			util.api.upload(inputs["src"], mediaManager.processNew, inputs)
		} else {
			console.log("src required");
		}
	},
	processNew: function (res, inputs) {
		var api_endpoint = "updateMediaInfo",
			api_params = [],
			src = false;
		console.log(res);
		if (res["success"] === true) {
			console.log("file uploaded!");
			console.log("adding metadata...");
			src = res["data"]["src"];
			api_params["src"] = src;

			if (util.valExists("alt", inputs)) {
				api_params["alt"] = inputs["alt"];
			}
			if (util.valExists("title", inputs)) {
				api_params["title"] = inputs["title"];
			}
			if (util.valExists("public", inputs)) {
				api_params["public"] = 1;
			} else {
				api_params["public"] = 0;
			}
			console.log(api_params);
			util.xhrFetch(api_endpoint, api_params, mediaManager.validateSave, inputs);
		} else {
			console.log("failed to upload file.");
		}
	},
	validateSave: function (res) {
		console.log("validating save...");
		console.log(res);
		if (res["success"] === true) {
			console.log("save validated!");
			
			window.location.href = admin_root + "view-all/media";
		} else {
			console.log("didnt work :(");
		}
	}
}

var list = document.getElementById("view_all_list"),
	list_items,
	actions = document.getElementById("actions_for_selections"),
	action_btns;

if (list) {
	list_items = list.children;

	for (var i = 0; i < list_items.length; i += 1) {
		var list_item = list_items[i];
		list_item.classList.add("visible");
	}
}
if (actions) {
	action_btns = actions.children;

	for (var i = 0; i < action_btns.length; i += 1) {
		var btn = action_btns[i];
		switch(btn.dataset.action) {
			case "edit":
				btn.addEventListener("click", mediaManager.actions.edit);
				break;
			case "delete":
				btn.addEventListener("click", mediaManager.actions.delete);
				break;
		}
	}
}