var videoManager = {
	saveChanges: function (inputs) {
		console.log("saving edits!", inputs);
		var api_endpoint = "update/video",
			items = {},
			links = {},
			fields = [
				"cover",
				"stream",
				"title",
				"description",
				"tags",
				"price",
				"publish_date",
				"timestamp",
				"url",
				"public"
			],
			current_links;

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
					var field = fields[i]
					if (key.includes(field)) {
						items[id][field] = val;
					}
				}
			}
		}
		
		console.log("items", items);
		console.log("links", links);

		for (var id in items) {
			var item = items[id],
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
			util.xhrFetch(api_endpoint, api_params, videoManager.validateSave);
		}
	},
	saveNew: function (inputs) {
		console.log("saving new video");
		console.log(inputs);
		var api_endpoint = "new/video",
			api_params = [],
			prefix = "video_",
			fields = [
				"cover",
				"preview",
				"title",
				"description",
				"tags",
				"price",
				"publish_date",
				"timestamp",
				"url",
				"public"
			],
			required = [
				prefix + "cover",
				prefix + "preview",
				prefix + "title",
				prefix + "description",
				prefix + "tags",
				prefix + "price",
				prefix + "publish_date",
				prefix + "timestamp",
				prefix + "url"
			],
			links;
		
		var validation = util.checkRequired(required, inputs);
		if (validation.success === true) {
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
			console.log('api_params', api_params);
			util.xhrFetch(api_endpoint, api_params, videoManager.validateSave);
		} else {
			console.log("missing required", validation.data);
		}
	},
	validateSave: function (res) {
		if (res.success === true) {
			console.log("save validated");
		//	window.location.href = "http://127.0.0.1/sami-the-sorceress/admin/view-all/videos";
		}
	}
}