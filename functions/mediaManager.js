var mediaManager = {
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