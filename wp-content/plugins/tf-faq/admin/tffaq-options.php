<?php

if(current_user_can('administrator')) {

if(!empty($_POST)) {

if(is_email($_POST['tffaq-email'])) {
      update_option('faq-email',$_POST['tffaq-email']);

      echo '<div class="updated settings-error"><h4>'.__('Options have been successfully updated').'</h4></div>';
}

}

?>

<div class="wrap">
	<div class="icon32" id="icon-edit"><br></div>

	<h2><?php _e('Frequently Asked Questions Options','tf-faq')?></h2>

    <table class="form-table">
		<thead>
		<tr class="alternate">
			<td><h3><?php _e('When a user submits a question, send an e-mail to this address:','tf-faq'); ?></h3></td>
		</tr>
		</thead>
		<tbody>
		<form action="" method="post">
		<tr class="alternate">
		<td>
		<input class="regular-text" type="text" name="tffaq-email" id="tffaq-email" value="<?php echo get_option('faq-email'); ?>" />
		<input style="display:block;margin:10px 0;" type="submit" name="tffaq-options" id="tffaq-options" value="<?php _e('Save changes','tf-faq')?>" class="button-secondary" />
		</td>
		</tr>
		</form>
		</tbody>
	</table>

	<table class="form-table">
		<thead>
		<tr class="alternate">
			<td><h3><?php _e('How to: Shortcodes','tf-faq')?></h3></td>
		</tr>
		</thead>
		<tbody>
		<tr class="alternate">
			<td>
			<ul>
				<li>To allow users to ask a question insert <code>[faq_ask]</code> in a page or post where you want the form to appear.</li>
			    <li>To allow users to search the faq insert <code>[tffaq_search]</code> in a page or post where you want the search box to appear.</li>
			    <li style="color:red;"><strong>The shortcode: <code>[faq_search]</code> has been depreciated. It will still work but you should upgrade it to the new <code>[tffaq_search]</code> to insure future compatability</strong></li>
			    <li>Use the shortcode: <code>[faq id="1"]</code> where 1 is the category id to show all FAQs in that category.</li>
			    <li>Use the shortcode: <code>[faq_name name="General"]</code> where General is the category name to show all FAQs in that category.</li>
			    <li>Use the shortcode: <code>[faq_all]</code> to show all categories.</li>
			    <li>Use the shortcode: <code>[tffaq_tabs]</code> to show everything in the tabbed interface.</li>
			    <li style="color:red;"><strong>The shortcode: <code>[tfa_tabs]</code> has been depreciated. It will still work but you should upgrade it to the new <code>[tffaq_tabs]</code> to insure future compatability</strong></li>
			</ul>
			</td>
			</tr>
			</tbody>
	</table>

	<table class="form-table">
		<thead>
		<tr class="alternate">
			<td><h3><?php _e('How to: CSS','tf-faq')?></h3></td>
		</tr>
		</thead>
		<tbody>
		<tr class="alternate">
			<td>
			<ul>
				<li>You can add a stylesheet named <code>tffaq.css</code>in your default stylesheet folder (same folder as <code>styles.css</code>) to over ride the default styles and add your own.</li>
			</ul>
			</td>
		</tr>
		</tbody>
	</table>
</div>
<?php }