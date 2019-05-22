function addNewLink(e) {
	util.events.cancel(e);
	var field = document.getElementById("infinite_link_fields"),
		list = util.getChildrenbyClassname(field, "links_list")[0],
		list_items = list.children,
		num = null,
		newLink = document.createElement("li");

	
	num = list_items.length;

	newLink.className = "field";
	newLink.innerHTML = "<div><label for='link_url_" + num + "'>Url</label><input id='link_url_" + num +  "' name='link_url_" + num +  "' type='text'></div><div><label>Title</label><input id='link_title_" + num +  "' name='link_title_"  + num + "' type='text'></div><button class='btn' type='button' onClick='removeLink()'><img src='" + htp_root + "src/icons/delete.svg' class='icon' /></button>";
	list.appendChild(newLink);
}

function removeLink(e) {
	var trg = util.getTrg(e), li;
	li = trg.parentNode;
	li.parentNode.removeChild(li);
}