var slideshowManager = {
	saveChanges: function (inputs) {
		console.log("saving slideshow changes~", inputs);
		var api_endpoint = "update/slide",
			items = {},
			fields = [
				"cover",
				"title",
				"url",
				"public"
			];
		for (var key in inputs) {
			var val = inputs[key],
				id = key.split("_")[2];
			console.log(id);
			if (!items[id]) {
				items[id] = [];
			}
			for (var i = 0; i < fields.length; i += 1) {
				var field = fields[i];
				if (key.includes(field)) {
					items[id][field] = val;
				}
			}

			console.log("items", items);

			for (var id in items) {
				var item = items[id],
					api_params = [];
				api_params["id"] = id;
				for(var key in item) {
					api_params[key] = item[key];
				}
				console.log("params", api_params);
				util.xhrFetch(api_endpoint, api_params, slideshowManager.validateSave);
			}
		}
	},
	saveNew: function (inputs) {
		console.log("saving new photoset", inputs);
		var api_endpoint = "new/slide",
			api_params = [],
			prefix = "slide_",
			fields = [
				"cover",
				"title",
				"url",
				"public"
			],
			required = [
				prefix + "cover",
				prefix + "title",
				prefix + "url",
			];
		var validation = util.checkRequired(required, inputs);
		if (validation["success"] === true) {
			console.log("validated required inputs");
			for (var key in inputs) {
				var name = key.replace(prefix, "");
				if (fields[name] !== "undefined") {
					api_params[name] = inputs[key];
				}
			}
			console.dir("api_params", api_params);
			util.xhrFetch(api_endpoint, api_params, slideshowManager.validateSave);
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
			window.location.href = admin_root + "view-all/slides";
		} else {
			console.log("didnt work :(");
		}
	}
}