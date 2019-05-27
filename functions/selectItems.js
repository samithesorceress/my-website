var selectItems = {
	actions: {
		selectAll: function () {
			var list_items = document.getElementsByClassName("view_all_list_item");
	
			for (var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				if (!list_item.classList.contains("hidden-flex-item")) {
					selectItems.select(list_item);
				}
			}
			selectItems.enableSelectionActions();
			selectItems.disableDefaultActions();
		},
		deselectAll: function () {
			var list_items = document.getElementsByClassName("view_all_list_item");
	
			for (var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				selectItems.deselect(list_item);
			}
			selectItems.disableSelectionActions();
			selectItems.enableDefaultActions();
		},
		edit: function (e) {
			util.events.cancel(e);
			var list = document.getElementById("view_all_list"),
				list_items = list.children,
				type = list.dataset.type,
				url = type + "/";

			for (var i = 0; i < list_items.length; i += 1) {
				list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					url += list_item.dataset.key + "%2C";
				}
			}
			url = url.replace(/%2C+$/,'');

			window.location.href = "http://127.0.0.1/sami-the-sorceress/admin/edit/" + url;
		},
		delete: function (e) {
			util.events.cancel(e);
			var list = document.getElementById("view_all_list"),
				list_items = list.children,
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
				dialogBox.add("confirmation", "Are you sure you want to delete these items? This action cannot be undone.", "main", selectItems.actions.confirmDelete, selected);
			}
		},
		confirmDelete: function (res, args) {
			console.log("confirming delete..");
			var api_endpoint = "delete",
				api_params = [],
				ids = "";
				
			if (res === true) {
				console.log("user clicked confirm");
				switch(type) {
					case "media":
							api_endpoint += "Media";
						break;
					case "photoset":
							api_endpoint += "Photoset";
						break;
					case "store-item":
							api_endpoint += "StoreItem";
						break;
					case "video":
							api_endpoint += "Video";
						break;
				}
				for (var i = 0; i < args.length; i += 1) {
					var id = args[i];
					ids += id + ",";
				}
				ids = ids.replace(/,+$/,'');
				api_params["id"] = ids;
				console.log(api_params);
				util.xhrFetch(api_endpoint, api_params, selectItems.updateGrid);
			} else {
				console.log("user clicked no");
			}
		}
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
					if (!list_item.classList.contains("hidden-flex-item")) {
						none_selected = false;
					}
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
						if (!list_item.classList.contains("hidden-flex-item")) {
							all_selected = false;
						}
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
					if (!list_item.classList.contains("hidden-flex-item")) {
						all_selected = false;
					}
				}
			}
			if (all_selected) {
				actions.classList.add("disabled");
			}
		}
	},
	updateGrid: function (res) {
		console.log("updating grid");
		console.log(res);
		var ids = false;
		if (res.success == true) {
			console.log("xhr was successful, deleting dom node");
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
			selectItems.disableSelectionActions();
			selectItems.enableDefaultActions();
		} else {
			console.log("it didn't work");
		}
	}
}

var fabs = document.getElementsByClassName("list_item_fab_btn"),
	list_items = document.getElementsByClassName("view_all_list_item"),
	actions_bar = document.getElementById("view_all_actions_bar"),
	select_all_btn = document.getElementById("select_all_btn"),
	deselect_all_btn = document.getElementById("deselect_all_btn"),
	edit_selected_btn = document.getElementById("edit_selected_btn"),
	delete_selected_btn = document.getElementById("delete_selected_btn");
	
if (fabs) {
	for (var i = 0; i < fabs.length; i += 1) {
		var fab = fabs[i];
		fab.addEventListener("click", selectItems.toggle);
	}
}

if (list_items) {
	for (var i = 0; i < list_items.length; i += 1) {
		var list_item = list_items[i];
		if (!list_item.classList.contains("hidden-flex-item")) {
			list_item.classList.add("visible");
		}
	}
}
if (actions_bar) {
	select_all_btn.addEventListener("click", selectItems.actions.selectAll);
	deselect_all_btn.addEventListener("click", selectItems.actions.deselectAll);
	edit_selected_btn.addEventListener("click", selectItems.actions.edit);
	delete_selected_btn.addEventListener("click", selectItems.actions.delete);
}