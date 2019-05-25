var storeManager = {
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
				dialogBox.add("confirmation", "Are you sure you want to delete these items? This action cannot be undone.", "main",storeManager.confirmDelete, selected);
			}
		}
	},
	confirmDelete: function (res, args) {
		console.log("confirming delete..");
		var api_endpoint = "deleteStoreItem",
			api_params = [];
			ids = "";
			
		if (res === true) {
				console.log("user clicked confirm");
			for (var i = 0; i < args.length; i += 1) {
				var id = args[i];
				ids += id + ",";
			}
			ids = ids.replace(/,+$/,'');
			api_params["id"] = ids;
			console.log(api_params);
			util.xhrFetch(api_endpoint, api_params, storeManager.updateGrid);
		}
	},
	updateGrid: function (res) {
		console.log("updating grid");
		console.log(res);
		var ids = false;
		if (res.success == true) {
			console.log("xhr was successful, deleting dom node");
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
		} else {
			console.log("it didn't work");
		}
	},
	saveChanges: function (inputs) {
		console.log("saving changes~");
		console.log(inputs);
		var items = {},
			api_endpoint = "updateStoreItem",
			api_params = [];

		for (var key in inputs) {
			var value = inputs[key],
				id = false;
			switch(true) {
				case(key.includes("cover")):
					id = key.replace("store_item_cover_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["cover"] = value;
					break;
				case(key.includes("previews")):
					id = key.replace("store_item_previews_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["previews"] = value;
					break;
				case(key.includes("title")):
					id = key.replace("store_item_title_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["title"] = value;
					break;
				case(key.includes("description")):
					id = key.replace("store_item_description_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["description"] = value;
					break;
				case(key.includes("tags")):
					id = key.replace("store_item_tags_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["tags"] = value;
					break;
				case(key.includes("price")):
					id = key.replace("store_item_price_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["price"] = value;
					break;
				case(key.includes("publish_date")):
					id = key.replace("store_item_publish_date_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["publish_date"] = value;
					break;
				case(key.includes("public")):
					id = key.replace("store_item_public_", "");
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
				api_params[key] =  val;
			}
			util.xhrFetch(api_endpoint, api_params, storeManager.validateSave);
		}
	},
	saveNew: function (inputs) {
		console.log("saving new store item");
		console.log(inputs);
		var required = [
			"cover",
			"previews",
			"title",
			"description",
			"tags",
			"price",
			"publish_date"
		],
		api_endpoint = "newStoreItem",
		api_params = [],
		errors = [];
		var validation = util.checkRequired(required, inputs);
		console.log(validation);
		if (validation["success"] === true) {
			console.log("validated required inputs");
			for(var i = 0; i < required.length; i += 1) {
				var field = required[i];
				api_params[field] = inputs[field];
			}
			
			if (util.valExists("public", inputs)) {
				api_params["public"] = 1;
			} else {
				api_params["public"] = 0;
			}
			console.dir(api_params);
			util.xhrFetch(api_endpoint, api_params, storeManager.validateSave);
		} else {
			console.log("no success")
			//missing vals
			for(var key in validation["missing"]) {
				errors.push("Missing: " . key);
			}
			console.error(errors);
		}
	},
	validateSave: function (res) {
		console.log("validating save...");
		console.log(res);
		if (res["success"] === true) {
			console.log("save validated!");
			window.location.href = admin_root + "view-all/store-items";
		} else {
			console.log("didnt work :(");
		}
	}
}

var actions = document.getElementById("actions_for_selections"),
	action_btns;

if (actions) {
	action_btns = actions.children;

	for (var i = 0; i < action_btns.length; i += 1) {
		var btn = action_btns[i];
		switch(btn.dataset.action) {
			case "edit":
				btn.addEventListener("click", storeManager.actions.edit);
				break;
			case "delete":
				btn.addEventListener("click", storeManager.actions.delete);
				break;
		}
	}
}