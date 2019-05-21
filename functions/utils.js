var htp_root = "http://127.0.0.1/sami-the-sorceress/";

var util = {
	api:  {
		req_queue: [],
		busy:  false,
		retry:  0,
		get: function (url, cb, args = false) {
			if (url.indexOf("http") < 0) {
				url = "http://127.0.0.1/sami-the-sorceress/api/" + url;
			}
			console.log("fetching: " + url);
			util.api.queue("GET", url, cb, args);
		},
    	request: function(method, url, cb, args) {
			var req = new XMLHttpRequest();
			console.log("api req:" + url);
			req.onreadystatechange = function() {
				if ((req.readyState === 4) && (req.status === 200)) {
					var res = req.responseText;
					if (res.indexOf("{") == 0) {
						res = JSON.parse(res);
					}
					
					cb(res, args);
					
					util.api.busy = false;
				}
				//} else {
					//if (util.api.retry < 3) {
					//	setTimeout(function(m,u,c,a) {
					//		util.api.request(m,u,c,a);
					//	}, util.api.retry * 150, method, url, cb, args);
					//	util.api.retry += 1;
					//} else {
						//cb(req.status + args);
					//	console.log("request failed");
					//	util.api.retry = 0;
					//	util.api.busy = false;
					//}
				//}
			}
			req.open(method, url, true);
			req.send();
		},
		upload: function (file, cb, args = false) {
			var req = new XMLHttpRequest();
			var formData = new FormData();
			console.log("processing in utils.js");
			console.log(file);
			req.onreadystatechange = function () {
				if ((req.readyState === 4) && (req.status === 200)) {
					console.log("connected");
					var res = req.responseText;
					if (res.indexOf("{") == 0) {
						res = JSON.parse(res);
					}
					cb(res, args);
				}
			}
			formData.append("file", file);
			req.open("POST", "http://127.0.0.1/sami-the-sorceress/api/uploadFile/index.php", true);
			req.send(formData);
		},
		queue: function(method, url, cb, args) {
			util.api.req_queue[url] = setInterval(function(m,u,c,a) {
				if (!util.api.busy) {
					clearInterval(util.api.req_queue[u]);
					delete util.api.req_queue[u];
					util.api.busy = true;
					util.api.request(m,u,c,a);
				}
			}, 99, method, url, cb, args);
		}
	},
	addStyle: function (elem, prop, val, vendors) {
		var i, ii, property, value
		if (!util.isElem(elem)) {
			elem = document.getElementById(elem)
		}
		if (util.isElem(elem)) {
			if (!util.isArray(prop)) {
				prop = [prop]
				val = [val]
			}
			for (i = 0; i < prop.length; i += 1) {
				var thisProp = String(prop[i]),
					thisVal = String(val[i])
				if (typeof vendors !== "undefined") {
					if (!util.isArray(vendors)) {
						vendors.toLowerCase() == "all" ? vendors = ["webkit", "moz", "ms", "o"] : vendors = [vendors]
					}
					for (ii = 0; ii < vendors.length; ii += 1) {
						elem.style[vendors[i] + thisProp] = thisVal
					}
				}
				thisProp = thisProp.charAt(0).toLowerCase() + thisProp.slice(1)
				elem.style[thisProp] = thisVal
			}
		}
	},
	cssLoaded: function (event) {
		var child = util.getTrg(event)
		child.setAttribute("media", "all")
	},
	events: {
		cancel: function (event) {
			util.events.prevent(event)
			util.events.stop(event)
		},
		prevent: function (event) {
			if (event && event !== "undefined" && typeof event !== "undefined") {
				event = event || window.event
				event.preventDefault()
			}
		},
		stop: function (event) {
			if (event && event !== "undefined" && typeof event !== "undefined") {
				event = event || window.event
				event.stopPropagation()
			}
		},
		listen: {
			active: 0,
			active_cb: false,
			timer: false,
			longPress: function (e, cb) {
				console.log("long press begun");
				util.events.cancel(e);
				var trg = util.getTrg(e);
				util.events.listen.active_cb = cb;
				util.events.listen.timer = setInterval(function (e) {
					if (util.events.listen.active > 10) {
						util.events.listen.endLongPress(e);
					} else {
						util.events.listen.active += 1;
					}
				}, 100, e);
				trg.addEventListener("touchend", util.events.listen.endLongPress);
			},
			endLongPress: function (e) {
				console.log("long press end");
				util.events.cancel(e);
				var trg = util.getTrg(e),
					a = false;
				trg.removeEventListener("touchend", util.events.listen.endLongPress);
				clearInterval(util.events.listen.timer);
				if (util.events.listen.active >= 10) {
					util.events.listen.active_cb(trg);
				} else {
					if (trg.tagName == "a") {
						a = trg;
					} else {
						a = trg.getElementsByTagName("a")[0];
					}
					window.location.href = a.href;
				}
				util.events.listen.active = 0;
			}
		}
	},
	getChildrenbyClassname: function (parent, classname) {
		var validParent = false,
			children = [];
		if (util.isElem(parent)) {
			validParent = true;
		} else {
			parent = document.getElementById(parent);
			if (util.isElem(parent)) {
				validParent = false;
			} else {
				return false;
			}
		}
		if (validParent) {
			for (var i = 0; i < parent.childNodes.length; i++) {
				var child = parent.childNodes[i];
				if (child.classList.contains(classname)) {
					children.push(child);
				}
			}
		}
		if (children.length) {
			return children;
		}
		return false;
	},
	getSize: function (elem, prop) {
		if (!util.isElem(elem)) {
			elem = document.getElementById(elem)
		}
		var size
		typeof prop !== "undefined" ? size = parseInt(elem.getBoundingClientRect()[prop], 10) : size = elem.getBoundingClientRect()
		return size
	},
	getTrg: function (event) {
		event = event || window.event
		if (event.srcElement) {
			return event.srcElement
		} else {
			return event.target
		}
	},
	isElem: function (elem) {
		return (util.isNode(elem) && elem.nodeType == 1)
	},
	isArray: function(v) {
		return (v.constructor === Array)
	},
	isNode: function(elem) {
		return (typeof Node === "object" ? elem instanceof Node : elem && typeof elem === "object" && typeof elem.nodeType === "number" && typeof elem.nodeName==="string" && elem.nodeType !== 3)
	},
	isNumeric: function(n) {
		return !isNaN(parseFloat(n)) && isFinite(n)
	},
	isObj: function (v) {
		return (typeof v == "object")
	},
	makeID: function (len) {
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		
		for (var i = 0; i < len; i++)
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		
		return text;

	},
	replaceAll: function(target, search, replacement) {
		return target.split(search).join(replacement)
	},
	replaceAt: function(str, index, char) {
		return str.substr(0, index) + char + str.substr(index + char.length);
	},
	spinner: {
		timer: false,
		add: function (parent) {

		},
		remove: function (child, parent) {
			if (!util.isElem(child)) { 
				child = document.getElementById(child);
			}
			if (!util.isElem(parent)) {
				parent = document.getElementById(parent);
			}
			child.classList.remove("visible");
			util.spinner.timer = setTimeout(function () {
				parent.removeChild(child);
			}, 1E3);
		}
	},
	dialog: {
		timer: false,
		add: function (type, msg, parent, cb, args = false) {
			console.log("spawning dialog box");
			var id = util.makeID(6),
				wrapper = document.createElement("div"),
				ctas = document.createElement("div"),
				shadow = document.createElement("div"),
				btns = [];
			wrapper.className = "dialog visible";
			wrapper.id = "dialog_" + id;
			ctas.className = "ctas";
			shadow.className = "popup_shadow visible";
			shadow.id = "shadow_" + id;
			shadow.addEventListener("click", function () {
				util.dialog.remove(event, false, cb, args);
			}, false);
			switch(type) {
				case "confirmation":
					wrapper.innerHTML = "<h3>Confirmation</h3><p>" + msg + "</p>";
					btns[0] = document.createElement("button");
					btns[0].type = "button";
					btns[0].className = "btn cta sml";
					btns[0].dataset.key = id;
					btns[0].innerHTML = "<span>Cancel</span>";
					btns[0].addEventListener("click", function () {
						util.dialog.remove(event, false, cb, args);
					}, false);

					btns[1] = document.createElement("button");
					btns[1].type = "button";
					btns[1].className = "btn cta sml danger";
					btns[1].dataset.key = id;
					btns[1].innerHTML = "<span>Confirm</span>";
					btns[1].addEventListener("click", function () {
						util.dialog.remove(event, true, cb, args);
					}, false);
				break;
			}
			for(var i = 0; i < btns.length; i += 1) {
				btn = btns[i];
				ctas.appendChild(btn);
			}
			wrapper.appendChild(ctas);
			if (parent) {
				if (!util.isElem(parent)) {
					parent = document.getElementById(parent);
				}
			} else {
				parent = document.getElementById("main");
			}
			parent.appendChild(shadow);
			parent.appendChild(wrapper);
			
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
			util.dialog.timer = setTimeout(function (d,s,c,r,a) {
				d.parentNode.removeChild(d);
				s.parentNode.removeChild(s);
			}, 4E2, dialog, shadow);
			if (cb) {
				cb(res, args);
			}
		}
	}
}

util.spinner.remove("spinner_0", "main");