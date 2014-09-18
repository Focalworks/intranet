$( document ).ready(function() {
	menuSlider();
	var mainHeight = $(document).height(),
			menuWrapper = $('.menu-wrapper');
	menuWrapper.css('height',(mainHeight-58));
	
	$( window ).resize(function(){
		var mainHeight = $(document).height();
		menuWrapper.css('height',(mainHeight-58));
	});
	
});

// Menu Slider 
function menuSlider() {
	var menuButton = $('#menuButton'),
			menuWrapper = $('.menu-wrapper'),
			mainWrapper = $('.main-wrapper')
			subMenuLink = $('.menu-wrapper .menu-inner .menu .sub-menu'),
			subMenuWrapper = $('.menu-wrapper .menu-inner .menu .sub-menu .sub-menu-wrapper'),
			subMenuWrapperIcon = $('.menu-wrapper .menu-inner .menu .sub-menu .item-wrapper .fw-icons-left'); /* Plus & Minus icons */

	//Main Menu
	TriggerClick = 0;
	menuButton.click(function(){
		if(TriggerClick == 0){
			TriggerClick = 1;
			menuWrapper.addClass('menu-wrapper-normal').removeClass('menu-wrapper-active');
			mainWrapper.addClass('main-wrapper-normal').removeClass('main-wrapper-active');
			subMenuWrapper.addClass('sub-menu-normal').removeClass('sub-menu-active');
		} else {
			TriggerClick = 0;
			menuWrapper.addClass('menu-wrapper-active').removeClass('menu-wrapper-normal');
			mainWrapper.addClass('main-wrapper-active').removeClass('main-wrapper-normal');
			subMenuWrapper.addClass('sub-menu-active').removeClass('sub-menu-normal');
      subMenuTriggerClick=0; 												/* Sub Menu Slide */
      subMenuWrapper.hide(); 												/* Sub Menu Slide */
      subMenuLink.removeClass('sub-menu-open'); 		/* Sub Menu Slide */
      subMenuWrapperIcon.removeClass('glyphicon-minus').addClass('glyphicon-plus'); /* Plus & Minus icons */
		}
	});

	/* Sub Menu Slide */
	subMenuTriggerClick = 0;
	subMenuLink.click(function(){
		if(subMenuTriggerClick == 0){
			subMenuTriggerClick = 1;
			subMenuLink.addClass('sub-menu-open');
			subMenuWrapperIcon.addClass('glyphicon-minus').removeClass('glyphicon-plus'); /* Plus & Minus icons */
			subMenuWrapper.slideDown(100);
		} else{
			subMenuTriggerClick = 0;
			subMenuLink.removeClass('sub-menu-open');
			subMenuWrapperIcon.addClass('glyphicon-plus').removeClass('glyphicon-minus'); /* Plus & Minus icons */
			subMenuWrapper.slideUp(100);
		}

	});

}