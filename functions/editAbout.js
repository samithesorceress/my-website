var editAbout = {
	saveChanges: function (inputs) {
		console.log("saving edits!");
		console.log(inputs);
		var api_endpoint = "updateAbout",
			api_params = "?",
			links = {};

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
				api_params += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
			}
		}

		if (links) {
			links = JSON.stringify(links);
			api_params += "links=" + links;
			util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/" + api_endpoint + api_params, editAbout.validateSave);
		}
		
		console.log(links);

		api_params = api_params.replace(/&+$/,'');

		console.log(api_params);
	},
	validateSave: function (res) {
		if (res.success) {
		//	window.location.href = "http://127.0.0.1/sami-the-sorceress/admin";
		}
	}
}