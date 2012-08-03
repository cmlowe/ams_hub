<?php

if(current_user_can('administrator')) {

global $wpdb;

?>
<div class="wrap">
	<div class="icon32" id="icon-edit"><br></div>
	<h2><?php _e('Unanswered questions','tf-faq')?></h2>

    <form method="post" action="?page=tffaq-questions" id="tffaq_answer_form">

    	<?php wp_nonce_field('tffaq_checkboxes','tffaq_checkboxes'); ?>
	    <input id="tffaq_confirm" type="hidden" value="false" name="tffaq_confirm">
   		<input id="tffaq_is_question" type="hidden" value="true" name="tffaq_is_question">
    <p>
    	<select name="tffaq_action" id="tffaq_action">
        	<option value="actions"><?php _e('Actions','tf-faq')?></option>
        	<option value="delete"><?php _e('Delete','tf-faq')?></option>
        </select>
        <input type="submit" id="tffaq_checkbox_submit" name="tffaq_checkbox_submit" class="button-secondary" value="<?php _e('Apply','tf-faq')?>" />
    </p>
    <table class="widefat page fixed" cellpadding="0">
    	<thead>
        	<tr>
				<th id="cb" class="manage-column column-cb check-column" style="" scope="col"><input type="checkbox"/></th>
            	<th class="manage-column"><?php _e('Question','tf-faq')?></th>
            	<th class="manage-column"><?php _e('Category','tf-faq')?></th>
            	<th class="manage-column"><?php _e('Email','tf-faq')?></th>
            </tr>
        </thead>
    	<tfoot>
        	<tr>
				<th id="cb" class="manage-column column-cb check-column" style="" scope="col"><input type="checkbox"/></th>
            	<th class="manage-column"><?php _e('Question','tf-faq')?></th>
            	<th class="manage-column"><?php _e('Category','tf-faq')?></th>
            	<th class="manage-column"><?php _e('Email','tf-faq')?></th>
            </tr>
        </tfoot>
        <tbody>
<?php
		$questions = tffaq_unanswered_questions();
			if($questions) {
				$i=0;
				foreach($questions as $question) {
				$i++;
					$category = faq_get_category($question->category);
?>
				<tr class="<?php echo (ceil($i/2) == ($i/2)) ? "" : "alternate"; ?>">
					<th class="check-column" scope="row">
						<input type="checkbox" value="<?php echo $question->id?>" name="tffaq_question[]" />
						<input type="hidden" value="<?php echo $category->id?>" name="tffaq_category" id="tffaq_category">
				</th>
						<td>
				 			<a href="?page=tffaq-questions&amp;id=<?php echo $question->id?>&amp;cat=<?php echo $question->category?>&amp;new=new" class="row-title"><?php echo stripslashes($question->question)?></a>
								<div class="row-actions">
									<span class="edit"><a href="?page=tffaq-questions&amp;id=<?php echo $question->id?>&amp;cat=<?php echo $question->category?>&amp;new=new">Answer</a> | </span>
									<span class="delete"><a href="?page=tffaq-questions&amp;delete=<?php echo $question->id?>&amp;cat=" onclick="return confirm('<?php _e('Are you sure you want to delete this question?','tf-faq'); ?>');">Delete</a></span>
								</div>
							</td>
							<td><?php echo $category->category?></td>
							<td><?php echo $question->email?></td>
						</tr>
						<?php
					}
                } else {
?>
                	<tr><td colspan="4"><?php _e('There are no unanswered questions.','tf-faq')?></td></tr>
<?php } ?>
        </tbody>
    </table>
    <p>
    	<select name="tffaq_action2" id="tffaq_action2">
        	<option value="actions"><?php _e('Actions','tf-faq')?></option>
        	<option value="delete"><?php _e('Delete','tf-faq')?></option>
        </select>
        <input type="submit" id="tffaq_checkbox_submit2" name="tffaq_checkbox_submit2" class="button-secondary" value="<?php _e('Apply','tf-faq')?>" />
    </p>
    </form>
</div>

<script type='text/javascript' src='<?php echo plugins_url( 'admin/js/tffaq-admin.js' , __FILE__ ); ?>'></script>
<?php }