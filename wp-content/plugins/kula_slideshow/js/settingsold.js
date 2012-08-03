$ = jQuery.noConflict();

$(document).ready(function(){
	$('#slideshow').kulaSlider({
		 slideEffect: "fade",
		 autoSlide: false,
		 tabsContainerID: "slideshowtab",
		 tabImage: new Array('/img/slidenav-image.png'),
		 tabImageCurrent: new Array('/img/slidenav-current.png'),
		 tabImageHover: new Array('/img/slidenav-hover.png'),
	});	
});			
