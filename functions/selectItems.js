var selectItems = {
	deselectAll: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children;

		for (var i = 0; i < list_items.length; i += 1) {
			var list_item = list_items[i];
			selectItems.deselect(list_item);
		}
		selectItems.disableActions();
	},
	selectAll: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children;

		for (var i = 0; i < list_items.length; i += 1) {
			var list_item = list_items[i];
			selectItems.select(list_item);
		}
		selectItems.enableActions();
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
			selectItems.disableActions();
		} else {
			selectItems.select(trg);
			selectItems.enableActions();
		}
	},
	select: function (elem) {
		if (!elem.classList.contains("selected")) {
			elem.classList.add("selected");
			elem.addEventListener("click", selectItems.toggle);
		}
		selectItems.enableActions();
	},
	deselect: function (elem) {
		if (elem.classList.contains("selected")) {
			elem.classList.remove("selected");
			elem.removeEventListener("click", selectItems.toggle);
		}
		selectItems.disableActions();
	},
	enableActions: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children,
			actions = document.getElementById("actions_for_selections");
		if (!ul.classList.contains("selections_active")) {
			ul.classList.add("selections_active");
			actions.classList.remove("disabled");
			for (var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				list_item.removeEventListener("touchstart", selectItems.touchStart);
			}
		}
	},
	disableActions: function () {
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children,
			selected = false,
			actions = document.getElementById("actions_for_selections");
		if (ul.classList.contains("selections_active")) {
			for (var i = 0; i < list_items; i += 1) {
				var list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					selected = false;
				}
			}
			if (!selected) {
				ul.classList.remove("selections_active");
				actions.classList.add("disabled");
				for (var i = 0; i < list_items.length; i += 1) {
					var list_item = list_items[i];
					list_item.addEventListener("touchstart", selectItems.touchStart);
				}
			}
		}
	},
	touchStart: function (e) {
		util.events.listen.longPress(e, selectItems.select)
	},
	actions: {
		edit: function (e) {
			util.events.cancel(e);
			var ul = document.getElementById("view_all_list"),
				list_items = ul.children,
				url = "";
			
			url += ul.dataset.type + "/";
			for (var i = 0; i < list_items.length; i += 1) {
				list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					url += list_item.dataset.key + "+";
				}
			}
			url = url.replace(/\++$/,'');

			window.location.href = "http://127.0.0.1/sami-the-sorceress/admin/edit/" + url;
		},
		delete: function (e) {
			util.events.cancel(e);
			var ul = document.getElementById("view_all_list"),
				list_items = ul.children,
				selected = [];

			for (var i = 0; i < list_items.length; i += 1) {
				list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					selected[] = list_item.dataset.key;
				}
			}

			if (selected.length) {
				// make spawn dialog function <3
				util.spawn.dialog("confirmation", "Are you sure you want to delete these items? This action cannot be undone.", selectItems.actions.confirmDelete, selected);
			}
		},
		confirmDelete: function () {

		}
	}
}
//onload
var ul = document.getElementById("view_all_list"),
	list_items = ul.children;
for (var i = 0; i < list_items.length; i += 1) {
	list_item = list_items[i];
	list_item.addEventListener("click", selectItems.toggle);
	list_item.addEventListener("touchstart", selectItems.touchStart);
}