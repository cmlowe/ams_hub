<?php
/*
Plugin Name: TF FAQ
Plugin URI: http://twentyfiveautumn.com/our-wordpress-plugins/tf-faq/
Description: An advanced frequently asked questions plugin by twentyfiveautumn.com and released under the MIT license.
Version: 0.1.2
Author: ray peaslee
Author URI: http://twentyfiveautumn.com
License: MIT
License URI: http://opensource.org/licenses/MIT
*/

function tffaq_uninstall() {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {

		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
			$old_blog = $wpdb->blogid;

			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_tffaq_uninstall();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_tffaq_uninstall();
}

function _tffaq_uninstall(){

	global $wpdb;
	$db_table_name = $wpdb->prefix . "faq_questions";
	$sql = 'DROP TABLE IF EXISTS '.$db_table_name;
	$wpdb->query( $sql );
	$db_table_name = $wpdb->prefix . "faq_categories";
	$sql = 'DROP TABLE IF EXISTS '.$db_table_name;
	$wpdb->query( $sql );
	delete_option( "tffaq_version" );
	delete_option( "faq-email" );

}

function tffaq_register_js(){

	wp_register_script( 'tffaq_jquery_cookie', plugins_url( 'js/jquery.cookie.js' , __FILE__ ),array('jquery-ui-tabs'),'',1);
	wp_register_script( 'tffaq_frontend', plugins_url( 'js/tffaq-frontend.js' , __FILE__ ),array('jquery-ui-tabs'),'',1 );
	wp_register_script('tffaq_validation', plugins_url('/js/jquery.validate.js', __FILE__), array('jquery'),'1.8.1',1);

	wp_register_script('tffaq_valid_question', plugins_url('/js/tffaq-valid-question.js', __FILE__), array('jquery','tffaq_validation'),'0.0.1', 1);

}
add_action('init','tffaq_register_js');

function tffaq_show($questions) {
	$new_content = '';
		foreach($questions as $question) {
			$new_content .= '<p class="tffaq-question" id="tffaq-question-'.$question->id.'"><a style="cursor: pointer;" data-options=\'{"id":'.$question->id.'}\' >'.$question->question.'</a></p>';
			$new_content .= '<p class="tffaq-answer sticky" id="tffaq-answer-'.$question->id.'" style="display: none;"> '.$question->answer.' </p>';
	}
	return $new_content;
}

function add_tffaq_frontend_js() {

	wp_enqueue_script('jquery-ui-tabs');

	wp_enqueue_script('tffaq_validation');

}
add_action('wp_head','add_tffaq_frontend_js',0);

function tffaq_tabs_loaded() {

	wp_dequeue_script('jquery-ui-tabs');
	wp_dequeue_script('tffaq_validation');
}
add_action('wp_footer','tffaq_tabs_loaded');

function add_tffaq_frontend_css() {

wp_register_style( 'tffaq_jquery_custom', plugins_url( 'css/jquery-ui-1.8.16.custom.css' , __FILE__ ) );
wp_enqueue_style( 'tffaq_jquery_custom' );

$tffaq_stylesheet_path = (file_exists(get_stylesheet_directory().'/tffaq.css'))? get_stylesheet_directory_uri().'/tffaq.css' : plugins_url( 'css/tffaq-frontend.css' , __FILE__ );
wp_register_style( 'tffaq_frontend', $tffaq_stylesheet_path );
wp_enqueue_style( 'tffaq_frontend' );

}
add_action('wp_head','add_tffaq_frontend_css',0);

if(is_admin()) {

	require_once(plugin_dir_path(__FILE__).'admin/admin.php');
}

function faq_get_first_category() {
	global $wpdb;
	$first_cat = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_categories ORDER BY id ASC LIMIT 0,1");
	return $first_cat[0];
}

function faq_get_questions($category) {
	global $wpdb;
	$questions = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_questions WHERE category='".$category."' AND answer!='' ORDER BY id DESC");
	return $questions;
}

function tffaq_unanswered_questions() {
	global $wpdb;
	$questions = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_questions WHERE answer='' ORDER BY id DESC");
	return $questions;
}

function tffaq_get_categories() {
	global $wpdb;
	$categories = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_categories ORDER BY id ASC");
	return $categories;
}

function faq_get_category($id) {
	global $wpdb;
	$the_cat = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_categories WHERE id='".$id."'");

	return empty($the_cat) ? 0 : $the_cat[0];

}

function faq_category_by_name($category) {
	global $wpdb;
	$the_cat = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_categories WHERE category='".$category."'");
	return $the_cat[0];
}

function faq_get_question($id) {
	global $wpdb;
	$the_question = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_questions WHERE id='".$id."'");
	if(!empty($the_question[0])) {
	return $the_question[0];
	}
	return;
}

function tffaq_category_validation() {
	global $wpdb;
	$valid_categories = $wpdb->get_results("SELECT id, category FROM ".$wpdb->prefix."faq_questions");

	foreach($valid_categories as $category) {

	$valid = faq_get_category($category->category);

	if(!$valid){

	$valid_id = faq_get_first_category();
	$valid_id = $valid_id->id;
	$status = $wpdb->query("UPDATE ".$wpdb->prefix."faq_questions SET category='".$valid_id."' WHERE id='".$category->id."'");

	}

	}

}

function tffaq_search() {

wp_print_scripts(array('tffaq_frontend', 'tffaq_valid_question'));

global $wpdb;
$string = isset($_POST['tffaq-search'])? esc_attr($_POST['tffaq-search']):'';

if(!isset($_POST['tffaq-search'])) {

$new_content ='';
$new_content .= '
<div class="tffaq-search">
<form method="post" id="tffaq_search_form" name="tffaq_search_form" action="" >
<h4 class="tffaq-header">'.__('Search FAQ','tf-faq').'</h4>
<input type="text" name="tffaq-search" id="tffaq-search" value="'.$string.'" minlength="3" />
<select name="tffaq-cat" id="tffaq-cat">';
$categories = tffaq_get_categories();
$new_content .= '<option value="0">'.__('All','tf-faq').'</option>';

$selected = '';
foreach($categories as $category) {

if(isset($_POST['tffaq-cat'])){ $selected = $_POST['tffaq-cat'] == absint($category->id)? ' selected="selected"':''; }

	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM ".$wpdb->prefix."faq_questions WHERE category = %d AND answer !='' ", $category->id));

	if($count != '0') {

	$new_content .= '<option '.$selected.'value="'.$category->id.'">'.$category->category.'</option>';

	}

}

$new_content .= '</select>
<input type="submit" name="tffaq-search-btn" id="tffaq-search-btn" value="'.__('Search','tf-faq').'" /></form></div>';

} else {

if(strlen($string) > 2) {

$questions =(!empty($_POST['tffaq-cat']))? $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_questions WHERE category=".absint($_POST['tffaq-cat'])." AND question LIKE '%".$string."%' OR category=".absint($_POST['tffaq-cat'])." AND answer LIKE '%".$string."%'") : $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."faq_questions WHERE question LIKE '%".$string."%' OR answer LIKE '%".$string."%'");

}

$new_content = '';
$new_content .= '<div class="tffaq-category"><h4 class="tffaq-header">Search results for: '.$string.'</h4>';

$new_content .= (!empty($wpdb->last_result)) ? tffaq_show($questions) : __('no search results were found: ','tf-faq');

unset($string);
	$new_content .= '<a href="">Search Again</a></div>';

}

return $new_content;

}

add_shortcode('faq_search', 'tffaq_search');
add_shortcode('tffaq_search', 'tffaq_search');

function faq_ask() {

		global $wpdb;
		wp_print_scripts('tffaq_valid_question');

			if(isset($_POST['tffaq-new-question']) and isset($_POST['tffaq-question'])) {

				$question = wp_kses_data($_POST['tffaq-question']);
				$q_email = sanitize_email($_POST['tffaq-email']);
				$category = absint($_POST['tffaq-category']);
				$table_name = $wpdb->prefix.'faq_questions';

        		$wpdb->insert( $table_name, array( 'category' => $category, 'question' => $question, 'answer' => '', 'email' => $q_email ) );

				$message = __('Your question has been submitted. Thank you.','tf-faq');

				$faq_email = get_option('faq-email');

				if($faq_email) {

					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset="UTF-8"' . "\r\n";
					$cat = faq_get_category($category);
					$subject = $cat->category.' '.__('question','tf-faq');
					$email = '
					<html>
					<body>
						<h2>&quot;'.$cat->category.'&quot; '.__('question','tf-faq').'</h2>
						<p>'.__('Someone asked this question on: ','tf-faq').' <a href="'.get_option('siteurl').'">'.get_option('siteurl').'</a></p>
						<p><strong>'.__('Category').'</strong>: '.$cat->category.'</p>
						<p><strong>'.__('Question').'</strong>: '.$question.'</p>
						<p><a href="'.admin_url('admin.php?page=tffaq-answers','admin').'">click here to answer the question</a></p>
					</body>
					</html>
					';

					$email = stripslashes($email);

					wp_mail($faq_email,$subject,$email,$headers);
				}

			}

$new_content = '';

if(isset($message)) { $new_content .=	'<p class="tffaq-message ui-state-highlight ui-corner-all">'.$message.'</p>'; }

$new_content .= '

<div class="tffaq-ask-question">
	<form name="tffaq-questionForm" id="tffaq-questionForm" method="post" action="">
		<h4 class="tffaq-header">'.__('Ask a question','tf-faq').'</h4>
		<p class="tffaq-ask-label"><label for="tffaq-category">'.__('Select a Category for your question.','tf-faq').'</label></p>
		<p><select class="required" name="tffaq-category" id="tffaq-category">';

		$categories = tffaq_get_categories();
		foreach($categories as $category) {

		$new_content .= '<option value="'.$category->id.'">'.$category->category.'</option>';

		}

		$new_content .= '</select></p>
		<p class="tffaq-ask-label"><label for="tffaq-question">'.__('What is your question?','tf-faq').'</label></p>
		<p><textarea class="required" id="tffaq-question" name="tffaq-question" rows="" cols=""></textarea></p>
		<p class="tffaq-ask-label"><label for="tffaq-email">'.__('Email address to send the answer to:','tf-faq').'</label></p>
		<p><input type="text" class="required" id="tffaq-email" name="tffaq-email" /></p>
		<input type="submit" value="'.__('Ask Question','tf-faq').'" id="tffaq-new-question" name="tffaq-new-question" />
	</form>
</div>';

return $new_content;

}

add_shortcode('faq_ask', 'faq_ask');

function faq_one_category( $atts, $content = null ) {

wp_print_scripts('tffaq_frontend');
   extract( shortcode_atts( array(
      'id' => '1',
      ), $atts ) );

	$new_content = '';
	$category = faq_get_category($id);
	$new_content .= '<div class="tffaq-category"><h4 class="tffaq-header">'.$category->category.'</h4>';
	$questions = faq_get_questions($category->id);
	$new_content .= stripslashes(tffaq_show($questions));
	$new_content .= '</div>';

	return $new_content;
}

add_shortcode('faq', 'faq_one_category');

function faq_category_name( $atts, $content = null ) {

   wp_print_scripts('tffaq_frontend');
   extract( shortcode_atts( array(
      'name' => 'General',
      ), $atts ) );

	$new_content = '';
	$category = faq_category_by_name($name);
	$new_content .= '<div class="tffaq-category"><h4 class="tffaq-header">'.$category->category.'</h4>';
	$questions = faq_get_questions($category->id);
	$new_content .= stripslashes(tffaq_show($questions));
	$new_content .= '</div>';

	return $new_content;
}

add_shortcode('faq_name', 'faq_category_name');

function faq_all(){

	wp_print_scripts('tffaq_frontend');
	global $wpdb;
	$categories = tffaq_get_categories();

	$new_content = '';
	foreach($categories as $category) {
	$category = faq_get_category($category->id);

	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM ".$wpdb->prefix."faq_questions WHERE category = %d AND answer !='' ", $category->id));

	if($count != '0') {

	$new_content .= '<div class="tffaq-category"><h4 class="tffaq-header">'.$category->category.'</h4>';
	$questions = faq_get_questions($category->id);
	$new_content .= stripslashes(tffaq_show($questions));
	$new_content .= '</div>';

	}

	}

return $new_content;
}

add_shortcode('faq_all', 'faq_all');

function tffaq_tabs(){

 wp_print_scripts('tffaq_jquery_cookie');

$new_content = '';

	$new_content .= '<div id="tffaq-tabs">
		<ul>
			<li><a href="#tffaq-tabs-1">'.__('Frequently Asked Questions','tf-faq').'</a></li>
			<li><a href="#tffaq-tabs-2">'.__('Search','tf-faq').'</a></li>
			<li><a href="#tffaq-tabs-3">'.__('Ask A Question','tf-faq').'</a></li>
		</ul>

		<div id="tffaq-tabs-1">';

		$new_content .= do_shortcode('[faq_all]');

		$new_content .= '</div>

		<div id="tffaq-tabs-2" >';

		$new_content .= do_shortcode('[tffaq_search]');

		$new_content .= '</div>

		<div id="tffaq-tabs-3">';

		$new_content .= do_shortcode('[faq_ask]');

		$new_content .= '</div></div>';

return $new_content;
}

add_shortcode('tffaq_tabs', 'tffaq_tabs');

function tfa_tabs(){

$new_content = '';

$new_content .= do_shortcode('[tffaq_tabs]');

return $new_content;
}
add_shortcode('tfa_tabs', 'tfa_tabs');

register_activation_hook( __FILE__, 'tffaq_install' );

function tffaq_install() {
   global $wpdb;

   	register_uninstall_hook( __FILE__, 'tffaq_uninstall' );

	if (function_exists('is_multisite') && is_multisite()) {

		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
	                $old_blog = $wpdb->blogid;

			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_tffaq_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_tffaq_install();
}

function _tffaq_install() {

   global $wpdb;

   $table_name = $wpdb->prefix . "faq_questions";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  category int(9) DEFAULT '0' NOT NULL,
	  question text NOT NULL,
	  answer text NOT NULL,
	  email VARCHAR(56) NOT NULL,
	  hits int DEFAULT '0' NOT NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH.'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }

   $table_name = $wpdb->prefix . "faq_categories";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  category VARCHAR(50) NOT NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH.'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      $insert = "INSERT INTO ".$table_name." (category) "."VALUES ('".__('General','tf-faq')."')";
      $results = $wpdb->query( $insert );
    }

		$tffaq_version = "0.1.2";
		if(!add_option("tffaq_version",$tffaq_version)) {
		update_option("tffaq_version",$tffaq_version);
	}

	if(!$wpdb->get_col_info($wpdb->prefix.'faq_questions',4)) {

	$sql = 'ALTER TABLE '.$wpdb->prefix.'faq_questions ADD email VARCHAR(56) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;';

	$wpdb->query($sql);

	}
}

add_action( 'wpmu_new_blog', 'tf_new_blog', 10, 6);

function tf_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	global $wpdb;

	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	   require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	   }

	if (is_plugin_active_for_network('tf-faq/tf-faq.php')) {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		_tffaq_install();
		switch_to_blog($old_blog);
	}
}

