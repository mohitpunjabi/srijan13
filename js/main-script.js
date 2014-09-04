	var currentFrameId = getQueryString();
	var callbacks = Array();
	var onLeaves = Array();
	
	callbacks["#home"] = function() {
		$(".social-buttons").fadeIn('fast');
	};
	
	callbacks["#starnights"] =  null;
	callbacks["#workshops"] = null;
	callbacks["#events"] = switchOniPad;

	onLeaves["#events"] = switchOffiPad;
	onLeaves["#home"] = function() {
		$(".social-buttons").fadeOut('fast');
	}; 



	$(document).ready(function () {
		$("body").queryLoader2();
		$(window).trigger('resize');
		$("div.container, div.object").each(function() {
			$(this).css({
				"top": $(this).attr("data-top")+"px",
				"left": $(this).attr("data-left")+"px"
			});
		});
		
		$("a.table-link").each(function() {
			$(this).click(function() {
				var id = $(this).attr("href");
				$("ul.nav-menu li.current").attr("class", "");
				$(this).parent().attr("class", "current");
				goto(id);
			});
		});
		
		$("a.table-link[href='" + currentFrameId + "']").click();
		
		$(".ipad .ipad-list .ipad-folder a").click(function() {
			var which = $(this).parent().attr("id");
			var contents = $(".ipad .ipad-list .ipad-folder-contents");
			if(parseInt(contents.css("height")) == 0) {
				var ht = 140,
					opVal = 0.2;
				if (which == "art") {
					ht = 250;
					opVal = 0;
				}
				contents.css({
					"height": ht+"px",
					"border-top": "3px rgba(255, 255, 255, 0.3) solid",
					"border-bottom": "3px rgba(255, 255, 255, 0.3) solid"
				});
				$(".ipad .ipad-list li").each(function() {
					if($(this).attr("id") != which && $(this).attr("class") != "ipad-folder-contents") {
						if($(this).attr("id") == "online" || $(this).attr("id") == "quiz")
							$(this).css("opacity", ""+opVal);
						else
							$(this).css("opacity", "0.2");
					}
				});

				$(".ipad .ipad-list .ipad-folder-contents .ipad-folder-data").css({"display": "none"});
				$(".ipad .ipad-list .ipad-folder-contents .ipad-folder-data[for='"+which+"']").css({"display": "block"});
			}
			else {
				contents.css({
					"height": "0px",
					"border": "0"
				});
				$(".ipad .ipad-list li").css("opacity", "1");
			}
		});
		
		$(".ipad div#ipad-button").click(function() {
			endApp();
		});

		$(".nav-menu").mouseover(function() {
			var cl = $(this).attr("class");
			if(cl == "nav-menu hidden")
				$(this).attr("class", "nav-menu visible"); 
		});

		$(".nav-menu").mouseout(function() {
			var cl = $(this).attr("class");
			if(cl == "nav-menu visible")
				$(this).attr("class", "nav-menu hidden"); 
		});
	
		$(".ipad .ipad-list .ipad-app-icon a").click(function() {
			startApp($(this).attr("href"));
		});

		$(".contact-list").jScrollPane();
	});
	
	$(window).resize(function() {
		goto(currentFrameId);
	});
	
	function goto(id) {
		if(id != currentFrameId)	onLeave(currentFrameId);
		
		var container = $("div"+id+".container");
		var leftG = parseInt(container.css("left"));
		var topG = parseInt(container.css("top"));
		var offsetX = (container.width() - $(window).width())/2;
		var offsetY = (container.height() - $(window).height())/2;
		
		$(".table").css({
			"top": (-topG-offsetY)+"px",
			"left": (-leftG-offsetX)+"px"
		});
		
		currentFrameId = id;
		setTimeout(function() {
			callback(id)
		}, 1000);
	}
	
	function callback(id) {
		if(typeof(callbacks[id]) == "function")	callbacks[id]();
	}
	
	function onLeave(id) {
		if(typeof(onLeaves[id]) == 'function')	onLeaves[id]();
	}

	function hideNavMenu() {
		$(".nav-menu.visible").attr("class", "nav-menu hidden");
	}
	
	function getQueryString() {
		// get the url and parse it
		return "#home";
	}