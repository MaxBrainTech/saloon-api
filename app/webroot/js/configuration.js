$(document).ready(function(){
		$(".Navi.NaviMobile").hide();
		$(".NaviMobileIcon").click(function(){
			$(".Navi.NaviMobile").toggle("MenuShow");

		});
			
		$("#home .RightMobile").fadeIn('slow').animate({
		            'left': '660px'
		            }, {duration: 'slow', queue: false}, function() {
		            // Animation complete.
		        });
				$("#home .LeftMobile").fadeIn('slow').animate({
		            'left': '111px'
		            }, {duration: 'slow', queue: false}, function() {
		            // Animation complete.
		        });
				$("#home .iphoneMobile").fadeIn('slow').animate({
		            'top': '0px'
		            }, {duration: 'slow', queue: false}, function() {
		            // Animation complete.
		});
		$(window).bind('scroll', function() {
			   var navHeight = $( window ).height()  +970;
					 if ($(window).scrollTop() > navHeight) {
						 $('.FeaListWrp').addClass('fixed');
					 }
					 else {
						 $('.FeaListWrp').removeClass('fixed');
					 }
		});
		

});