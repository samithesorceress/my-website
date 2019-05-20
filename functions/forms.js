var form = {
	inputs: [],
	clickSave: function (e) {
		// first stop submission/bubbles
		util.events.cancel(e);
		
		// then collect all the elements
		var trg = util.getTrg(e);
		var parent = trg.parentNode;

	},
	getElements: function () {
		var fields = document.getElementsByClassName("field");
		for (var i = 0; i < fields.length; i += 1) {
			var field = fields[i];
			var children = field.childNodes;
			for (var j = 0; j < children.length; j += 1) {
				var child = children[j];
				if (child.tagName == "input" || child.tagName == "textarea") {
					form.inputs.push(child);
				}
			}
		}
		console.dir(form.inputs);
	}
}

document.body.onLoad = form.getElements();