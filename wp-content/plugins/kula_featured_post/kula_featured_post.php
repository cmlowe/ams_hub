<?php

/* -------------------------------------------------- 
	Plugin Name: Kula Featured Post
	Plugin URI: http://kulapartners.com 
	Description: Allows a user to specify whether a given post will be featured on the home page.
	Version: 1.0
	Author: Kula Partners
	Author URI: http://kulapartners.com
-------------------------------------------------- */
	
	// which post types will inherit this functionality?
	
	$post_types = array('post', 'slide');

	add_option('kula_featured_post_types', $post_types);

	// add the menu option to any post types specified in $post_types

	add_action('admin_menu', 'kula_featured_post_menu', 10, 1);

	function kula_featured_post_menu(){
		global $post_types;
		
		foreach($post_types as $post_type){
			if($post_type == 'post'){
				add_submenu_page('edit.php', 'Featured Post', 'Featured Post', 'manage_options', 'kula_featured_post', 'kula_featured_post_options');
			}
			else{
				$singular_name = get_post_types(array('name'=>$post_type), 'objects'); 
				$singular_name = $singular_name[$post_type]->labels->singular_name;

				add_submenu_page('edit.php?post_type='.$post_type.'', 'Featured '.$singular_name.'', 'Featured '.$singular_name.'', 'manage_options', 'kula_featured_'.$post_type.'', 'kula_featured_post_options');
			}
		}
	}

	// the contents of the featured menu page

	function kula_featured_post_options(){
		$post_type = $_GET['post_type'];

		$singular_name = get_post_types(array('name'=>$post_type), 'objects');
		$singular_name = $singular_name[$post_type]->labels->singular_name;

		if(!$post_type){
			$singular_name = "Post";
		}

		if(!current_user_can('manage_options')){
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		if($_POST['submit'] and !wp_verify_nonce($_POST['kula_featured_post_nonce'],'kula_featured_post_nonce')){
			echo 'Sorry, your nonce did not verify.';
   			exit;
		}

		if($_POST['submit']){
			if($featured_post = $_POST['kula_featured_post']){
				if($post_type){
					delete_option('kula_featured_'.$post_type.'');
					add_option('kula_featured_'.$post_type.'', $featured_post);
				}
				else{
					delete_option('kula_featured_post');
					add_option('kula_featured_post', $featured_post);
				}
			}
		}

		$blogs = get_posts(array('numberposts'=>-1, 'post_type'=>$post_type));

		if($_POST['kula_featured_post'] == 'random' or get_option('kula_featured_'.$post_type.'') == 'random'){		
			$random = 'selected="selected"';		
		}
		else if($_POST['kula_featured_post'] == 'select' or get_option('kula_featured_'.$post_type.'') == 'select'){
			$select = 'selected="selected"';
		}

		echo '<div class="wrap">';
		echo '<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>';
		echo 	'<h2>Featured '.$singular_name.'</h2>';
		echo '</div>';
		echo '<div id="featured_post">';
		echo 	'<form method="post">';
		echo    	'<table class="form-table">';
		echo 			'<tbody>';
		echo 				'<tr valign="top">';
		echo 					'<th scope="row">';
		echo 						'<label for="featured_post">Featured '.$singular_name.'</label>';
		echo 					'</th>';
		echo 					'<td>';
		echo 						'<select name="kula_featured_post" id="featured_post">';
		echo 							'<option value="select" '.$select.' >- Select -</option>';
		echo 							'<option value="random" '.$random.' >Random</option>';		
		
		foreach($blogs as $blog){
			unset($selected);

			if($_POST['kula_featured_post'] == $blog->ID or get_option('kula_featured_'.$post_type.'') == $blog->ID){		
				$selected = 'selected="selected"';		
			}
		
		    echo 						'<option value="'.$blog->ID.'" '.$selected.' >'.$blog->post_title.'</option>';
		   
		}

		echo 						'</select>';
		echo 					'</td>';
		echo 				'</tr>';
		echo 			'</tbody>';
		echo 		'</table>';
		echo 		'<p class="submit"><input type="submit" id="submit" class="button-primary" value="Save Changes" name="submit" /></p>';
					wp_nonce_field('kula_featured_post_nonce','kula_featured_post_nonce');
		echo 	'</form>';
		echo '</div>';

	}

	// add the metabox to posts

	add_action('add_meta_boxes', 'kula_featured_post_add_metabox');
	add_action('save_post', 'kula_featured_post_save_postdata');

	function kula_featured_post_add_metabox(){
		global $post_types;

		foreach($post_types as $post_type){
			$singular_name = get_post_types(array('name'=>$post_type), 'objects'); 
			$singular_name = $singular_name[$post_type]->labels->singular_name;

		    add_meta_box( 
		        'kula_featured_post_sectionid',
		     	'Featured '.$singular_name.'',
		        'kula_featured_post_inner_metabox',
		        ''.$post_type.'',
		        'advanced',
		        'default',
		        array($post_type)
		    );
		}
	}

	// the contents of the metabox

	function kula_featured_post_inner_metabox($post, $post_type){
		$post_type = $post_type['args'][0];

		$singular_name = get_post_types(array('name'=>$post_type), 'objects'); 
		$singular_name = $singular_name[$post_type]->labels->singular_name;

		$singular_name = strtolower($singular_name);

		$featured_post = get_option('kula_featured_'.$post_type.'');

		if($featured_post == $post->ID){
			$checked = 'checked="checked"';
		}
		else{
			$checked = '';
		}
		
	  	wp_nonce_field( plugin_basename( __FILE__ ), 'kula_featured_post_noncename' );
		
		echo '<p>Do you want to make this the featured '.$singular_name.'?</p>';
	  	echo '<input type="checkbox" '.$checked.' name="kula_featured_post" value="'.$post->ID.'" />';	
	  	echo '<input type="hidden" name="kula_featured_post_type" value="'.$post_type.'" />';
	}

	// save the data to the database

	function kula_featured_post_save_postdata($post_id){
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		  return;

		if(!wp_verify_nonce($_POST['kula_featured_post_noncename'], plugin_basename(__FILE__)))
		  return;

		if('page' == $_POST['post_type']){
		if(!current_user_can('edit_page', $post_id))
		    return;
		}
		else{
		if (!current_user_can('edit_post', $post_id))
		    return;
		}
	  
	  	$featured_post = $_POST['kula_featured_post'];
	  	$post_type = $_POST['kula_featured_post_type'];

	  	if($featured_post){
			delete_option('kula_featured_'.$post_type.'');
			add_option('kula_featured_'.$post_type.'', $featured_post);
		}
		else{
			$was_featured = get_option('kula_featured_'.$post_type.'');

			if($was_featured == $post_id){
				delete_option('kula_featured_'.$post_type.'');
				add_option('kula_featured_'.$post_type.'', 'select');
			}
		}
	} 

?>