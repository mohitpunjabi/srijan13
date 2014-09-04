var curr = "#counter";

$(document).ready(function() {
	$("#home").click(function() { setCurrent("#counter", 40); });
	$("#about-srijan").click(function() { setCurrent("#srijan", 15); });
	$("#about-ism").click(function() { setCurrent("#ism", 15); });
	$("#event").click(function() { setCurrent("#events", 15); });
	$("#contact-us").click(function() { setCurrent("#contact", 15); });
	$("#past-sponsers").click(function() { setCurrent("#past", 15); });

	count();
	setInterval(count,1000);

	$(".background-show img").hide();
	theAmazingShow(0);
});

function setCurrent(id, top) {
	if(id != curr) {
		$(id).css('top', top+'%');
		$(curr).css('top', '102%');
		curr = id;
	}
}

function count() {
	var n=document.getElementById("counter");
	var d = new Date();
	var date = d.getDate();
	var month = d.getMonth();
	var year = d.getFullYear();
	var days;
	if(month == 11) {
		days = 31 + 31 + 28 + 14 - date;
	}
	else if(month == 0) days = 31 + 28 + 14 - date;
	else if(month == 1) days = 28 + 14 - date;
	else if(month == 2) days = 14 - date;
	else days = 0;
	if(days < 0) days = 0;
	var hours = d.getHours();
	var minutes = d.getMinutes();
	var sec = d.getSeconds();

	sec = 60 - sec;
	minutes = 59 - minutes;
	hours = 23 - hours;
	
	n.innerHTML = days + " days " + hours + " hours " + minutes + " minutes " + sec + " seconds to go";
}

$.ajax({url:"./image.php",success:function(result){
    $(".background-show").html(result);
  }});	



function theAmazingShow(i) {
	var curr = $(".background-show img");
	if(i == 0)
		$(curr[curr.length - 1]).fadeOut('slow', function() {
			$(curr[i]).fadeIn('slow');
		});
	else
		$(curr[i - 1]).fadeOut('slow', function() {
			$(curr[i]).fadeIn('slow');
		});

	

	
	setTimeout(function() {
		if(!curr[i + 1])
			theAmazingShow(0);
		else
			theAmazingShow(i + 1);
	}, 10000);
}