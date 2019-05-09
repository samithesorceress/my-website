function addNewLink(e) {
	var trg = util.getTrg(e),
		field = null,
		list = null,
		num = null,
		newLink = document.createElement("li");
	
	field = trg.parentNode;
	list = util.getChildrenbyClassname(field, "links_list")[0];

	
	num = list.childNodes.length + 1;

	newLink.className = "field";
	newLink.innerHTML = "<div><label for='link_" + num + "_url'>Url</label><input id='link_" + num +  "_url' name='link_" + num + "_url' type='text'></div><div><label>Title</label><input id='link_" + num + "_title' name='link_"  + num + "_title' type='text'></div><button class='btn' type='button' onClick='removeLink()'><img src='http://127.0.0.1/sami-the-sorceress/src/icons/delete.svg' /></button>";
	list.appendChild(newLink);
}

function removeLink(e) {
	var trg = util.getTrg(e), li;
	li = trg.parentNode;
	li.parentNode.removeChild(li);
}