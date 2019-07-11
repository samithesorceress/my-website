/*echo "";
*/

var slideshow = {
	wrapper: false,
	slides_container: false,
	slides: false,
	total_slides: 1,
	current_slide: 1,
	progress_bar: false,
	icons: {
		arrow_left: "<svg class='icon' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path fill='none' d='M0 0h24v24H0V0z'/><path d='M14.71 6.71c-.39-.39-1.02-.39-1.41 0L8.71 11.3c-.39.39-.39 1.02 0 1.41l4.59 4.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L10.83 12l3.88-3.88c.39-.39.38-1.03 0-1.41z'/></svg>",
		arrow_right: "<svg class='icon' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path fill='none' d='M0 0h24v24H0V0z'/><path d='M9.29 6.71c-.39.39-.39 1.02 0 1.41L13.17 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.7 6.7c-.38-.38-1.02-.38-1.41.01z'/></svg>"
	},
	init: function () {
		slideshow.wrapper = document.getElementById("slideshow");
		slideshow.slides_container = slideshow.wrapper.getElementsByClassName("slides_container")[0];
		slideshow.slides = slideshow.slides_container.children;
		slideshow.total_slides = slideshow.slides.length;
		slideshow.progress_bar = slideshow.wrapper.getElementsByClassName("progress_bar")[0];
		
		nav = document.createElement("ul");
		nav.className = "slides_nav";
		li = document.createElement("li");
		prev_btn = document.createElement("button");
		prev_btn.className = "prev_slide_btn btn slide_arrow";
		prev_btn.type = "button";
		prev_btn.innerHTML = slideshow.icons.arrow_left;
		prev_btn.addEventListener("click", slideshow.nav.prevBtn);
		li.appendChild(prev_btn);
		nav.appendChild(li);
		li = document.createElement("li");
		next_btn = document.createElement("button");
		next_btn.className = "next_slide_btn btn slide_arrow";
		next_btn.type = "button";
		next_btn.innerHTML = slideshow.icons.arrow_right;
		next_btn.addEventListener("click", slideshow.nav.nextBtn);
		li.appendChild(next_btn);
		nav.appendChild(li);
		slideshow.wrapper.prepend(nav);
		slideshow.queue.reset();
	},
	change: {
		advance: function () {
			console.log("advancing slides");
			slideshow.progress_bar.style.transitionProperty = "none";
			slideshow.progress_bar.style.transform = "scale(0)";
			setTimeout(function (pb) {
				pb.style.transitionProperty = "transform";
			}, 10, slideshow.progress_bar);

			for(var i = 0; i < slideshow.slides.length; i += 1) {
				slide = slideshow.slides[i];
				slide_text = slide.getElementsByClassName("slide_text")[0];
				slide.style.transitionProperty = "none";
				slide.style.left = 0;
				slide.style.transform = "translateX(0)";
				if (i + 1 == slideshow.current_slide) {
					slide.style.zIndex = 2;
				} else {
					slide.style.zIndex = 1;
					slide_text.style.transitionProperty = "none";
				}
				slide_text.style.opacity = 0;
				setTimeout(function (s, st) {
					s.style.transitionProperty = "transform";
					st.style.transitionProperty = "opacity";
				}, 10, slide, slide_text);
			}

			slide_width = slideshow.wrapper.getBoundingClientRect().width;
			next_slide = slideshow.current_slide + 1;

			if (next_slide > slideshow.total_slides) {
				next_slide = 1;
			}
			slideshow.current_slide = next_slide;
			next_slide = slideshow.slides[next_slide - 1];
			next_slide.style.left = slide_width + "px";
			next_slide.style.zIndex = 3;
			next_slide_text = next_slide.getElementsByClassName("slide_text")[0];
			setTimeout(function (ns, w, pb) {
				ns.style.transform = "translateX(-" + w + "px)";
				pb.style.transform = "scale(200)";
			}, 20, next_slide, slide_width, slideshow.progress_bar);
			setTimeout(function (nst) {
				nst.style.opacity = 1;
			}, 666, next_slide_text)
		},
		reverse: function () {
			console.log("reversing slides");
			for(var i = 0; i < slideshow.slides.length; i += 1) {
				slide = slideshow.slides[i];
				slide_text = slide.getElementsByClassName("slide_text")[0];
				slide.style.transitionProperty = "none";
				slide.style.left = 0;
				slide.style.transform = "translateX(0)";
				if (i + 1 == slideshow.current_slide) {
					slide.style.zIndex = 2;
				} else {
					slide.style.zIndex = 1;
					slide_text.style.transitionProperty = "none";
				}
				slide_text.style.opacity = 0;
				setTimeout(function (s, st) {
					s.style.transitionProperty = "transform";
					st.style.transitionProperty = "opacity";
				}, 10, slide, slide_text);
			}
			slide_width = slideshow.wrapper.getBoundingClientRect().width;
			next_slide = slideshow.current_slide - 1;
			if (next_slide < 1) {
				next_slide = slideshow.total_slides;
			}
			last_slide = slideshow.slides[slideshow.current_slide - 1];
			slideshow.current_slide = next_slide;
			next_slide = slideshow.slides[next_slide - 1];
			last_slide.style.zIndex = 3;
			next_slide.style.zIndex = 2;
			next_slide_text = next_slide.getElementsByClassName("slide_text")[0];
			setTimeout(function (ls, w) {
				ls.style.transform = "translateX(" + w + "px)";
			}, 20, last_slide, slide_width);
			setTimeout(function (nst) {
				nst.style.opacity = 1;
			}, 666, next_slide_text)
		}
	},
	nav: {
		prevBtn: function (e) {
			util.events.cancel(e);
			slideshow.queue.reset();
			slideshow.change.reverse();
		},
		nextBtn: function (e) {
			util.events.cancel(e);
			slideshow.queue.reset();
			slideshow.change.advance();
		}
	},
	queue: {
		timer: false,
		reset: function () {
			clearInterval(slideshow.queue.timer);
			slideshow.progress_bar.style.transitionProperty = "none";
			slideshow.progress_bar.style.transform = "scale(0)";
			setTimeout(function () {
				slideshow.progress_bar.style.transitionProperty = "transform";
			}, 10);
			setTimeout(function () {
				slideshow.progress_bar.style.transform = "scale(200)";
				slideshow.queue.timer = setInterval(slideshow.change.advance, 5e3);
			}, 20);
		}
	}
}
slideshow.init();