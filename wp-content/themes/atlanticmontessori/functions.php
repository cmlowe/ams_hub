<?
/* ERROR CODE ----------------------------------------------- */
	
//	ini_set('display_errors', 1); 
//	ini_set('log_errors', 1); 
//	ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
//	error_reporting(E_ALL);



	function ams_add_style(){
	
		kula_enqueue_style('stylesheet', get_bloginfo('stylesheet_directory').'/css/style.css?v=1', array(), false, 'all');
	}

	add_action('kula_child_enqueue_styles', 'ams_add_style');	
	
	function kula_login_enqueue_styles() {
		echo '<link rel="stylesheet" href="'.get_bloginfo('stylesheet_directory').'/css/login.css'.'" type="text/css" media="all" />';
	}
	add_action( 'login_head', 'kula_login_enqueue_styles' );		

	
	function ams_add_scripts() {
		kula_enqueue_script('kulaslider', get_bloginfo('stylesheet_directory').'/js/jquery.kulaslider.js', array(), false, true);
		kula_enqueue_script('child_scripts', get_bloginfo('stylesheet_directory').'/js/child_scripts.js', array('kulaslider'), false, true);
		kula_enqueue_script('retina', get_bloginfo('stylesheet_directory').'/js/retina.js', array(), false, false);
	}
	add_action('kula_child_enqueue_scripts', 'ams_add_scripts');

	add_action( 'init', 'create_post_type' );
	function create_post_type() {
		register_post_type( 'home_slide',
			array(
				'labels' => array(
					'name' => __( 'Slides' ),
					'singular_name' => __( 'Slide' )
				),
			'public' => true,
			'has_archive' => false,
			)
		);
		add_post_type_support( 'home_slide', 'thumbnail' );
	}	

?>