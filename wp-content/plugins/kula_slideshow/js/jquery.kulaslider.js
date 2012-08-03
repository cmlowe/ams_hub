/*
Name: Kula Slider
Description: A method that is added to the JQuery framework which runs a custom slide
show. This can be used for one slideshow or many slideshows of the same configuration.
For more information on how to initialize and use the Kula Slider, read the API at
http://kulaslider.brighthost.ca.
Author: Silas Neilsen
Additions: William Burns & Faisal Kabir
Last Updated: April 11, 2012
*/

(function($){
$.fn.kulaSlider = function(options) {
	//Setting the default values 
	var defaults = {
		this_selector: false,
		slideClass: "slide",
		prevControlClass: "",
		nextControlClass: "",
		tabsContainer: "",
		tabImage: new Array(),
		tabImageCurrent: new Array(),
		tabImageHover: new Array(),
		tabImageActive: new Array(),
		slideTimer: 5000,
		slideEffect: "snap",
		autoSlide: true,
		tabChangeOn: "click",
		tabClickLink: new Array(),
		startingSlide: 0
	};
	//Merging the default values with the values set by the user during initialization
	var options = $.extend(defaults, options);
	
	//Declaring private variables
	var currentSlideshow2 = "";
	var autoTimer;
	var slideCount = 0;
	var counter = 0;
	var previous = 0;
	
	//Function to be run for the slideshow(s)
	return this.each(function(){
		if (!(options.this_selector)) {
			if (!$(this).attr('id')) {
				options.this_selector = $(this).attr('class');
			} else {
				options.this_selector = '#' + $(this).attr('id');
			}	
		}
		
		//Increments the slideCount to give a total number of slides with the same tabsContainer
		slideCount ++;
		
		//Assigning slideShow variable to be the current slideshow and setting it's attributes
		var slideShow = $(this);
		var showLength = slideShow.find('.'+options.slideClass).length;
		$(this).attr('slideCount', slideCount);
		$(this).attr('slideNumber', 0);

		//Show the first slide in the slideshow and hiding all of the others.
		slideShow.find('.'+options.slideClass).css('display','none');
		$(slideShow.find('.'+options.slideClass)[options.startingSlide]).css('display', 'block');
		
		//Setting the opacity for all the slides if the slideEffect is set to 'crossfade'
		if(options.slideEffect == "crossfade"){
			slideShow.find('.'+options.slideClass).css('opacity','0');
			$(slideShow.find('.'+options.slideClass)[options.startingSlide]).css('opacity', '1');
			slideShow.find('.'+options.slideClass).each(function(){
				//IE Fix
				if(navigator.userAgent.search(/msie/i)!= -1) { 
					this.style.removeAttribute("filter");
				}
			});
		}

		//Checks to see which tabs for each navigation state (normal, hover and active) have been set
		if(options.tabsContainer){
			if(!options.tabImageHover || options.tabImageHover == ""){
				options.tabImageHover = options.tabImage;
			}
			if(!options.tabImageCurrent || options.tabImageCurrent == ""){
				options.tabImageCurrent = options.tabImageHover;
			}
			if(!options.tabImageActive || options.tabImageActive == ""){
				options.tabImageActive = options.tabImageCurrent;
			}
		}
		
		//Set up navigation tabs if the tabContainerID is set
		if(options.tabsContainer && options.tabsContainer != ""){
			for(var i=0; i<showLength; i++){
				var tabImage;
				
				if(i>=options.tabImage.length){
					tabImage = options.tabImage[i-(options.tabImage.length * Math.floor(i/options.tabImage.length))];
					tabImageCurrent = options.tabImageCurrent[i-(options.tabImage.length * Math.floor(i/options.tabImage.length))];
				}else{
					tabImage = options.tabImage[i];
					tabImageCurrent = options.tabImageCurrent[i];
				}

				if(i==options.startingSlide){
					$(this).find(options.tabsContainer).append(tabImageCurrent);					
					$($(this).find(options.tabsContainer).children()[i]).addClass('kulaSliderTab');
					$($(this).find(options.tabsContainer).children()[i]).mouseenter(onHover);
				}else{
					$(this).find(options.tabsContainer).append(tabImage);
					$($(this).find(options.tabsContainer).children()[i]).addClass('kulaSliderTab');
					$($(this).find(options.tabsContainer).children()[i]).mouseenter(onHover);
					
				}
				counter++;
			}
		}
		
		//Function to be run when navigation tab is moused over
		function onHover() {
			var myID = slideShow.attr('slideNumber');
			var index = $(this).index();
			//Function to run of the tabChangeOn is set to 'hover'
			if(options.tabChangeOn == 'hover'){
				if(myID != index) {
					gotoSlide(index);
					$(slideShow).attr('slideNumber', index);
				}
			}else{
				//Change the image to the hover image
				if(myID != $(this).index()){
					$($(this).parent().children()[$(this).index()]).after(options.tabImageHover[$(this).index() -(options.tabImage.length * Math.floor($(this).index()/options.tabImage.length))]);
					$($(this).parent().children()[$(this).index() + 1]).addClass('kulaSliderTab');
					$($(this).parent().children()[$(this).index() + 1]).mouseleave(offHover);
					$($(this).parent().children()[$(this).index()]).remove();
				}
			}
		}
		
		//Function to be run with navigation tab is moused out
		function offHover() {
			var myID = slideShow.attr('slideNumber');
		
			if(options.tabChangeOn == 'hover'){
				
			}else{
				if(myID != $(this).index()){
					$($(this).parent().children()[$(this).index()]).after(options.tabImage[$(this).index() -(options.tabImage.length * Math.floor($(this).index()/options.tabImage.length))]);
					$($(this).parent().children()[$(this).index() + 1]).addClass('kulaSliderTab');
					$($(this).parent().children()[$(this).index() + 1]).mouseenter(onHover);
					$($(this).parent().children()[$(this).index()]).remove();
				}
			}
		}
		
		//Functions for slide transitions when next and previous buttons are clicked
		function prevSlide(slideShow) {
			if ($(currentSlideshow2).index()==slideShow.index()||currentSlideshow2=='') {
				if($(slideShow).attr('slideNumber') == 0){
					gotoSlide(showLength - 1);
					$(slideShow).attr('slideNumber', showLength - 1);
				}else{
					var newCount = parseInt(slideShow.attr('slideNumber')) - 1;
					gotoSlide(newCount);
					$(slideShow).attr('slideNumber', newCount);
				}
				previous = options.startingSlide;
			//If the previous button is clicked for a slideshow that is different than the one previously clicked on
			} else {
				if (parseInt(slideShow.attr('slideNumber')) == showLength) {
					gotoSlide(0);
				} else {
					if (parseInt(slideShow.attr('slideNumber')) - 1 < 0) {
						gotoSlide(showLength - 1);
					} else {
						gotoSlide(parseInt(slideShow.attr('slideNumber')) - 1);
					}
				}
				previous = options.startingSlide;
			}
			
			//Resetting previous and currentSlideshow2 variables to be the previous index and the current slide respectively
			if (previous < 0) {
				previous = showLength - 1;
			}
			//currentSlideshow2 = $(this).parent().parent();
			currentSlideshow2 = slideShow;
			//Setting the slideNumber attribute of the slideshow to indicate what the currently selected slide is for this particular slide show
			currentSlideshow2.attr('slideNumber', previous);
			
			return false;
		}

		function nextSlide(slideShow){
			if ($(currentSlideshow2).index()==slideShow.index()||currentSlideshow2=='') {
				if($(slideShow).attr('slideNumber') == (showLength - 1)){
					gotoSlide(0);
					$(slideShow).attr('slideNumber', 0);
				}else{
					var newCount = parseInt(slideShow.attr('slideNumber')) + 1;
					gotoSlide(newCount);
					$(slideShow).attr('slideNumber', newCount);
					
				}
				previous = options.startingSlide;
			//If the next button is clicked for the slideshow that is different than the one previously clicked on
			} else {
				if (parseInt(slideShow.attr('slideNumber')) == 0) {
					gotoSlide(1);
				} else {
					if (parseInt(slideShow.attr('slideNumber')) + 1 == showLength) {
						gotoSlide(0);
					} else {
						gotoSlide(parseInt(slideShow.attr('slideNumber')) + 1);
					}
				}
				previous = options.startingSlide;
			}
			
			//Resetting previous and currentSlideshow2 variables to be the previous index and the current slide respectively
			if (previous >= showLength) {
				previous = 0;
			}
			//currentSlideshow2 = $(this).parent().parent();
			currentSlideshow2 = slideShow;
			//console.log(currentSlideshow2);
			//Setting the slideNumber attribute of the slideshow to indicate what the currently selected slide is for this particular slide show
			currentSlideshow2.attr('slideNumber', previous);
			
			return false;
		}
		
		//Function used to move to another slide in the slideshow
		function gotoSlide(gotoID){
			var slideArray = new Array;
			var prev2 = 0;
			//For loop that adds the css of the current slideshow. If that is the slideshow displayed, it is the assigned as the secondary previous slide.
			for(i = 0; i < slideShow.children('.'+options.slideClass).children().length; i++){
				slideArray.push(slideShow.children('.'+options.slideClass).children().get(i).style.cssText);
				
				if(slideArray[i].search("block") > 0){
					prev2 = i;
				}
			}
			prev2 = $(slideShow).attr('slideNumber');
			
			//Checks to see if the clicked navigation tab does not equal the already clicked navigation tab. If this is not true, then the function will continue.
			//Check to make sure the function will not run if the slide is in the middle of a transition
			if(!$($(slideShow).children()).is(':animated')){
				var previousSlide = prev2;
				options.startingSlide = gotoID;
				
				if (gotoID != slideShow.attr('slideNumber')) {
					slideShow.children('.'+options.slideClass).css('display','none');
					//Transition if the slideEffect is set to 'slideVertical'
					if(options.slideEffect == "slideVertical"){
						var prev_slide = $(slideShow.children('.'+options.slideClass)[prev2]);
						var cur_slide = $(slideShow.children('.'+options.slideClass)[options.startingSlide]);
						$(cur_slide).animate({height:'toggle'}, function() {
							$(prev_slide).css('display', 'none');
							$(cur_slide).css('display', 'block');
						});
					}
					
					//Transition if the slideEffect is set to 'slideSide'
					else if(options.slideEffect == "slideSide"){
						//Declaring and assigning next and previous slide
						var prev_slide = $(slideShow.children('.'+options.slideClass)[prev2]);
						var cur_slide = $(slideShow.children('.'+options.slideClass)[options.startingSlide]);
						var slideShowPosition = slideShow.position();

						//Check to see if the index of the previous slide is less than the index of the current slide.
						if(prev2 < options.startingSlide){
							cur_slide.show();
							cur_slide.css('left', $(window).width()+'px');
							cur_slide.css('top', '0px');
							
							prev_slide.animate({left: (0-prev_slide.width()-slideShowPosition.left)+'px'},'slow','', function(){
								prev_slide.hide();						
							});
							
							cur_slide.animate({left: '0px'},'slow');
							cur_slide.css('display', 'block');
						}else{
							cur_slide.show();
							cur_slide.css('left', (0-prev_slide.width()-slideShowPosition.left)+'px');
							cur_slide.css('display', '');
							
							prev_slide.animate({left: $(window).width()+'px'},'slow','', function(){
								prev_slide.hide();																			
							});
							
							cur_slide.animate({left: '0px'},'slow');
						}
					}
					
					//Transition if the slideEffect is set to 'fade'
					else if(options.slideEffect == "fade"){
						//var prev_slide = $(slideShow.children('.'+options.slideClass)[prev2]);
						//var cur_slide = $(slideShow.children('.'+options.slideClass)[options.startingSlide]);
						//console.log(prev2);
						var prev_slide = $(slideShow).find('.'+options.slideClass)[prev2];
						var cur_slide = $(slideShow).find('.'+options.slideClass)[options.startingSlide];
						$(prev_slide).fadeTo('slow',0,function() {
							$(prev_slide).css('display', 'none');
							$(cur_slide).css('opacity', 0);
							$(cur_slide).css('display', 'block');
							$(cur_slide).fadeTo('slow', 1);
						});
						
					}
					
					//Transition if the slideEffect is set to 'crossfade'
					else if(options.slideEffect == "crossfade"){
						$(slideShow.children('.'+options.slideClass)[previousSlide]).css('display','none');
						$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('opacity',0);
						
						$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('display','block');
						$(slideShow.children('.'+options.slideClass)[options.startingSlide]).fadeTo('slow',1, function(){
							//IE Fix
							if(navigator.userAgent.search(/msie/i)!= -1) { 
								this.style.removeAttribute("filter");	
							}
						});
					}
					//Transition if the slideEFfect is set to nothing, defaulting it to the 'snap' setting
					else{
						$(slideShow.children('.'+options.slideClass)[previousSlide]).css('display', 'none');
						$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('display', 'block');
						$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('opacity', '1');
					}
					
					//Changing the images of the navigation tab of the current and previous slide after the transition is completed
					if(options.tabsContainer){
						$($(slideShow).find(options.tabsContainer).children()[prev2]).after(options.tabImage[prev2-(options.tabImage.length * Math.floor(prev2/options.tabImage.length))]);
						$($(slideShow).find(options.tabsContainer).children()[parseInt(prev2) + 1]).addClass('kulaSliderTab');
						$($(slideShow).find(options.tabsContainer).children()[parseInt(prev2) + 1]).mouseenter(onHover);
						$($(slideShow).find(options.tabsContainer).children()[prev2]).remove();
						
						$($(slideShow).find(options.tabsContainer).children()[options.startingSlide]).after(options.tabImageCurrent[options.startingSlide-(options.tabImage.length * Math.floor(options.startingSlide/options.tabImage.length))]);
						$($(slideShow).find(options.tabsContainer).children()[parseInt(options.startingSlide) + 1]).addClass('kulaSliderTab');
						$($(slideShow).find(options.tabsContainer).children()[parseInt(options.startingSlide) + 1]).mouseenter(onHover);
						$($(slideShow).find(options.tabsContainer).children()[options.startingSlide]).remove();
					}
				} 
			}
		}

		if(options.tabsContainer){
			//Functions to run when a slideshow is clicked on
			$($(slideShow).find('.'+options.tabsContainer+' .kulaSliderTab')).live({
				click: function() {
					var myID = $(this).index();				
					currentSlideshow2 = slideShow;

					slideShow = $(this).parents(options.this_selector);
					//Functions to run if tabChangeOn is set to 'click'
					if (myID != -1) {
						if(options.tabChangeOn == 'click'){
							//If the slide is inside the slideshow of the previously clicked slide		
							gotoSlide(myID);
							$(slideShow).attr('slideNumber', myID);
						}
						//Functions to run if the tabChangeOn is set to 'hover'
						if(options.tabClickLink[myID] && options.tabClickLink[myID] != "" && options.tabChangeOn == 'hover'){
							window.location = options.tabClickLink[myID];
						}
					}	
				}
			});
		}
		
		//Functions to run if the nextControlClass and the prevControlClass are established
		if(options.prevControlClass && options.prevControlClass != "" && options.nextControlClass && options.nextControlClass != ""){
			//Functions to run if the previous button is clicked.
			$(this).find('.'+options.prevControlClass).click(function(){
				check = true;
				slideShow = $(this).parents(options.this_selector);
				//If the previous button is clicked for the slideshow that was previously clicked on
				prevSlide(slideShow);
			});
			
			//Functions to run if the previous button is clicked
			$(this).find('.'+options.nextControlClass).click(function(){
				check = true;
				slideShow = $(this).parents(options.this_selector);
				//If the next button is clicked for the slideshow that was previously clicked on
				nextSlide(slideShow);
			});

			$(function() {
				$(slideShow).bind('swiperight', function(e){
					check = true;
					prevSlide(slideShow);
				});
				$(slideShow).bind('swipeleft', function(e){
					check = true;
					nextSlide(slideShow);
				});
			});
		}
		
		//Functions to run if autoSlide is set to true
		if(options.autoSlide == true){
				autoTimer = window.setInterval(function(){
					check = true;
					if(!$($(slideShow).children()).is(':animated')){
						if($(slideShow).attr('slideNumber') == (showLength - 1)){
							gotoSlide(0);
							$(slideShow).attr('slideNumber', 0);
							previous = showLength - 1;
						}else{
							var newcount = parseInt($(slideShow).attr('slideNumber')) + 1;
							previous = $(slideShow).attr('slideNumber');
							gotoSlide(newcount);
							$(slideShow).attr('slideNumber', newcount);
						}
					}
				}, options.slideTimer);
		}
		
		//Resizes the slideshow for IE
		if($.browser.msie){
			$(document).ready(function(){
				 slideShow.width(slideShow.parent().width() - 4);
				 $(window).resize(function() {
					slideShow.width(slideShow.parent().width() - 4);
					slideShow.children('.'+options.slideClass).css('display','none');
					$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('display', 'block');
				 });
			});
		}
	});
};
}
)(jQuery);
