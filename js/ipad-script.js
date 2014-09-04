	
	function switchOniPad() {
		$(".ipad-list").css({
			"opacity": "1"
		});
		$(".ipad-list li img").css({
			"width": "90px",
			"height": "90px"
		});
	}

	function switchOffiPad() {
		$(".ipad-list").css({
			"opacity": "0"
		});
		$(".ipad-list li img").css({
			"width": "0px",
			"height": "0px"
		});
	}
	
	function startApp(id) {
		$(".ipad .ipad-list").css({
			"display": "none",
		});
		$(".ipad .ipad-app").css({
			"display": "block",
			"opacity": "1"
		});
		
		setAppData(id);
	}
	
	function endApp() {
		var appLoading = "<br /><br /><br /><br />Loading...<br />";
		$(".ipad .ipad-list").show();
		$(".ipad .ipad-app").hide().html(appLoading).append("<a href=\"#events\" class=\"back-button\" onclick=\"endApp()\">&laquo;</a>");
	}
	
	function setAppData(id) {
		var url = "event-details.php?id="+id.substring(1, id.length);
		var elem = $(".ipad .ipad-app").load(url, function() {
				$(".ipad .ipad-app").append("<a href=\"#events\" class=\"back-button\" onclick=\"endApp()\">&laquo;</a>");
		});
	}