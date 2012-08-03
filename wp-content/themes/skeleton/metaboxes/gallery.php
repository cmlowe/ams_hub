<?php

    add_action('add_meta_boxes', 'gallery_add_custom_box');
    add_action('save_post', 'gallery_save_postdata');

    function gallery_add_custom_box(){
        add_meta_box( 
            'gallery_sectionid',
     		    'Gallery',
            'gallery_inner_custom_box',
            'page' 
        );
    }

    function gallery_inner_custom_box($post){
	      $images = get_post_meta($_GET['post'], '_kula_images', true);
        
        wp_nonce_field(plugin_basename(__FILE__), 'gallery_noncename');

        echo '<div class="gallery_container">';
        	if ($images){ 
      		  for($i = 0; $i < count($images["image"]); $i++){
      		  	echo '<p><a href="#" class="remove button">Remove Image</a><label>Image</label>';
      		  	echo '<input type="text" name="images[image][]" value="'.$images["image"][$i].'" size="25" /><br/>';
      		  	echo '<label>Caption</label>';
      		  	echo '<input type="text" name="images[caption][]" value="'.$images["caption"][$i].'" size="25" /></p>';
      		  }
      		}
        echo '</div>';

        echo '<a href="#" class="gallery_add_new button">Add Image</a>'; 	
    }


    function gallery_save_postdata($post_id){
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
            return;

        if(!wp_verify_nonce($_POST['gallery_noncename'], plugin_basename(__FILE__)))
            return;

        if ('page' == $_POST['post_type']){
          if(!current_user_can( 'edit_page', $post_id))
              return;
        }
        else{
          if(!current_user_can('edit_post', $post_id))
              return;
        } 
        
        $images = $_POST['images'];
        delete_post_meta($post_id, '_kula_images');
        add_post_meta($post_id, '_kula_images', $images, true); 
    }

?>