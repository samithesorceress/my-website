var dialogBox = {
	timer: false,
	add: function (type, msg, cb, args = false) {
		console.log("spawning dialog box");
		var id = util.makeID(6),
			wrapper = document.createElement("div"),
			dialog = document.createElement("div"),
			ctas = document.createElement("div"),
			shadow = document.createElement("div"),
			btns = [];
		wrapper.className = "dialog_wrapper";
		dialog.className = "dialog visible";
		dialog.id = "dialog_" + id;
		ctas.className = "ctas";
		shadow.className = "shadow visible";
		shadow.id = "shadow_" + id;
		shadow.addEventListener("click", function () {
			dialogBox.remove(event, false, cb, args);
		}, false);
		switch(type) {
			case "confirmation":
				dialog.innerHTML = "<h3>Confirmation</h3><p>" + msg + "</p>";
				btns[0] = document.createElement("button");
				btns[0].type = "button";
				btns[0].className = "btn cta sml";
				btns[0].dataset.key = id;
				btns[0].innerHTML = "<span>Cancel</span>";
				btns[0].addEventListener("click", function () {
					dialogBox.remove(event, false, cb, args);
				}, false);

				btns[1] = document.createElement("button");
				btns[1].type = "button";
				btns[1].className = "btn cta sml danger";
				btns[1].dataset.key = id;
				btns[1].innerHTML = "<span>Confirm</span>";
				btns[1].addEventListener("click", function () {
					dialogBox.remove(event, true, cb, args);
				}, false);
			break;
		}
		for(var i = 0; i < btns.length; i += 1) {
			btn = btns[i];
			ctas.appendChild(btn);
		}
		dialog.appendChild(ctas);
		wrapper.appendChild(dialog);
		document.body.appendChild(dialog);
		//parent.appendChild(shadow);
		
	},
	remove: function (e, res, cb, args) {
		console.log(e);
		var trg = util.getTrg(e),
			key = false,
			dialog = false,
			shadow = false;
		if (trg.tagName == "BUTTON") {
			key = trg.dataset.key;
		} else {
			key = trg.id.replace("shadow_", "");
		}
		dialog = document.getElementById("dialog_" + key);
		shadow = document.getElementById("shadow_" + key);
		dialog.classList.remove("visible");
		shadow.classList.remove("visible");
		dialogBox.timer = setTimeout(function (d,s) {
			d.parentNode.removeChild(d);
			s.parentNode.removeChild(s);
		}, 4E2, dialog, shadow);
		if (cb) {
			cb(res, args);
		}
	}
}