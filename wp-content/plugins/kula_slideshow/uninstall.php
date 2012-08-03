<?php

	if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
   		exit();

   	$slides = get_posts(array('post_type'=>'slide'));

   	foreach($slides as $slide){
   		wp_delete_post($slide->ID, true);
   	}