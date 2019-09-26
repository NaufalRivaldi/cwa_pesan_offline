$(function(){
	$(".notif").click(function(){
		$(this).next(".notif-container").slideToggle();
		$(this).children(".num").hide();
	});
	$(".first").click(function(){
		$(".submenu, .showmenu").slideUp();
		$(this).next(".submenu, .showmenu").slideToggle();

	});
	$(".box-error, .box-success, .box-warning, .box-information").click(function(){
		$(this).slideToggle();
	});

	
});
