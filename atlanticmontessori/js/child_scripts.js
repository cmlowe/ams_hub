$ = jQuery.noConflict();

$(function(){
	$('.hero').kulaSlider({
		 slideEffect: "fade",
		 nextControlID: "next",
		 prevControlID: "prev",
		 autoSlide: false
	});
});
