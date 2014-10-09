$( document ).ready(function() {
	boxPosition();
	$(window).resize(function(){
	  boxPosition();
	});
});

$(window).load(function() {
	$('.fw-overly').delay(500).fadeIn(500);
	boxAnimation();
});

// Box Animation
function boxAnimation() {
	var animateBox = $('.user-login-wrapper');
	animateBox.delay(1000).animate({
		'left': '60%'
	}, 600, function(){
		animateBox.animate({
			'left': '48%'
		}, function(){
			animateBox.animate({
				'left': '50%'
			}, 200)
		})
	});
}

// Box Position
function boxPosition() {
	var mainWidth = $(document).width();
	var mainHeight = $(document).height();
	var screenPosition = mainHeight / 2 - 160;
	$('.fw-overly').css({
		'width': mainWidth,
		'height': mainHeight
	})
	$('.user-login-wrapper').css('top', screenPosition);
}