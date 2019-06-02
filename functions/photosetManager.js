var photosetManager = {
	saveChanges: function (inputs) {
		console.log("saving changes~", inputs);
		var api_endpoint = "update/photoset",
			api_params = [],
			items = {},
			fields = [
				"cover",
				"previews",
				"title",
				"description",
				"tags",
				"price",
				"publish_date",
				"public"
			],
			links = {};

		for (var key in inputs) {
			var val = inputs[key],
				id = key.split("_");
			if (key.includes("publish_date")) {
				id = id[3];
			} else {
				id = id[2];
			}
			console.log(id);
			if (!items[id]) {
				items[id] = [];
			}
			if (!links[id]) {
				links[id] = [];
			}
			if (key.includes("link")) {
					links[id][key] = val;
			} else {
				for (var i = 0; i < fields.length; i += 1) {
					var field = fields[i];
					if (key.includes(field)) {
						items[id][field] = val;
					}
				}
			}
		}
		
		console.log("items", items);
		console.log("links", links);

		for (var id in items) {
			var item = items[id];
			api_params = [];
			api_params["id"] = id;
			for(var key in item) {
				api_params[key] = item[key];
			}
			current_links = util.formatLinks(links[id]);
			if (current_links) {
				api_params["links"] = current_links;
			}
			console.log("params", api_params);
			util.xhrFetch(api_endpoint, api_params, photosetManager.validateSave);
		}
	},
	saveNew: function (inputs) {
		console.log("saving new photoset");
		console.log(inputs);
		var api_endpoint = "new/photoset",
			api_params = [],
			prefix = "photoset_",
			fields = [
				"cover",
				"preview",
				"title",
				"description",
				"tags",
				"price",
				"publish_date",
				"public"
			],
			required = [
				prefix + "cover",
				prefix + "preview",
				prefix + "title",
				prefix + "description",
				prefix + "tags",
				prefix + "price",
				prefix + "publish_date"
			],
			links;
		
		var validation = util.checkRequired(required, inputs);
		console.log(validation);
		if (validation["success"] === true) {
			console.log("validated required inputs");
			for (var key in inputs) {
				var name = key.replace(prefix, "");
				if (fields[name] !== "undefined" && !key.includes("link")) {
					api_params[name] = inputs[key];
				}
			}
			links = util.formatLinks(inputs);
			if (links) {
				api_params["links"] = links;
			}
			console.dir("api_params", api_params);
			util.xhrFetch(api_endpoint, api_params, photosetManager.validateSave);
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
			
			window.location.href = admin_root + "view-all/photosets";
		} else {
			console.log("didnt work :(");
		}
	}
}