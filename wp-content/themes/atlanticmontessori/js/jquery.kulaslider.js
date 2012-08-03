//this is the .kulaSlider method that I am adding to jQuery
(function($){
					$.fn.kulaSlider = function(options) {
						
						//here I am setting the default values for 
						var defaults = {
							slideClass: "slide",
							prevControlID: "",
							nextControlID: "",
							tabsContainerID: "",
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
						var options = $.extend(defaults, options);
						
						return this.each(function(){
							var slideShow = $(this);
							var showLength = slideShow.children('.'+options.slideClass).length;
							
							//show first slide hide all others
							slideShow.children('.'+options.slideClass).css('display','none');
							$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('display', 'block');
							
							
							//if the slideshow effect is crossfade then the opacity must be set for all slides
							if(options.slideEffect == "crossfade"){
								slideShow.children('.'+options.slideClass).css('opacity','0');
								$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('opacity', '1');
								slideShow.children('.'+options.slideClass).each(function(){
									if(navigator.userAgent.search(/msie/i)!= -1) { 
										this.style.removeAttribute("filter");	
									}
								});
							}
					
							//checks to see which nav tab states have been provided.
							if(options.tabsContainerID){
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
							
							//set up nav tabs if tabsContainerID is declared
							if(options.tabsContainerID && options.tabsContainerID != ""){
								
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
										$('#'+options.tabsContainerID).append('<li id="'+options.tabsContainerID+'-li-'+i+'" class="kulaSliderTab"><img src="'+tabImageCurrent+'"></li>');
									}else{
										$('#'+options.tabsContainerID).append('<li id="'+options.tabsContainerID+'-li-'+i+'" class="kulaSliderTab"><img src="'+tabImage+'"></li>');
									}
								}
							}
							
							function gotoSlide(gotoID){
								//will not run if slides are in the middle of a transition
								if(!$($(slideShow).children()).is(':animated')){
									var previousSlide = options.startingSlide;
									options.startingSlide = gotoID;
									if(options.slideEffect == "slideVertical"){
										$(slideShow.children('.'+options.slideClass)[previousSlide]).animate({height:'toggle'}, function(){
											$(slideShow.children('.'+options.slideClass)[options.startingSlide]).animate({height:'toggle'});
										});
										
									}else if(options.slideEffect == "slideSide"){
									
										var prev_slide = $(slideShow.children('.'+options.slideClass)[previousSlide]);
										var cur_slide = $(slideShow.children('.'+options.slideClass)[options.startingSlide]);
										
										var slideShowPosition = slideShow.position();
											
										if(previousSlide < options.startingSlide){
											cur_slide.show();
											cur_slide.css('left', $(window).width()+'px');
											cur_slide.css('top', '0px');
											
											prev_slide.animate({left: (0-prev_slide.width()-slideShowPosition.left)+'px'},'slow','', function(){
												prev_slide.hide();																			
											});
											cur_slide.animate({left: '0px'},'slow');
										}else{
											cur_slide.show();
											cur_slide.css('left', (0-prev_slide.width()-slideShowPosition.left)+'px');
											
											prev_slide.animate({left: $(window).width()+'px'},'slow','', function(){
												prev_slide.hide();																			
											});
											cur_slide.animate({left: '0px'},'slow');
										}
										
										
										
									}else if(options.slideEffect == "fade"){
										$(slideShow.children('.'+options.slideClass)[previousSlide]).fadeTo('slow',0,function(){
											 $(slideShow.children('.'+options.slideClass)[previousSlide]).css('display','none');
											 $(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('display','block');
											 $(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('opacity',0);
											 $(slideShow.children('.'+options.slideClass)[options.startingSlide]).fadeTo('slow',1);
										});
									}else if(options.slideEffect == "crossfade"){
										$(slideShow.children('.'+options.slideClass)[previousSlide]).fadeTo('slow',0,function(){
											 $(slideShow.children('.'+options.slideClass)[previousSlide]).css('display','none');
											 $(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('opacity',0);
										});
									  $(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('display','block');
									  $(slideShow.children('.'+options.slideClass)[options.startingSlide]).fadeTo('slow',1, function(){
									if(navigator.userAgent.search(/msie/i)!= -1) { 
										this.style.removeAttribute("filter");	
									}
									});
									}else{
										$(slideShow.children('.'+options.slideClass)[previousSlide]).css('display', 'none');
										$(slideShow.children('.'+options.slideClass)[options.startingSlide]).css('display', 'block');
									}
										
									if(options.tabsContainerID){																																									 
										$($('#'+options.tabsContainerID).children()[options.startingSlide]).children().attr('src', options.tabImageCurrent[options.startingSlide-(options.tabImage.length * Math.floor(options.startingSlide/options.tabImage.length))]);
										$($('#'+options.tabsContainerID).children()[previousSlide]).children().attr('src', options.tabImage[previousSlide-(options.tabImage.length * Math.floor(previousSlide/options.tabImage.length))]);
									}
									
								}
							}
							
							if(options.tabsContainerID){
							
								$('#'+options.tabsContainerID+' .kulaSliderTab').click(function() {
									var myID = $(this).index();
									//alert(myID);
									if(options.tabChangeOn == 'click'){
										if(myID != options.startingSlide)
										gotoSlide(myID);
									}else if(options.tabClickLink[myID] && options.tabClickLink[myID] != "" && options.tabChangeOn == 'hover'){
										window.location = options.tabClickLink[myID];
									}
								});
								
								$('#'+options.tabsContainerID+' .kulaSliderTab').hover(function() {
									var myID = $(this).index();
									
									if(options.tabChangeOn == 'hover'){
										if(myID != options.startingSlide)
										gotoSlide(myID);
									}else{
										if(myID != options.startingSlide)
										
										$(this).children().attr('src', options.tabImageHover[myID-(options.tabImage.length * Math.floor(myID/options.tabImage.length))]);
									}
																					 
								},function() {
									var myID = $(this).index();
									
									if(options.tabChangeOn == 'hover'){
									}else{
										if(myID != options.startingSlide)
										$(this).children().attr('src', options.tabImage[myID-(options.tabImage.length * Math.floor(myID/options.tabImage.length))]);
									}
								});
															
								$('#'+options.tabsContainerID+' .kulaSliderTab').mousedown(function() {
									var myID = $(this).index();
									
									if(myID != options.startingSlide)
									$(this).children().attr('src', options.tabImageActive[myID-(options.tabImage.length * Math.floor(myID/options.tabImage.length))]);
																					 
								});
							}
							if(options.prevControlID && options.prevControlID != "" && options.nextControlID && options.nextControlID != ""){
								$('#'+options.prevControlID).click(function(){
									if(options.startingSlide == 0){
										gotoSlide(showLength - 1);
									}else{
										gotoSlide(options.startingSlide - 1);
									}
									return false;
								});
								
								$('#'+options.nextControlID).click(function(){
									if(options.startingSlide == (showLength - 1)){
										gotoSlide(0);
									}else{
										gotoSlide(options.startingSlide + 1);
									}
									return false;
								});
							}
							
							if(options.autoSlide == true){
								window.setInterval(function(){
																						if(!$($(slideShow).children()).is(':animated')){
																							if(options.startingSlide == (showLength - 1)){
																								gotoSlide(0);
																							}else{
																								gotoSlide(options.startingSlide + 1);
																							}
																						}
																						
																						}, options.slideTimer);
							}
							
							
							//resizes the slideshow for ie
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