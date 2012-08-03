<?
function ams_custom_post_init() {

	//Home Slides
  $labels = array(
    'name' => 'Home Slides',
    'singular_name' => 'Home Slides'
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => false,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'exclude_from_search'=>true,
    'rewrite' => false,
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title' )
  ); 
  register_post_type('home-slides',$args);
}

add_action( 'init', 'ams_custom_post_init' );

function ams_make_meta_boxes(){

	//Home Slides meta
	$metaboxes = new kula_meta();
	$metaboxes->add_meta_box('home-slides', 'Slide Info', 'slide-info', 'generic_form', array( 
		array('label'=> 'Quote Text', 'name'=>'_quote', 'placeholder'=>'Quote', 'type'=>'textarea'), array('label'=> 'Quote Source', 'name'=>'_source', 'placeholder'=>'Source', 'type'=>'text'),
		array('label'=> 'Browse Your Files', 'name'=>'_image', 'button'=>'Upload', 'type'=>'uploader')
		) 
  );
}

add_action('add_meta_boxes', 'ams_make_meta_boxes');

?>