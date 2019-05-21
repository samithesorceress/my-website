var forms = {
	callbacks: { //string conversions
		"mediaManager.saveEdits": mediaManager.saveEdits
	},
	clickSave: function (e) {
		// first stop submission/bubbles
		util.events.cancel(e);
		
		// then collect all the elements
		var trg = util.getTrg(e),
			inputs = forms.getElements(),
			cb = forms.callbacks[trg.dataset.cb];
			cb(inputs);
	},
	getElements: function () {
		console.log("getting form elements");
		var fields = document.getElementsByClassName("field"),
			inputs = [];
		console.log("fields: " + fields.length);
		for (var i = 0; i < fields.length; i += 1) {
			var field = fields[i];
			var children = field.children;
			for (var j = 0; j < children.length; j += 1) {
				var child = children[j];
				if (child.tagName == "INPUT" || child.tagName == "TEXTAREA") {
					if (child.type !== "submit") {
						if (child.type == "checkbox") {
							if (child.checked) {
								inputs[child.id] = 1;
							} else {
								inputs[child.id] = 0;
							}
						} else {
							inputs[child.id] = child.value;
						}
					}
				}
			}
		}
		return inputs;
	}
},
inputs = document.getElementsByTagName("input");

for(var i = 0; i < inputs.length; i += 1) {
	var input = inputs[i]
	if (input.type == "submit") {
		input.addEventListener("click", forms.clickSave);
	}
}