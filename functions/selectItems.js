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
					selected.push(list_item.dataset.key);
				}
			}
			console.log("selected : ");
			console.dir(selected);

			if (selected.length) {
				// make spawn dialog function <3
				util.dialog.add("confirmation", "Are you sure you want to delete these items? This action cannot be undone.", "main", selectItems.actions.confirmDelete, selected);
			}
		},
		confirmDelete: function (res, args) {
			var api_endpoint = "deleteMedia",
				api_params = "?id=";
				
			if (res === true) {
				for (var i = 0; i < args.length; i += 1) {
					var id = args[i];
					api_params += id + ",";
				}
				api_params = api_params.replace(/,+$/,'');

				util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/" + api_endpoint + api_params, selectItems.updateGrid);
			}
		}
	},
	updateGrid: function (res) {
		console.log("updating grid");
		var ids = false;
		if (res.success == true) {
			console.log("success");
			ids = res.data;
			console.log(ids);
			for(var i = 0; i < ids.length; i += 1) {
				var id = ids[i],
					elem = document.getElementById("list_item_" + id);

				elem.classList.remove("visible");
				setTimeout(function (elem) {
					elem.parentNode.removeChild(elem);
				}, 6E2, elem);
			}
		}
	}
}