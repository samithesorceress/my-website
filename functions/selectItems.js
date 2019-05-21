var selectItems = {
	deselectAll: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children;

		for (var i = 0; i < list_items.length; i += 1) {
			var list_item = list_items[i];
			selectItems.deselect(list_item);
		}
		selectItems.disableSelectionActions();
	},
	selectAll: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children;

		for (var i = 0; i < list_items.length; i += 1) {
			var list_item = list_items[i];
			selectItems.select(list_item);
		}
		selectItems.enableSelectionActions();
	},
	toggle: function(e) {
		if (!util.isNode(e)) {
			util.events.cancel(e);
		}
		var trg = e;
		if (!util.isNode(trg)) {
			trg = util.getTrg(e);
		}
		if(trg.classList.contains("fab")) {
			trg = trg.parentNode;
		}

		if (trg.classList.contains("selected")) {
			selectItems.deselect(trg);
			selectItems.disableSelectionActions();
		} else {
			selectItems.select(trg);
			selectItems.enableSelectionActions();
		}
	},
	select: function (elem) {
		if (!elem.classList.contains("selected")) {
			elem.classList.add("selected");
			elem.addEventListener("click", selectItems.toggle);
		}
		selectItems.enableSelectionActions();
		selectItems.disableDefaultActions();
	},
	deselect: function (elem) {
		if (elem.classList.contains("selected")) {
			elem.classList.remove("selected");
			elem.removeEventListener("click", selectItems.toggle);
		}
		selectItems.disableSelectionActions();
		selectItems.enableDefaultActions();
	},
	enableSelectionActions: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children,
			actions = document.getElementById("actions_for_selections");
		if (!ul.classList.contains("selections_active")) {
			ul.classList.add("selections_active");
			actions.classList.remove("disabled");
			for (var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				list_item.addEventListener("click", selectItems.toggle);
			}
		}
	},
	disableSelectionActions: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children,
			none_selected = true,
			actions = document.getElementById("actions_for_selections");

		if (ul.classList.contains("selections_active")) {
			for (var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					none_selected = false;
				}
			}
			if (none_selected) {
				ul.classList.remove("selections_active");
				actions.classList.add("disabled");
				for (var i = 0; i < list_items.length; i += 1) {
					var list_item = list_items[i];
					list_item.removeEventListener("click", selectItems.toggle);
				}
			}
		}
	},
	enableDefaultActions: function () {
		var actions = document.getElementById("default_actions"),
			ul = document.getElementById("view_all_list"),
			list_items = ul.children,
			all_selected = true;

			if (actions.classList.contains("disabled")) {
				for(var i = 0; i < list_items.length; i += 1) {
					var list_item = list_items[i];
					if (!list_item.classList.contains("selected")) {
						all_selected = false;
					}
				}
				if (!all_selected) {
					actions.classList.remove("disabled");
				}
			}


	},
	disableDefaultActions: function () {
		var actions = document.getElementById("default_actions"),
			ul = document.getElementById("view_all_list"),
			list_items = ul.children,
			all_selected = true;

		if (!actions.classList.contains("disabled")) {
			for(var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				if (!list_item.classList.contains("selected")) {
					all_selected = false;
				}
			}
			if (all_selected) {
				actions.classList.add("disabled");
			}
		}
	}
}