<?php

function tffaq_admin_menu() {
	global $menu, $submenu;

$minLevelGeneral = 'edit_plugins';

	add_menu_page(__('FAQs','tf-faq'), __('FAQs','tf-faq'), $minLevelGeneral , 'tffaq-questions', 'tffaq_questions', plugins_url('images/tffaq.png' , dirname(__FILE__) ));

	add_submenu_page('tffaq-questions', __('Questions','tf-faq'), __('Questions','tf-faq'), $minLevelGeneral , 'tffaq-questions', 'tffaq_questions');

	$questions = tffaq_unanswered_questions();

	$showMenu = (count($questions) > 0)? __('Unanswered Questions','tf-faq').'<span class="update-plugins"><span class="plugin-count">'.count($questions).'</span></span>': __('Unanswered Questions','tf-faq');

	add_submenu_page('tffaq-questions', __('Unanswered Questions','tf-faq'), $showMenu, $minLevelGeneral , 'tffaq-answers', 'tffaq_answers');

	add_submenu_page('tffaq-questions', __('Categories','tf-faq'), __('Categories','tf-faq'), $minLevelGeneral , 'tffaq-categories', 'tffaq_categories');

	add_submenu_page('tffaq-questions', __('Options'), __('Options'), $minLevelGeneral, 'tffaq-options', 'tffaq_options');

}
add_action('admin_menu', 'tffaq_admin_menu');

function tffaq_options() {
	require_once('tffaq-options.php');
}

function tffaq_answers() {
	require_once('tffaq-answers.php');
}

function tffaq_questions() {
	require_once('tffaq-questions.php');
}

function tffaq_categories() {
	require_once('tffaq-categories.php');
}

?>