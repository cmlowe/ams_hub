<?php
	
	function kula_display_breadcrumbs($id, $ancestors){
		$return = '<li><a href="'.get_bloginfo('url').'">'.__('Home', 'kula').'</a></li> > ';
		if($ancestors){
			$ancestors = array_reverse($ancestors);
			foreach($ancestors as $ancestor){
				$return .= '<li>';
				$return .= '<a href="'.get_permalink($ancestor).'">'.get_the_title($ancestor).'</a>';
				$return .= '</li> > ';
			}
		}	
		$return .= '<li>'.get_the_title($id).'</li>';
		
		return $return;
	}

?>