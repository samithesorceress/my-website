var photosetManager = {
	saveChanges: function () {

	},
	saveNew: function (inputs) {
		console.log("saving new photoset");
		console.log(inputs);
		var required = [
			"cover",
			"preview",
			"title",
			"description",
			"tags",
			"price",
			"publish_date"
		],
		api_endpoint = "newPhotoset",
		api_params = [],
		errors = [];
		var validation = util.checkRequired(required, inputs);
		console.log(validation);
		if (validation["success"] === true) {
			console.log("validated required inputs");
			for(var i = 0; i < required.length; i += 1) {
				var field = required[i];
				api_params[field] = inputs[field];
			}
			
			if (util.valExists("public", inputs)) {
				api_params["public"] = 1;
			} else {
				api_params["public"] = 0;
			}
			console.dir(api_params);
			api_req = util.xhrFetch(api_endpoint, api_params, photosetManager.validateSave);
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
		console.log(res);
		if (res["success"] === true) {
			console.log("save validated");
			
	//		window.location.href = admin_root + "view-all/photosets";
		} else {
			console.log("didnt work");
		}
	}
}