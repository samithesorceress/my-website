var videoManager = {
	saveChanges: function (inputs) {
		console.log("saving edits!", inputs);
		var api_endpoint = "update/video",
			api_params = [],
			items = {},
			prefix = "video",
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
			links;

		for (var key in inputs) {
			var val = inputs[key],
				id = false;
			for (var i = 0; i < fields.length; i += 1) {
				var field = fields[i],
					field_id;
				if (key.includes(field) && !key.includes("link")) {
					field_id = prefix + "_" + field + "_";
					id = key.replace(field_id, "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id][field] = val;
				}
			}
		}
		links = util.formatLinks(inputs);
		if (links) {
			api_params["links"] = links;
		}
		console.log("items", items);
		
		for (var id in items) {
			var item = items[id];
			api_params["id"] = id;
			for(var key in item) {
				api_params[key] = item[key];
			}
			console.log("params", api_params);
		}
		util.xhrFetch(api_endpoint, api_params, videoManager.validateSave);
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
		
		validation = util.checkRequired(required, inputs);
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
			console.log('items', api_params);
			util.xhrFetch(api_endpoint, api_params, videoManager.validateSave);
		} else {
			console.log("missing required", validation.data);
		}
	},
	validateSave: function (res) {
		if (res.success === true) {
			window.location.href = "http://127.0.0.1/sami-the-sorceress/admin/view-all/videos";
		}
	}
}