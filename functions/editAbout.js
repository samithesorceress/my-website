var editAbout = {
	saveChanges: function (inputs) {
		console.log("saving edits!");
		console.log(inputs);
		var api_endpoint = "update/about",
			api_params = [],
			links;
		links = util.formatLinks(inputs);
		if (links) {
			api_params["links"] = links;
		}
		for (var key in inputs) {
			if (!key.includes("link")) {
				api_params[key] = encodeURIComponent(inputs[key]);
			}
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