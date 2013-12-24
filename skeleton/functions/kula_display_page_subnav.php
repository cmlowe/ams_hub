<?php
	
	function kula_display_page_subnav($current, $ancestors){
		
		$ancestors = array_reverse($ancestors);
		
		$starting_point = $ancestors ? $ancestors[0] : $current;
			
		$starting_children = get_posts( array('post_type'=>'page', 'post_parent'=>$starting_point, 'orderby'=>'menu_order', 'order'=>'ASC', 'numberposts'=>-1) );
		
		$ancestor_tree = $ancestors;
		
		array_push($ancestor_tree, $current);

		return kula_get_subnav_level($starting_children, $ancestor_tree);
		
	}

	function kula_get_subnav_level($pages, $ancestor_tree){
		
		$return = '';

		foreach($pages as $page){		
			$is_related = in_array($page->ID, $ancestor_tree);
			
			$classes='';
			
			if($is_related){
				$classes="current_page_item";
			}
			
			$return .= '<li class="'.$classes.'"><a href="'.get_permalink($page->ID).'">'.get_the_title($page->ID).'</a>';
					
			if($is_related){
				
				$children = get_posts( array('post_type'=>'page', 'post_parent'=>$page->ID, 'orderby'=>'menu_order', 'order'=>'ASC', 'numberposts'=>-1) );
				
				if($children){
					$return .= '<ul>'.kula_get_subnav_level($children, $ancestor_tree).'</ul>';
				}
			}
					
			$return .='</li>';		
		}	

		return $return;
	}

?>