function tffaq_category_dropdown() {
?>
    <select name="tffaq_category" id="tffaq_category" onchange="window.location='?page=tffaq-questions&amp;cat='+this.value">
    	<?php
			$cur_category = absint($_GET['cat']);
			$categories = tffaq_get_categories();
			foreach($categories as $category) {
				$selected = '';
				if($category->id == $cur_category) $selected = ' selected="selected" ';
				echo '<option'.$selected.' value="'.$category->id.'">'.$category->category.'</option>';
			}
		?>
    </select>
<?php
}

function faq_categoryname_meta_box() {

	$category_id = isset($_GET['id']) ? absint($_GET['id']) : '';

	if($category_id) { $edit_category = faq_get_category($category_id); }
?>
		<input type="hidden" name="faq_category_id" value="<?php echo $category_id?>" />
  Name: <input type="text" name="faq_category_name" value="

<?php if(isset($edit_category)) { echo $edit_category->category; } ?>

    " class="regular-text" />
<?php
}

function tffaq_question_metabox() {

	$tf_title = esc_attr($_GET['tf_title']);
	$edit_question = isset($_GET['id']) ? faq_get_question(absint($_GET['id']))	: 0 ;
	$cur_category = (empty($_GET['cat'])) ? faq_get_first_category():faq_get_category(absint($_GET['cat']));
	$tffaq_mail = (empty($edit_question->email)) ? '': $edit_question->email ;
	$tffaq_id = (empty($edit_question->id)) ? '': $edit_question->id ;
	?>
	<form method="post" action="?page=tffaq-questions">
	<input type="hidden" id="tffaq_mail" name="tffaq_mail" value="<?php echo $tffaq_mail ?>" />
<p>
    <h3><strong>"<?php echo $cur_category->category; ?>"</strong></h3>
	<input type="hidden" id="tffaq_question_id" name="tffaq_question_id" value="<?php echo $tffaq_id ?>" />
	<input type="hidden" id="tffaq_category" name="tffaq_category" value="<?php echo $cur_category->id?>" />
</p>

<p>
	<label for="tffaq_question"><h4><?php _e('Question: ','tf-faq');?></h4></label>
    <textarea id="tffaq_question" name="tffaq_question" class="large-text">
    <?php if(!empty($edit_question)) { echo trim(stripslashes($edit_question->question)); } ?>
    </textarea>

</p>

<p>
<label for="tffaq_answer"><h4><?php _e('Answer: ','tf-faq');?></h4></label>

<?php
	if(user_can_richedit()) {
		$tffaq_answer = (!empty($edit_question))? trim(stripslashes($edit_question->answer)):'';
		wp_editor( stripslashes($tffaq_answer), 'tffaq_answer' );
	}else{
?>
	<textarea id="tffaq_answer" name="tffaq_answer" class="large-text">
		    <?php if(!empty($edit_question)) { echo trim(stripslashes($edit_question->answer)); } ?>
    </textarea>

<?php } ?>

</p>

    <input type="submit" value="<?php echo $tf_title.__(' this Question ','tf-faq');?>" name="tffaq_submit_question" id="tffaq_submit_question" class="button-secondary" />
    <span><?php _e('or Select a Different Category','tf-faq'); tffaq_category_dropdown(); ?></span>
</form>
    <?php
}

function tffaq_loadtranslation() {
	load_plugin_textdomain('tf-faq', false,'tf-faq/languages');
}

add_action('init', 'tffaq_loadtranslation');
?>