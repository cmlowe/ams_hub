<?php

/* FEATURED IMAGE SUPPORT ---------------------------------- */
	
	function kula_add_theme_support(){
		add_theme_support('post-thumbnails');
	}

	add_action('after_setup_theme', 'kula_add_theme_support');

/* ERROR CODE ----------------------------------------------- */
	
	//ini_set('display_errors', 1); 
	//ini_set('log_errors', 1); 
	//ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
	//error_reporting(E_ALL);

/* SCRIPTS -------------------------------------------------- */

	function kula_enqueue_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false){
		
		wp_deregister_script($handle);
		wp_register_script($handle, $src, $deps, $ver, $in_footer);
		wp_enqueue_script($handle);
	}
	
	function kula_add_scripts(){
		// header scripts
		// kula_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
		kula_enqueue_script('modernizr', get_bloginfo('template_directory').'/js/libs/modernizr-2.5.3.min.js');

		// footer scripts
		kula_enqueue_script('scripts', get_bloginfo('template_directory').'/js/scripts.js', array(), false, true);

		do_action('kula_child_enqueue_scripts');
	}   

	add_action('wp_enqueue_scripts', 'kula_add_scripts');

	function kula_add_admin_scripts(){
		
		kula_enqueue_script('admin_scripts', get_bloginfo('template_directory').'/js/admin_scripts.js');
	}

	add_action('admin_enqueue_scripts', 'kula_add_admin_scripts');

/* STYLES -------------------------------------------------- */
	
	function kula_enqueue_style($handle, $src = false, $deps = array(), $ver = false, $media = false){
		
		wp_deregister_style($handle);
		wp_register_style($handle, $src, $deps, $ver, $media);
		wp_enqueue_style($handle);
	}

	function kula_add_styles(){
		
		kula_enqueue_style('stylesheet', get_bloginfo('template_directory').'/css/style.css', array(), false, 'all');

		do_action('kula_child_enqueue_styles');
	}

	add_action('wp_enqueue_scripts', 'kula_add_styles');

	function kula_add_admin_styles(){
		
		kula_enqueue_style('meta', get_bloginfo('template_directory').'/css/meta.css', array(), false, 'all');
	}

	add_action('admin_enqueue_scripts', 'kula_add_admin_styles');

/* METABOXES ----------------------------------------------- */	
	
	//include('metaboxes/gallery.php');

/* FUNCTIONS ----------------------------------------------- */	
	
	include('functions/kula_display_breadcrumbs.php');
	include('functions/kula_display_page_subnav.php');
	include('functions/kula_comment.php');

	/*-- settings not tested, not working yet--*/
	//include('functions/kula_social_media_settings.php');

?>