var videoManager = {
	saveChanges: function (inputs) {
		console.log("saving edits!");
		console.log(inputs);
		var items = {},
			api_endpoint = "updateVideo",
			api_params = "";
		for (var key in inputs) {
			var value = inputs[key],
				id = false;
			switch(true) {
				case(key.includes("cover")):
					id = key.replace("video_cover_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["cover"] = value;
					break;
				case(key.includes("preview")):
					id = key.replace("video_preview_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["preview"] = value;
					break;
				case(key.includes("title")):
					id = key.replace("video_title_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["title"] = value;
					break;
				case(key.includes("description")):
					id = key.replace("video_description_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["description"] = value;
					break;
				case(key.includes("tags")):
					id = key.replace("video_tags_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["tags"] = value;
					break;
				case(key.includes("price")):
					id = key.replace("video_price_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["price"] = value;
					break;
				case(key.includes("publish_date")):
					id = key.replace("video_publish_date", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["publish_date"] = value;
					break;
				case(key.includes("public")):
					id = key.replace("video_public_", "");
					if (!items[id]) {
						items[id] = [];
					}
					items[id]["public"] = value;
					break;
			}
		}
		for (var id in items) {
			var item = items[id];
			api_params = "?id=" + id + "&";
			for(var key in item) {
				api_params += key + "=" + item[key] + "&";
			}
			api_params = api_params.replace(/&+$/,'');
			util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/" + api_endpoint + api_params, videoManager.validateSave);
		}
		console.dir(items);
	},
	validateSave: function (res) {
		if (res.success) {
			window.location.href = "http://127.0.0.1/sami-the-sorceress/admin/view-all/videos";
		}
	}
}