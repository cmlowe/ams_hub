$ = jQuery.noConflict();

$(document).ready(function(){
	$('#slideshow').kulaSlider({
		 tabsContainer: '#slideshowtab',
		 tabImage: new Array("<img style='margin-right:1px' src='images/slide2_nav_normal.png'/>", "<img style='margin-right:1px' src='images/slide_nav_normal.png' />"),
		 tabImageHover: new Array("<img style='margin-right:1px' src='images/slide2_nav_hover.png'/>", "<img style='margin-right:1px' src='images/slide_nav_hover.png'/>"),
		 tabImageCurrent: new Array("<img style='margin-right:1px' src='images/slide2_nav_current.png'/>", "<img style='margin-right:1px' src='images/slide_nav_current.png'/>"),
		 slideEffect: "fade",
		 tabChangeOn: "hover",
		 //autoSlide: false,
		 nextControlClass: "your_nextControlClass",
		 prevControlClass: "your_prevControlClass"
	})
});			