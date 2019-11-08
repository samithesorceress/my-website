var mediaManager = {
	saveChanges: function (inputs) {
		console.log("saving MEDIA edits!", inputs);
		var api_endpoint = "update/media",
			items = {},
			fields = [
				"title",
				"alt",
				"public"
			];

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
			for (var i = 0; i < fields.length; i += 1) {
				var field = fields[i];
				if (key.includes(field)) {
					items[id][field] = val;
				}
			}
		}
		
		console.log("items", items);

		for (var id in items) {
			var item = items[id],
				api_params = [];
			api_params["id"] = id;
			for(var key in item) {
				if (!key.includes("file")) {
					api_params[key] = item[key];
				}
			}
			console.log("params", api_params)
			util.xhrFetch(api_endpoint, api_params, mediaManager.validateSave);
		}
		console.dir(items);
	},
	saveNew: function (inputs) {
		console.log("saving new media");
		console.log(inputs);
		if (util.valExists("src", inputs)) {
			util.api.upload(inputs["src"], mediaManager.processNew, inputs)
		} else {
			console.log("src required");
		}
	},
	processNew: function (res, inputs) {
		var api_endpoint = "update/media",
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