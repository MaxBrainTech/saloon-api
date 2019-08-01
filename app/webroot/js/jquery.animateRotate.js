$.fn.animateRotate = function(angle, duration, easing, complete) {
	
	var args = $.speed(duration, easing, complete), 
		step = args.step;
	
	return this.each(function(i, e){
		
		args.step = function(now){
			$.style(e, 'transform', 'rotate(' + now + 'deg)');
			if (step){ return step.apply(this, arguments); }
		};

		$({deg: 0}).animate({deg: angle}, args);
		
	});
	
};