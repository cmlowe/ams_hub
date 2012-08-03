<?php

	if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
   		exit();
 
   	$post_types = get_option('kula_featured_post_types');

   	foreach($post_types as $post_type){
		delete_option('kula_featured_'.$post_type.'');
	}

	delete_option('kula_featured_post_types');

?>