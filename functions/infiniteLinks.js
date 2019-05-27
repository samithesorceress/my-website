var infiniteLinks = {
	add(e) {
		util.events.cancel(e);
		var trg = util.getTrg(e),
			field = trg.parentNode.parentNode,
			list,
			num = null,
			newLink, url_field, title_field, delete_btn;

		if (field.classList.contains("infinite_links")) {
			list = field.getElementsByClassName("links_list")[0];
			num = list.children.length;
			newLink = document.createElement("li");
			newLink.className = "field";

			url_field = document.createElement("div");
			url_field.innerHTML = "<label for='link_url_" + num + "'>Url</label><input id='link_url_" + num +  "' name='link_url_" + num +  "' type='text'></input>";
			title_field = document.createElement("div");
			title_field.innerHTML = "<label>Title</label><input id='link_title_" + num +  "' name='link_title_"  + num + "' type='text'>";
			
			delete_btn = document.createElement("button");
			delete_btn.className = "btn delete_link_btn";
			delete_btn.type = "button";
			delete_btn.innerHTML = util.icon("delete");
			delete_btn.addEventListener("click", infiniteLinks.delete);

			newLink.appendChild(url_field);
			newLink.appendChild(title_field);
			newLink.appendChild(delete_btn);

			list.appendChild(newLink);
		}
	},
	delete(e) {
		var trg = util.getTrg(e), li;
		li = trg.parentNode;
		li.parentNode.removeChild(li);
	}
},
add_link_btns = document.getElementsByClassName("add_link_btn"),
delete_link_btns = document.getElementsByClassName("delete_link_btn");

if (add_link_btns) {
	console.log("init add");
	for (var i = 0; i < add_link_btns.length; i += 1) {
		var add_link_btn = add_link_btns[i];
		add_link_btn.addEventListener("click", infiniteLinks.add);
	}
}
if (delete_link_btns) {
	console.log("init delete");
	for (var i = 0; i < delete_link_btns.length; i += 1) {
		var delete_link_btn = delete_link_btns[i];
		delete_link_btn.addEventListener("click", infiniteLinks.delete);
	}
}