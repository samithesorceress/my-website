var storeManager = {
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