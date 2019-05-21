var mediaManager = {
	edit: function (e) {
		util.events.cancel(e);
		var ul = document.getElementById("view_all_list"),
			list_items = ul.children,
			url = "";
		
		url += ul.dataset.type + "/";
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
			util.dialog.add("confirmation", "Are you sure you want to delete these items? This action cannot be undone.", "main",mediaManager.confirmDelete, selected);
		}
	},
	confirmDelete: function (res, args) {
		var api_endpoint = "deleteMedia",
			api_params = "?id=";
			
		if (res === true) {
			for (var i = 0; i < args.length; i += 1) {
				var id = args[i];
				api_params += id + "%2C";
			}
			api_params = api_params.replace(/%2C+$/,'');

			util.api.request("GET", "http://127.0.0.1/sami-the-sorceress/api/" + api_endpoint + api_params, selectItems.updateGrid);
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
	},
	saveEdits: function (inputs) {
		console.log("saving edits!");
		items = {};
		for (var i = 0; i < inputs.length; i += 1) {

		}
	}
}