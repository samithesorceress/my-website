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
		console.log("checking val exists");
		var res = false,
			val = false;
		if (arr && key) {
			console.log("passed first check");
			if (Array.isArray(arr)) {
				console.log("passed second check");
				val = arr[key];
				if (val !== "undefined" && val !== false &&	val !== "") {
					console.log("passed third check");
					res = true;
				} else {
					console.log("failed third check");
				}
			} else {
				console.log("failed second check");
			}
		} else {
			console.log("failed first check");
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
			child.classList.remove("visible");
			util.spinner.timer = setTimeout(function () {
				parent.removeChild(child);
			}, 1E3);
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
			console.log("passed 1st conditional");
			for (var i = 0; i < required.length; i += 1) {
				var req = required[i];
				console.log("checking: " + req);
				if (!util.valExists(req, input)) {
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
	icon: function (src) {
		var svg = "<svg class='icon' height='24' width='24' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>";

		switch(src) {
			case "delete":
				svg += "<path fill='none' d='M0 0h24v24H0V0z'></path><path d='M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v10zM18 4h-2.5l-.71-.71c-.18-.18-.44-.29-.7-.29H9.91c-.26 0-.52.11-.7.29L8.5 4H6c-.55 0-1 .45-1 1s.45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1z'></path></svg>";
				return svg;
				break;
			case "add":
				svg.innerHTML = "add";
				break;
			default: 
				return false;
		}
		return false;
	}
}

util.spinner.remove("spinner_0", "main");