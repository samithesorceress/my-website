var forms = {
	callbacks: { //string conversions
	},
	clickSave: function (e) {
		// first stop submission/bubbles
		util.events.cancel(e);
		
		forms.updateCbList();

		// then collect all the elements
		var trg = util.getTrg(e),
			inputs = forms.getElements(),
			cb = forms.callbacks[trg.dataset.cb];
			if (cb) {
				cb(inputs);
			} else {
				alert("no callback for this form");
			}
	},
	getElements: function () {
		console.log("getting form elements");
		var fields = document.getElementsByClassName("field"),
			inputs = [];
		console.log("fields: " + fields.length);
		for (var i = 0; i < fields.length; i += 1) {
			var field = fields[i];
			var children = field.children;
			for (var ii = 0; ii < children.length; ii += 1) {
				var child = children[ii],
					nested_children = false;
				if (child.tagName == "INPUT" || child.tagName == "TEXTAREA" || child.tagName == "DIV") {
					if (child.tagName == "DIV") {
						var nested_children = child.children;
						for (var iii = 0; iii < nested_children.length; iii += 1) {
							var nested_child = nested_children[iii];
							if (nested_child.tagName == "INPUT" || nested_child.tagName == "TEXTAREA") {
								inputs[nested_child.id] = nested_child.value;
							}
						}
					} else {
						if (child.type !== "submit") {
							if (child.type == "checkbox") {
								if (child.checked) {
									inputs[child.id] = 1;
								} else {
									inputs[child.id] = 0;
								}
							} else {
								if (child.type == "file") {
									inputs[child.id] = child.files[0];
								} else {
									inputs[child.id] = child.value;
								}
							}
						}
					}
				}
			}
		}
		return inputs;
	},
	updateCbList: function () {
		if (typeof(editAbout) !== "undefined") {
			if (!forms.callbacks["editAbout.saveChanges"]) {
				forms.callbacks["editAbout.saveChanges"] = editAbout.saveChanges;
			}
		}		
		if (typeof(videoManager) !== "undefined") {
			if (!forms.callbacks["videoManager.saveNew"]) {
				forms.callbacks["videoManager.saveNew"] = videoManager.saveChanges;
			}
			if (!forms.callbacks["videoManager.saveChanges"]) {
				forms.callbacks["videoManager.saveChanges"] = videoManager.saveChanges;
			}
		}
		if (typeof(photosetManager) !== "undefined") {
			if (!forms.callbacks["photosetManager.saveNew"]) {
				forms.callbacks["photosetManager.saveNew"] = photosetManager.saveNew;
			}
			if (!forms.callbacks["photosetManager.saveChanges"]) {
				forms.callbacks["photosetManager.saveChanges"] = photosetManager.saveChanges;
			}
		}
		if (typeof(storeManager) !== "undefined") {
			if (!forms.callbacks["storeManager.saveNew"]) {
				forms.callbacks["storeManager.saveNew"] = storeManager.saveNew;
			}
			if (!forms.callbacks["storeManager.saveChanges"]) {
				forms.callbacks["storeManager.saveChanges"] = storeManager.saveChanges;
			}
		}
		if (typeof(mediaManager) !== "undefined") {
			if (!forms.callbacks["mediaManager.saveNew"]) {
				forms.callbacks["mediaManager.saveNew"] = mediaManager.saveNew;
			}
			if (!forms.callbacks["mediaManager.saveChanges"]) {
				forms.callbacks["mediaManager.saveChanges"] = mediaManager.saveChanges;
			}
		}
	}
},
inputs = document.getElementsByTagName("input");

for(var i = 0; i < inputs.length; i += 1) {
	var input = inputs[i]
	if (input.type == "submit") {
		input.addEventListener("click", forms.clickSave);
	}
}