var editAbout = {
	saveChanges: function (inputs) {
		console.log("saving edits!");
		console.log(inputs);
		var api_endpoint = "update/about",
			api_params = [],
			links = {}, 
			json = {};

		for (var key in inputs) {
			var value = inputs[key],
				id = false;
			if (key.includes("link")) {
				switch(true) {
					case(key.includes("url")):
						id = key.replace("link_url_", "");
						if (!links[id]) {
							links[id] = {};
						}
						links[id]["url"] = encodeURIComponent(value);
						break;
					case(key.includes("title")):
						id = key.replace("link_title_", "");
						if (!links[id]) {
							links[id] = {};
						}
						links[id]["title"] = encodeURIComponent(value);
						break;
				}
			} else {
				api_params[key] = encodeURIComponent(value);
			}
		}

		if (links) {
			var i = 0;
			for (var key in links) {
				json[i] = links[key];
				i  += 1;
			}
			json = JSON.stringify(json);
			console.log(json);
			api_params["links"] = json;
		}
		console.log(api_params);
		util.xhrFetch(api_endpoint, api_params, editAbout.validateSave);

		
	},
	validateSave: function (res) {
		if (res.success) {
			window.location.href = "http://127.0.0.1/sami-the-sorceress/admin";
		}
	}
}