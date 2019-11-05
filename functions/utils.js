var htp_root = "http://127.0.0.1/sami-the-sorceress/",
	admin_root = htp_root + "admin/",
	media_root = htp_root + "uploads/";

var util = {
	xhrFetch: function (endpoint, params, cb, args = false) {
		var req = new XMLHttpRequest(),
			formData = new FormData(),
			url = "";
			if (endpoint.indexOf("http") < 0) {
				url = "http://127.0.0.1/sami-the-sorceress/api/" + endpoint;
				if (endpoint.indexOf("upload") > -1) {
					url += "/index.php";
				}
			} else {
				url = endpoint;
			}
		if (params && Array.isArray(params)) {
			for (var key in params) {
				var val = params[key];
				formData.append(key, val);
			}
		}
		console.log("xhr req:" + url);
		console.log("sending data..");
		// Display the key/value pairs
		for (var pair of formData.entries()) {
			console.log(pair[0]+ ', ' + pair[1]); 
		}
		req.onreadystatechange = function() {
			if ((req.readyState === 4) && (req.status === 200)) {
				var res = req.responseText;
				console.log("xhr res: " + res);
				if (res.indexOf("{") == 0) {
					res = JSON.parse(res);
				}
				if (cb) {
					if (args) {
						cb(res, args);
					} else {
						cb(res);
					}
				} else {
					return res;
				}
			}
		}
		//if (endpoint == "get-contents") {
		//	console.log("using GET method");
		//	req.open("GET", url, true);
		//} else {
			console.log("using POST method");
			req.open("POST", url, true);
		//}
		req.send(formData);
	},
	api: {
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
		upload: function (file, cb, args = false, updateId = false) {
			var req = new XMLHttpRequest(),
				formData = new FormData(),
				api_url = htp_root + "api/uploadFile/index.php";
			if (updateId) {
				formData.append("update_id", updateId);
				console.log("updating file at api: " + updateId);
			} else {
				console.log("uploading new file at api");
			}
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
			
			console.log("api req:" + api_url);

			formData.append("file", file);
			//console.dir(formData);

			req.open("POST", api_url, true);
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
	valExists: function (key, arr) {
		var res = false,
			val;
		if (arr &&
			key &&
			Array.isArray(arr)
			) {
				
			val = arr[key];
			if (
				val !== "undefined" &&
				val !== false &&
				val !== null &&
				val !== ""
				) {
				res = true;
			}
		}
		return res;
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
			if (util.isElem(child)) {
				child.classList.remove("visible");
				util.spinner.timer = setTimeout(function () {
					parent.removeChild(child);
				}, 1E3);
			}
		}
	},
	checkRequired: function (required, input) {
		var res = {
			"success": false,
			"missing": []
		};

		if (
			required !== false && 
			input !== false && 
			Array.isArray(required) && 
			Array.isArray(input)
		) {
			console.log("arguments are prepared correctly");
			for (var i = 0; i < required.length; i += 1) {
				var req = required[i];
				console.log("checking: " + req);
				if (!util.valExists(req, input) || input[req] == false) {
					res["missing"].push((req));
					console.log("missing: " + req);
				} else {

					console.log("val exists");
				}
			}
			console.log("mising totoal: " + res["missing"].length);
			if (res["missing"].length < 1) {
				console.log("missing none!!");
				res["success"] = true;
			}
		}

		return res;
	},
	formatLinks: function (inputs) {
		var links = [],
			json = false;
		for (var key in inputs) {
			var value = inputs[key],
				id = false;
			if (key.includes("link")) {
				if (key.includes("store")) {
					id = key.split("_")[6];
				} else {
					id = key.split("_")[5];
				}
				if (!links[id]) {
					links[id] = {};
				}
				if(key.includes("url")) {
					links[id]["url"] = encodeURIComponent(value);
				} else {
					links[id]["title"] = encodeURIComponent(value);
				}
			}
		}
		console.log("links", links);
		if (links) {
			var json = {},
				i = 0;
			for (var key in links) {
				json[i] = links[key];
				i += 1;
			}
			console.log("links", links);
			json = JSON.stringify(json);
		}
		return json;
	},
	icon: function (src) {
		var svg = "<svg class='icon";
		if (src.includes("unchecked")) {
			svg += " unchecked";
		} else if (src.includes("checked")) {
			svg += " checked";
		}
		svg += "' height='24' width='24' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>";

		switch(src) {
			case "delete":
				svg += "<path fill='none' d='M0 0h24v24H0V0z'></path><path d='M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v10zM18 4h-2.5l-.71-.71c-.18-.18-.44-.29-.7-.29H9.91c-.26 0-.52.11-.7.29L8.5 4H6c-.55 0-1 .45-1 1s.45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1z'></path></svg>";
				return svg;
				break;
			case "checkbox_checked":
				svg += "<path fill='none' d='M0 0h24v24H0V0z'/><path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM9.29 16.29L5.7 12.7c-.39-.39-.39-1.02 0-1.41.39-.39 1.02-.39 1.41 0L10 14.17l6.88-6.88c.39-.39 1.02-.39 1.41 0 .39.39.39 1.02 0 1.41l-7.59 7.59c-.38.39-1.02.39-1.41 0z'/></svg>";
				return svg;
				break;
			case "checkbox_unchecked":
				svg += "<path fill='none' d='M0 0h24v24H0V0z'/><path d='M18 19H6c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h12c.55 0 1 .45 1 1v12c0 .55-.45 1-1 1zm1-16H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z'/></svg>";
				return svg;
				break;
			case "add":
				svg.innerHTML = "add";
				break;
			default: 
				return false;
		}
		return false;
	},
	mediaContainer: function (obj, shape, title) {
		var html = false,
			api_endpoint = "list/media",
			api_params = [],
			id = util.makeID(6);
		console.log("media container", obj);
		if (obj) {
			// init wrapper
			html = "<div id='media_container_" + id + "' class='media_container";
			if (shape) {
				switch(shape) {
					case "wide":
						html += " wide_container";
						break;
					case "tall":
						html += " wide_container";
						break;
					case "round":
						html += " round_container";
						break;
					case "hd":
						$html += " hd_container";
				}
				html += "' dataset-shape='" + shape;
			}
			html += "'>";
			// slides/videos title
			if (title) {
				html += "<div class='media_title'><span>" + title + "</span></div>";
			}
			if (!util.valExists("id", obj)) {
				api_params["id"] = obj["id"];
				util.xhrFetch(api_endpoint, api_params, util.populateMediaContainer, "media_container_" + id);
			} else {
				util.populateMediaContainer(obj, "media_container_" + id);
			}
			html += "</div>";
		}
		return html;
	},
	populateMediaContainer: function (res, id) {
		var media_root = "http://127.0.0.1/sami-the-sorceress/uploads/",
			trg = document.getElementById(id),
			obj = res,
			elem,
			shape = trg.dataset.shape,
			sizes,
			srcset = "",
			container = document.createElement("div");

		container.className = "media_contents";
		if (obj["success"] === true) {
			obj = obj["data"];
		}
		console.log("populating", obj);
		if (typeof(obj["type"]) !== "undefined") {
			switch (obj["type"]) {
				case "image":
					elem = document.createElement("img");
					elem.src = media_root + obj["src"] + "." + obj["ext"];

					if (typeof(obj["sizes"]) !== "undefined" && obj["sizes"] !== false) {
						sizes = parseInt(obj["sizes"]);
						for (var i = 0; i < sizes; i += 1) {
							var size = (i + 1) * 200;
							srcset += media_root + obj["src"] + "_" + size + "w." + obj["ext"] + " " + size + "w, ";
						}
						srcset = srcset.replace(/, +$/,'');
						elem.srcset = srcset;
					}
					break;
				case "video":
					elem = document.createElement("video");
					elem.src = media_root + obj["src"] + "." + obj["ext"];
					break;
			}
			ratio = 1;
			if (shape) {
				switch(shape) {
					case "wide":
						ratio = 2.16;
						break;
					case "tall":
						ratio = .6;
						break;
				}
			}
			if (obj["ratio"] < ratio) {
				elem.dataset.shape = "wide";
			} else {
				elem.dataset.shape = "tall";
			}
		} else {
			elem = document.createElement("img");
			elem.src = "http://127.0.0.1/sami-the-sorceress/src/imgs/placeholder.png";
			elem.alt = "Placeholder image for missing file.";
			elem.title = "Missing file.";
			elem.dataset.shape = "wide";
		}
		elem.setAttribute("loading", "lazy");
		container.appendChild(elem);
		trg.appendChild(container);
	}
}

document.body.onload = setTimeout(function () {
	util.spinner.remove("spinner_0", "main");
}, 5E2);