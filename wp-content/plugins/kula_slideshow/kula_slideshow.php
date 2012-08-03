<?php

/* -------------------------------------------------- 
	Plugin Name: Kula Slideshow
	Plugin URI: http://kulapartners.com 
	Description: Implements a slideshow for the home page.
	Version: 1.0
	Author: Kula Partners
	Author URI: http://kulapartners.com
-------------------------------------------------- */

	add_action('after_setup_theme', 'kula_slideshow_init');
	
	function kula_slideshow_init(){
    	// create the custom post type for slideshows

		$labels = array(
    		'name' => 'Slides',
    		'singular_name' => 'Slide',
  		);

  		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => false, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title', 'thumbnail', 'page-attributes'),
			'exclude_from_search' => true
		); 

  		register_post_type('slide', $args);

  		add_theme_support('post-thumbnails', array('slide'));

  		include('markup.php');
	}

	function kula_slideshow_rewrite_flush(){
	    kula_slideshow_init();

	    flush_rewrite_rules();
	}

	register_activation_hook(__FILE__, 'kula_slideshow_rewrite_flush');

	// include any scripts needed to run the slideshow

	function kula_slideshow_enqueue_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false){
		wp_deregister_script($handle);
		wp_register_script($handle, $src, $deps, $ver, $in_footer);
		wp_enqueue_script($handle);
	}

	function kula_slideshow_add_scripts(){
		kula_slideshow_enqueue_script('kula_slideshow_mobile', plugins_url('js/jquery.mobile-1.1.0.min.js', __FILE__), array(), false, true);
		kula_slideshow_enqueue_script('kula_slideshow', plugins_url('js/jquery.kulaslider.js', __FILE__), array(), false, true);
		kula_slideshow_enqueue_script('kula_slideshow_settings', plugins_url('js/settings.js', __FILE__), array('jquery'), false, false);
		
	}

	add_action('wp_enqueue_scripts', 'kula_slideshow_add_scripts');

	// add metabox to slides for subtitle and link

	add_action('add_meta_boxes', 'kula_slideshow_add_metabox');
	add_action('save_post', 'kula_slideshow_save_postdata');

	function kula_slideshow_add_metabox(){
	    add_meta_box( 
	        'kula_slideshow_sectionid',
	     	'Slide Meta',
	        'kula_slideshow_inner_metabox',
	        'slide'
	    );
	}

	// the contents of the metabox

	function kula_slideshow_inner_metabox($post){
		$caption = get_post_meta($_GET['post'], 'kula_slideshow_slide_caption', true);
		$link = get_post_meta($_GET['post'], 'kula_slideshow_slide_link', true);
		
	  	wp_nonce_field( plugin_basename(__FILE__), 'kula_slideshow_noncename');
		
		echo '<label>Caption</label>';
	  	echo '<input type="text" name="kula_slideshow_slide_caption" value="'.$caption.'" /><br />';	
	  	echo '<label>Link</label>';
	  	echo '<input type="text" name="kula_slideshow_slide_link" value="'.$link.'" />';
	}

	// save the data to the database

	function kula_slideshow_save_postdata($post_id){
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		  return;

		if(!wp_verify_nonce($_POST['kula_slideshow_noncename'], plugin_basename(__FILE__)))
		  return;

		if('page' == $_POST['post_type']){
		if(!current_user_can('edit_page', $post_id))
		    return;
		}
		else{
		if(!current_user_can('edit_post', $post_id))
		    return;
		} 

	  	$caption = $_POST['kula_slideshow_slide_caption'];
	  	delete_post_meta($post_id, 'kula_slideshow_slide_caption');
  		add_post_meta($post_id, 'kula_slideshow_slide_caption', $caption, true); 

	  	$link = $_POST['kula_slideshow_slide_link'];
	  	delete_post_meta($post_id, 'kula_slideshow_slide_link');
  		add_post_meta($post_id, 'kula_slideshow_slide_link', $link, true); 
	} 

?>