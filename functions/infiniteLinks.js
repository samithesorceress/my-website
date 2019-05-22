function addNewLink(e) {
	util.events.cancel(e);
	var field = document.getElementById("infinite_link_fields"),
		list = util.getChildrenbyClassname(field, "links_list")[0],
		list_items = list.children,
		num = null,
		newLink = document.createElement("li");

	
	num = list_items.length + 1;

	newLink.className = "field";
	newLink.innerHTML = "<div><label for='link_" + num + "_url'>Url</label><input id='link_" + num +  "_url' name='link_" + num + "_url' type='text'></div><div><label>Title</label><input id='link_" + num + "_title' name='link_"  + num + "_title' type='text'></div><button class='btn' type='button' onClick='removeLink()'><img src='" + htp_root + "src/icons/delete.svg' class='icon' /></button>";
	list.appendChild(newLink);
}

function removeLink(e) {
	var trg = util.getTrg(e), li;
	li = trg.parentNode;
	li.parentNode.removeChild(li);
}