<?php

	function kula_slideshow_markup(){
		$slides = get_posts(array('post_type'=>'slide', 'numberposts'=>-1, 'orderby'=>'menu_order', 'order'=>'ASC'));
		
		echo '<div id="slideshow">';
		
		foreach($slides as $slide){
			$image = get_the_post_thumbnail($slide->ID, array(800, 300));
			$title = get_the_title($slide->ID);
			$caption = get_post_meta($slide->ID, 'kula_slideshow_slide_caption', true);
			$link = get_post_meta($slide->ID, 'kula_slideshow_slide_link', true);
			
			if($link){
				if(is_numeric($link)){
					$link = get_permalink($link);
				}
				else{
					if(!preg_match("~^(?:f|ht)tps?://~i", $link)){
						$link = "http://".$link;
					}
				}
			}
			

			echo '<div class="slide">';
			echo 	'<div class="slide-img">';	
			echo 		$image;
			//echo 		'<img src="http://placebox.es/800/300" alt="" />';
			if($title or $caption or $link){
				echo	'<div class="caption">';

				if($title){
					echo  	'<h2>'.$title.'</h2>';
				}
				
				echo 		'<div class="rule"></div>';

				if($caption){
					echo 	'<p>'.$caption.'</p>';
				}
				
				if($link){
					echo 	'<a class="learn-more" href="'.$link.'">Learn More <span class="more-arrow"></span></a>';
				}

				echo 	'</div>';
			}

			echo 	'</div>';
			echo '</div>';
		}

		echo '</div>';
		echo '<div class="center">';
		echo 	'<ul id="slideshowtab"></ul>';
		echo '</div>';
	}

?>