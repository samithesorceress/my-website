var selectItems = {
	deselectAll: function () {
		var ul = document.getElementById("view_all_list"),
			actions = document.getElementById("actions_for_selections");
		var list_items = ul.children;
		
		if (ul.classList.contains("selections_active")) {
			ul.classList.remove("selections_active");
			actions.classList.add("disabled");

			for (var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					list_item.classList.remove("selected");
					list_item.removeEventListener("click", selectItems.toggle);
				}
			}
		}
	},
	selectAll: function () {
		var ul = document.getElementById("view_all_list"),
			actions = document.getElementById("actions_for_selections");
		var list_items = ul.children;
		
		if (!ul.classList.contains("selections_active")) {
			ul.classList.add("selections_active");
			actions.classList.remove("disabled");
		}

		for (var i = 0; i < list_items.length; i += 1) {
			var list_item = list_items[i];
			if (!list_item.classList.contains("selected")) {
				list_item.classList.add("selected");
				list_item.addEventListener("click", selectItems.toggle);
			}
		}
	},
	toggle: function(e) {
		util.events.cancel(e);
		var trg = util.getTrg(e),
			ul, list_items,
			actions = document.getElementById("actions_for_selections");
			selections_active = false;

		if(trg.classList.contains("fab")) {
			trg = trg.parentNode;
		}
		ul = trg.parentNode;
		list_items = ul.children;

		trg.classList.toggle("selected");
		if (trg.classList.contains("selected")) {
			if (!ul.classList.contains("selections_active")) {
				ul.classList.add("selections_active");
				actions.classList.remove("disabled");
				for (var i = 0; i < list_items.length; i += 1) {
					var list_item = list_items[i];
					list_item.addEventListener("click", selectItems.toggle);

				}
			}
		} else {
			for (var i = 0; i < list_items.length; i += 1) {
				var list_item = list_items[i];
				if (list_item.classList.contains("selected")) {
					selections_active = true;
				}
			}
			if (!selections_active) {
				ul.classList.remove("selections_active");
				actions.classList.add("disabled")
				for (var i = 0; i < list_items.length; i += 1) {
					var list_item = list_items[i];
					list_item.removeEventListener("click", selectItems.toggle);
				}
			}
		}
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
		delete: function () {
			//todo
		}
	},

	setState: {
		active: function () {

		},
		inactive: function () {

		}
	}
}