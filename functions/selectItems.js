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
			var api_endpoint = "delete/",
				api_params = [],
				ids = "",
				list = document.getElementById("view_all_list"),
				type = list.dataset.type;
				
			if (res === true) {
				console.log("user clicked confirm");
				switch(type) {
					case "media":
						api_endpoint += "media";
						break;
					case "photoset":
						api_endpoint += "photoset";
						break;
					case "store-item":
						api_endpoint += "store-item";
						break;
					case "video":
						api_endpoint += "video";
						break;
					case "slide":
						api_endpoint += "slide";
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
	pagination: {
		prev: function (e) {
			util.events.cancel(e);
			var trg = util.getTrg(e),
				offset = trg.dataset.offset,
				list = document.getElementById("view_all_list"),
				type = list.dataset.type;
			api_endpoint  = "list/" + type;
			api_params = [];
			api_params["rows"] = 5;
			api_params["order_by"] = "id";
			api_params["order_dir"] = "DESC";
			api_params["offset"] = offset;
			console.log("api_params", api_params);
			util.xhrFetch(api_endpoint, api_params, selectItems.pagination.updateGrid);

		},
		next: function (e) {
			util.events.cancel(e);
			var trg = util.getTrg(e),
				offset = trg.dataset.offset,
				list = document.getElementById("view_all_list"),
				type = list.dataset.type;
			api_endpoint  = "list/" + type;
			api_params = [];
			api_params["rows"] = 5;
			api_params["order_by"] = "id";
			api_params["order_dir"] = "DESC";
			api_params["offset"] = offset;
			console.log("api_params", api_params);
			util.xhrFetch(api_endpoint, api_params, selectItems.pagination.updateGrid);
		},
		updateGrid: function (res) {
			var list = document.getElementById("view_all_list"),
				type = list.dataset.type,
				shape = "wide",
				prev_btn = document.getElementById("prev_page_btn"),
				next_btn = document.getElementById("next_page_btn");
			if (res["success"] === true) {
				for(var i = 0; i < list_items.length; i += 1) {
					list_items[i].classList.remove("visible");
				}
				if (type == "media") {
					shape = "square";
				}
				setTimeout(function (list, items, pagination) {
					list.innerHTML = "";
					var html = "";
					//if (util.valExists("id", items)) {
					//	items = [items];
					//}
					for (var i = 0; i < items.length; i += 1) {
						var item = items[i];
						html += "<li id='list_item_" + item["id"] + "' class='view_all_list_item grid_item " + shape + "_grid_item' data-key='" + item["id"] + "'><button type='button' class='btn cta fab sml list_item_fab_btn'>" + util.icon("checkbox_checked") + util.icon("checkbox_unchecked") + "</button><a href='" + "http://127.0.0.1/sami-the-sorceress/admin/edit/" + type + "/" + list_item["id"] + "'>";
						if (type == "media") {
							html += util.mediaContainer(item, shape);
						} else {
							html += util.mediaContainer(item["cover"], shape, item["title"]);
						}
						html += "</a></li>";
					}
					for (var i = 0; i < 12; i += 1) {
						html += "<li class='hidden_flex_item grid_item " + shape + "_grid_item'></li>";
					}
					list.innerHTML = html;
					setTimeout(function (){
						var list = document.getElementById("view_all_list"),
							list_items = list.children;
						for(var i = 0; i < list_items.length; i += 1) {
							list_items[i].classList.add("visible");
						}
					}, 10);

					if (pagination) {
						prev_btn.dataset.offset = pagination.prev;
						next_btn.dataset.offset = pagination.next;
						if (pagination["prev"] !== false) {
							prev_btn.classList.remove("disabled");
						} else {
							prev_btn.classList.add("disabled");
						}
						if (pagination["next"] !== false) {
							next_btn.classList.remove("disabled");
						} else {
							next_btn.classList.add("disabled");
						}
					}

				}, 6E2, list, res["data"], res["pagination"]);
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
	delete_selected_btn = document.getElementById("delete_selected_btn"),
	prev_page_btn = document.getElementById("prev_page_btn"),
	next_page_btn = document.getElementById("next_page_btn");
	
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

if (prev_page_btn && next_page_btn) {
	prev_page_btn.addEventListener("click", selectItems.pagination.prev);
	next_page_btn.addEventListener("click", selectItems.pagination.next);
}