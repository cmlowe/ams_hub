<?php

global $wpdb;

if(current_user_can('administrator')) {

if(isset($_POST['tffaq_submit_question'])) {
	$category = absint($_POST['tffaq_category']);
	$question = trim(wp_kses_data($_POST['tffaq_question']));
	$answer = trim(wp_kses_post($_POST['tffaq_answer']));
	$email = is_email($_POST['tffaq_mail']) ? $_POST['tffaq_mail'] : '';

	if(empty($_POST['tffaq_question_id'])) {

	if(empty($question)) {

		$_POST['tffaq_message'] = array('class' => 'error', 'status' => __('The New Question can not be Blank!')) ;

	} else {

	$status = $wpdb->insert( $wpdb->prefix.'faq_questions', array('category' => $category, 'question' => $question, 'answer' => $answer, 'email' => $email ),array('%d','%s','%s', '%s'));

	$_POST['tffaq_message'] = ($status == 1) ?
	  array('class' => 'updated', 'status' => __('A New Question has been Successfully Created'))
	: array('class' => 'error', 'status' => __('There was an Error! New Question was not Created')) ;

	}

	} else {

	$question_id = absint($_POST['tffaq_question_id']);

	$status = $wpdb->update( $wpdb->prefix.'faq_questions', array('question' => $question, 'answer' => $answer), array('id' => $question_id, 'category' => $category ));

	$_POST['tffaq_message'] = ($status == 1) ?
	  array('class' => 'updated', 'status' => __('The Question has been Successfully Updated'))
	: array('class' => 'error', 'status' => __('There was an Error! The Question was not Updated')) ;

if(!empty($email)) {

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset="UTF-8"' . "\r\n";
$cat = faq_get_category($category);
$subject = $cat->category.' '.__('question on ','tf-faq').get_option('blogname');
$email_body = '
<html>
<body>
<h2>&quot;'.$cat->category.'&quot; '.__('question','tf-faq').'</h2>
<p>'.__('Your question on: ','tf-faq').' <a href="'.get_option('siteurl').'">'.get_option('siteurl').'</a>'.__(' has been answered.','tf-faq').'</p>
<p><strong>'.__('Category').'</strong>: '.$cat->category.'</p>
<p><strong>'.__('Question').'</strong>: '.$question.'</p>
<p><strong>'.__('Answer').'</strong>: '.$answer.'</p>
<p>'.__('Thank you again from ','tf-faq').'<a href="'.get_option('siteurl').'">'.get_option('blogname').'</a></p>
</body>
</html>';

wp_mail($email,$subject,$email_body,$headers);

}

	}

$_GET['cat'] = $category;

}

if(isset($_GET['delete'])) {
	$_GET['delete'] = absint($_GET['delete']);
	$status = $wpdb->query("DELETE FROM ".$wpdb->prefix."faq_questions WHERE id='".$_GET['delete']."'");

	$_POST['tffaq_message'] = ($status == 1) ?
	  array('class' => 'updated', 'status' => __('The Question has been Successfully Deleted'))
	: array('class' => 'error', 'status' => __('There was an Error! The Question was not Deleted')) ;

}

if(isset($_GET['direction'])) {

$check_admin_referer = check_admin_referer('tffaq_sort', '_wpnonce');

	if($check_admin_referer == 1 ) {

	$direction = ($_GET['direction'] == 'down') ? array('<', 'DESC') : array('>', 'ASC');
	$id = intval($_GET['order-id']);
	$category = intval($_GET['cat']);

	$sort_sql = "SELECT id FROM ".$wpdb->prefix."faq_questions WHERE id".$direction[0].$id." AND category='".$category."' ORDER BY id ".$direction[1]." LIMIT 0,1";
	$sort_id = $wpdb->get_results($sort_sql);
	$sort_id = $sort_id[0]->id;

	$temp = $wpdb->update( $wpdb->prefix.'faq_questions', array('id' => (0-$sort_id)) , array('id' => $sort_id)  );
	$new =  $wpdb->update( $wpdb->prefix.'faq_questions', array('id' => absint($sort_id)), array('id' => $id) );
	$final = $wpdb->update( $wpdb->prefix.'faq_questions', array('id' => absint($id)), array('id' => (0-$sort_id)) );

	unset($check_admin_referer);

	}

}

if(isset($_POST['tffaq_checkbox_submit'])) {

check_admin_referer('tffaq_checkboxes','tffaq_checkboxes');

if(isset($_POST['tffaq_question'])) {

	$action = esc_attr($_POST['tffaq_action']);
	$selected = array($_POST['tffaq_question']);

if(($action == 'delete') and ($_POST['tffaq_confirm'] == 'true')) {

	foreach($selected as $question) {

			foreach($question as $key => $value) {

			$status = $wpdb->query("DELETE FROM ".$wpdb->prefix."faq_questions WHERE id='".absint($value)."'");

			if(!$status == 1) { break;}

			}
			}

			$_POST['tffaq_message'] = ($status == 1) ?
			  array('class' => 'updated', 'status' => __('The Questions have been Successfully Deleted'))
			: array('class' => 'error', 'status' => __('There was an Error! The Questions have not been Deleted')) ;

	} else {

if($action != 'delete') {

	$move_to = str_replace('cat-','',$action);

		foreach($selected as $question) {

			foreach($question as $key => $value) {

			$status = $wpdb->query("UPDATE ".$wpdb->prefix."faq_questions SET category='".$move_to."' WHERE id='".absint($value)."'");

			if(!$status == 1) { break;}

			}

		}

		$_POST['tffaq_message'] = ($status == 1) ?
		  array('class' => 'updated', 'status' => __('The Questions have been Successfully Moved'))
		: array('class' => 'error', 'status' => __('There was an Error! The Questions have not been Moved')) ;

	}

	}

} else {

	$_POST['tffaq_message'] = array('class' => 'error', 'status' => __('There was an Error! No Questions were Selected')) ;

	}

}

$cur_category = (empty($_GET['cat'])) ?	faq_get_first_category():faq_get_category($_GET['cat']);

if(!$cur_category) { ?>
<div class="wrap">
	<h2><?php _e('Create a category','tf-faq')?></h2>
    <p><?php _e('No Categories have been created.','tf-faq')?><?php _e(' You can ','tf-faq')?><a href="?page=tffaq-categories&new=new"><?php _e('Create One Here','tf-faq')?></a></p>
</div>
<?php
} else {
?>
<div class="wrap">
<div class="icon32" id="icon-edit"><br></div>
	<h2>&ldquo;<?php echo ucfirst($cur_category->category)?>&rdquo; <?php _e('Questions','tf-faq')?></h2>

<?php

if(isset($_POST['tffaq_message'])){
?>
<div class="<?php echo esc_attr($_POST['tffaq_message']['class']) ?>"><h4><?php echo esc_attr($_POST['tffaq_message']['status']) ?></h4></div>'
<?php
}

if(isset($_GET['new']) and $_GET['new'] === 'new') {

global $cur_category;

$cur_question = (!empty($_GET['id']))? $_GET['id'] :'';
$cur_question = faq_get_question($cur_question);
$cur_category = (empty($_GET['cat']))? absint(faq_get_first_category()) : absint($_GET['cat']);
$cur_category = faq_get_category($cur_category);

if((!empty($cur_question->question)and empty($cur_question->answer))) {
		$tf_title = __('Answer','tf-faq');
	}elseif
		(!empty($cur_question->question)and (!empty($cur_question->answer))) {
		$tf_title = __('Edit','tf-faq');
	}else {
		$tf_title = __('Add','tf-faq');
	}

$_GET['tf_title'] = $tf_title;

add_meta_box('tffaq_question', $tf_title.__(' this Question ','tf-faq'), 'tffaq_question_metabox', 'faq', 'normal', 'core');

?>

<div class="wrap">
	<div id="poststuff" class="metabox-holder">
    <?php do_meta_boxes('faq', 'normal','low'); ?>
	</div>
</div>

<?php

}

?>
    <form method="post" action="?page=tffaq-questions&amp;cat=<?php echo $cur_category->id?>" id="tffaq_checkbox_form">

   <?php wp_nonce_field('tffaq_checkboxes','tffaq_checkboxes'); ?>
   <input id="tffaq_confirm" type="hidden" value="false" name="tffaq_confirm">
   <input id="tffaq_is_question" type="hidden" value="true" name="tffaq_is_question">

    <p>
    	<select name="tffaq_action" id="tffaq_action">
        	<option value="actions"><?php _e('Actions','tf-faq')?></option>
            <?php
				$categories = tffaq_get_categories();
				foreach($categories as $category) {
					if($category->category != $cur_category->category) {
						echo '<option value="cat-'.$category->id.'">'.__('move to','tf-faq').' "'.$category->category.'"</option>';
					}
				}
			?>
        	<option value="delete"><?php _e('Delete','tf-faq')?></option>
        </select>
        <input type="submit" id="tffaq_checkbox_submit" name="tffaq_checkbox_submit" class="button-secondary" value="<?php _e('Apply','tf-faq')?>" />

<?php if(!isset($_GET['new'])) { ?>

<a title="" href="?page=tffaq-questions&amp;new=new&amp;cat=<?php echo $cur_category->id?>" class="button-secondary">Add a New Question</a>
<?php _e('or Select a Different Category','tf-faq'); tffaq_category_dropdown(); ?>

<?php } ?>

    </p>
    <table class="widefat page fixed" cellpadding="0">
    	<thead>
        	<tr>
				<th id="cb" class="manage-column column-cb check-column" style="" scope="col"><input type="checkbox"/></th>
            	<th class="manage-column"><?php _e('Question','tf-faq')?></th>
            	<th class="manage-column" colspan="2"><?php _e('Answer','tf-faq')?></th>
            	<th class="manage-column" style="width: 10%;"><?php _e('Sort Questions','tf-faq')?></th>
          </tr>
        </thead>
    	<tfoot>
        	<tr>
				<th id="cb" class="manage-column column-cb check-column" style="" scope="col"><input type="checkbox"/></th>
            	<th class="manage-column"><?php _e('Question','tf-faq')?></th>
            	<th class="manage-column" colspan="2"><?php _e('Answer','tf-faq')?></th>
            	<th class="manage-column"><?php _e('Sort Questions','tf-faq')?></th>
            </tr>
        </tfoot>
        <tbody>
            	<?php
				$questions = faq_get_questions($cur_category->id);
				if($questions) {
					$i=0;
					foreach($questions as $question) { $i++;
						?>
						<tr class="<?php echo (ceil($i/2) == ($i/2)) ? "" : "alternate"; ?>">
							<th class="check-column" scope="row">
								<input type="checkbox" value="<?php echo $question->id?>" name="tffaq_question[]" />
							</th>
							<td>
								<a href="?page=tffaq-questions&amp;id=<?php echo $question->id?>&amp;cat=<?php echo $question->category?>&amp;new=new" class="row-title"><?php echo stripslashes($question->question)?></a>
								<div class="row-actions">
									<span class="edit"><a href="?page=tffaq-questions&amp;id=<?php echo $question->id?>&amp;cat=<?php echo $question->category?>&amp;new=new">Edit</a> | </span>
									<span class="delete"><a href="?page=tffaq-questions&amp;cat=<?php echo $question->category?>&amp;delete=<?php echo $question->id?>" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a></span>
								</div>
							</td>
							<?php $answer_length = (strlen(stripslashes($question->answer)) > 200)? '<strong><em> more...</em></strong>':'';?>
							<td colspan="2"><?php echo substr(stripslashes($question->answer), 0, 200).$answer_length;?></td>
							<td style="text-align:center;">

	<?php if($i!=1) { ?>
	<?php $up_url = add_query_arg( array('order-id'=>$question->id, 'direction' => 'up', 'cat' => $question->category)); ?>
	<?php $nonce_url = wp_nonce_url( $up_url , 'tffaq_sort' ); ?>
	<a href="<?php echo $nonce_url ?>">&#x25B2;</a>
	<?php } ?>

	<?php if($i != count($questions)) { ?>
	<?php $down_url = add_query_arg( array('order-id'=>$question->id, 'direction' => 'down', 'cat' => $question->category)); ?>
	<?php $nonce_url = wp_nonce_url( $down_url , 'tffaq_sort' ); ?>
	<a href="<?php echo $nonce_url ?>">&#x25BC;</a>
	<?php } ?>

							</td>
						</tr>
						<?php
					}
                } else {
				?>
                	<tr><td colspan="4"><?php _e('There are no Questions in this Category.','tf-faq')?> <a href="?page=tffaq-questions&amp;new=new&amp;cat=<?php echo $cur_category->id?>"><?php _e('click here to add a question','tf-faq') ?></a></td></tr>
                <?php
				}
				?>
        </tbody>
    </table>
    <p>
    	<select name="tffaq_action2" id="tffaq_action2" >
        	<option value="actions"><?php _e('Actions','tf-faq')?></option>
            <?php
				$categories = tffaq_get_categories();
				foreach($categories as $category) {
					if($category->category != $cur_category->category) {
						echo '<option value="cat-'.$category->id.'">'.__('Move to','tf-faq').' '.$category->category.'</option>';
					}
				}
			?>
        	<option value="delete"><?php _e('Delete','tf-faq')?></option>
        </select>
       <input type="submit" id="tffaq_checkbox_submit2" name="tffaq_checkbox_submit2" class="button-secondary" value="<?php _e('Apply','tf-faq')?>" />

<?php if(!isset($_GET['new'])) { ?>
    	<a title="" href="?page=tffaq-questions&amp;new=new&amp;cat=<?php echo $cur_category->id?>" class="button-secondary">Add a New Question</a>
<?php } ?>
    </p>
    </form>
</div>

<?php } ?>
<script type='text/javascript' src='<?php echo plugins_url( 'admin/js/tffaq-admin.js' , __FILE__ ); ?>'></script>
<?php }