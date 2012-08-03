<?php

if(current_user_can('administrator')) {

global $wpdb;

if(isset($_POST['faq_add_category']) and isset($_POST['faq_category_name'])) {

check_admin_referer('tffaq-add-cat');

	$category = trim(esc_attr($_POST['faq_category_name']));

	if(empty($_POST['faq_category_id'])) {

	if(empty($category)) {

	$_POST['tffaq_message'] = array('class' => 'error', 'status' => __('The New Category name can not be Blank!')) ;

	} else {

	$status = $wpdb->query("INSERT INTO ".$wpdb->prefix."faq_categories(category) VALUES('".$category."');");

	$_POST['tffaq_message'] = ($status == 1) ?
	  array('class' => 'updated', 'status' => __('A New Category has been Successfully Created'))
	: array('class' => 'error', 'status' => __('There was an Error! New Category was not Created')) ;

	}

	} else {

	$category_id = absint($_POST['faq_category_id']);

	$status = $wpdb->query("UPDATE ".$wpdb->prefix."faq_categories SET category='".$category."' WHERE id='".$category_id."'");

	$_POST['tffaq_message'] = ($status == 1) ?
		array('class' => 'updated', 'status' => __('The Category has been Successfully Updated'))
		: array('class' => 'error', 'status' => __('There was an Error! The Category was not Updated')) ;

	}
}

if(isset($_GET['delete_cat'])) {

check_admin_referer('tffaq-del-one-cat');

$questions = faq_get_questions(absint($_GET['delete_cat']));

if(empty($questions)) {

	$status = $wpdb->query("DELETE FROM ".$wpdb->prefix."faq_categories WHERE id='".absint($_GET['delete_cat'])."'");

	$_POST['tffaq_message'] = ($status == 1) ?
	    array('class' => 'updated', 'status' => __('The Category has been Successfully Deleted'))
	  : array('class' => 'error', 'status' => __('There was an Error! The Category was not Deleted')) ;

} else {

$_POST['tffaq_message'] = array('class' => 'error', 'status' => __('Questions must be moved or deleted before you can Delete this Category!')) ;

}

}

if(isset($_POST['tffaq_checkbox_submit'])) {

check_admin_referer('tffaq_checkboxes','tffaq_checkboxes');

if(isset($_POST['tffaq_category'])) {

	$action = esc_attr($_POST['tffaq_action'])	;

	if(($action == 'delete') and ($_POST['tffaq_confirm'] == 'true')) {

	$selected = $_POST['tffaq_category'];

foreach($selected as $category) {

$questions = faq_get_questions(absint($category));

 if(empty($questions)) {

	$status = $wpdb->query("DELETE FROM ".$wpdb->prefix."faq_categories WHERE id='".absint($category)."'");

	if(!$status == 1) { break;}

	} else {

	$_POST['tffaq_message'] = array('class' => 'error', 'status' => __('Questions must be moved or deleted before you can Delete these Categories!')) ;

	}

	}

	if(isset($status)) {

	$_POST['tffaq_message'] = ($status == 1) ?
	  array('class' => 'updated', 'status' => __('The Categories have been Successfully Deleted'))
	: array('class' => 'error', 'status' => __('There was an Error! The Categories have not been Deleted')) ;

	}

}

}

else {

$_POST['tffaq_message'] = array('class' => 'error', 'status' => __('There was an Error! No Categories have been Selected')) ;

}

}

if(isset($_GET['direction'])) {

$check_admin_referer = check_admin_referer('tffaq_sort', '_wpnonce');

	if($check_admin_referer == 1 ) {

	$direction = ($_GET['direction'] == 'down') ? array('>', 'ASC') : array('<', 'DESC');
	$id = intval($_GET['order-id']);

	$sort_sql = "SELECT id FROM ".$wpdb->prefix."faq_categories WHERE id".$direction[0].$id." ORDER BY id ".$direction[1]." LIMIT 0,1";
	$sort_id = $wpdb->get_results($sort_sql);
	$sort_id = $sort_id[0]->id;

	$temp = $wpdb->update( $wpdb->prefix.'faq_categories', array('id' => (0-$sort_id)) , array('id' => $sort_id)  );
	$new = $wpdb->update( $wpdb->prefix.'faq_categories', array('id' => absint($sort_id)), array('id' => $id) );
	$final = $wpdb->update( $wpdb->prefix.'faq_categories', array('id' => absint($id)), array('id' => (0-$sort_id)) );

	unset($check_admin_referer);

	}

}

?>
<div class="wrap">
<div class="icon32" id="icon-edit"><br></div>
	<h2><?php _e('Frequently Asked Questions Categories','tf-faq')?></h2>

<?php

tffaq_category_validation();

if(isset($_POST['tffaq_message'])){ ?>
<div class="<?php echo esc_attr($_POST['tffaq_message']['class']) ?>"><h4><?php echo esc_attr($_POST['tffaq_message']['status']) ?></h4></div>'
<?php
}

$new_metabox_active = false;

if(isset($_GET['new']) and $_GET['new'] === 'new') {

$new_metabox_active = true;

add_meta_box('faq_category', __('Add a Frequently Asked Questions Category'), 'faq_categoryname_meta_box', 'faq', 'normal', 'core');

?>

<form method="post" action="?page=tffaq-categories">

<?php wp_nonce_field('tffaq-add-cat', '_wpnonce', true, true ); ?>

	<div id="poststuff" class="metabox-holder">
		<?php do_meta_boxes('faq', 'normal','low'); ?>
	</div>

<input type="submit" value="<?php _e('Add new category','tf-faq'); ?>" name="faq_add_category" class="button-secondary">

</form>

<?php

}

if(isset($_GET['edit']) and $_GET['edit'] === 'true') {

add_meta_box('faq_category', __('Edit a Frequently Asked Questions Category'), 'faq_categoryname_meta_box', 'faq', 'normal', 'core');

?>

<form method="post" action="?page=tffaq-categories">

<?php wp_nonce_field('tffaq-add-cat', '_wpnonce', true, true); ?>

	<div id="poststuff" class="metabox-holder">
		<?php do_meta_boxes('faq', 'normal','low'); ?>
	</div>

<input type="submit" value="<?php _e('Edit this category','tf-faq'); ?>" name="faq_add_category" class="button-secondary">

</form>

<?php

}

?>
    <form method="post" action="" id="tffaq_cat_form">
    <?php wp_nonce_field('tffaq_checkboxes','tffaq_checkboxes'); ?>
    <input id="tffaq_confirm" type="hidden" value="false" name="tffaq_confirm">
    <p>
    	<select name="tffaq_action" id="tffaq_action">
        	<option value="actions"><?php _e('Actions','tf-faq')?></option>
        	<option value="delete"><?php _e('Delete','tf-faq')?></option>
        </select>
        <input type="submit" id="tffaq_checkbox_submit" name="tffaq_checkbox_submit" class="button-secondary" value="<?php _e('Apply','tf-faq')?>" />

<?php if(!$new_metabox_active) { ?>

		<a class="button-secondary" href="?page=tffaq-categories&amp;new=new" title=""><?php _e('Add a new category','tf-faq')?></a>

<?php } ?>

    </p>
    <table class="widefat page fixed" cellpadding="0">
    	<thead>
        	<tr>
				<th id="cb" class="manage-column column-cb check-column" style="" scope="col"><input type="checkbox"/></th>
				<th class="manage-column" style="width:10%;">&nbsp;<?php _e('ID','tf-faq')?></th>
            	<th class="manage-column"><?php _e('Category','tf-faq')?></th>
            	<th class="manage-column  scope="col" ><?php _e('Shortcode','tf-faq')?></th>
            	<th class="manage-column  scope="col" style="text-align:center;width: 10%;" ><?php _e('Sort Categories','tf-faq')?></th>
            </tr>
        </thead>
    	<tfoot>
        	<tr>
				<th id="cb" class="manage-column column-cb check-column" style="" scope="col"><input type="checkbox"/></th>
				<th class="manage-column sortable desc">&nbsp;<?php _e('ID','tf-faq')?></th>
				<th class="manage-column"><?php _e('Category','tf-faq')?></th>
            	<th class="manage-column column-tags" id="tags" scope="col"><?php _e('Shortcode','tf-faq')?></th>
            	<th class="manage-column  scope="col" style="text-align:center;" ><?php _e('Sort Categories','tf-faq')?></th>
            </tr>
        </tfoot>
        <tbody>
            	<?php
				$categories = tffaq_get_categories();
				if($categories) {
					$i=0;
					foreach($categories as $category) { $i++;
					?>
                    <tr class="<?php echo (ceil($i/2) == ($i/2)) ? "" : "alternate"; ?>">
						<th class="check-column" scope="row">
							<input type="checkbox" value="<?php echo $category->id?>" name="tffaq_category[]" />
						</th>
						<td>
						<a href="?page=tffaq-categories&amp;id=<?php echo $category->id?>&amp;edit=true" class="row-title">&nbsp;<?php echo $i; ?></a>
						    <div class="row-actions">
						    <span class="edit"><a href="?page=tffaq-categories&amp;id=<?php echo $category->id?>&amp;edit=true">Edit</a> | </span>

<?php
  $delete_url = add_query_arg( array('delete_cat'=>$category->id));
  $nonced_url = wp_nonce_url( $delete_url, 'tffaq-del-one-cat');
  ?>
<span class="delete"><a href="<?php echo $nonced_url; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a></span>

                            </div>
                        </td>
						<td>
							<a href="?page=tffaq-categories&amp;id=<?php echo $category->id?>&amp;edit=true" class="row-title"><?php echo $category->category; ?></a>
                            <div class="row-actions">
                                <span class="edit"><a href="?page=tffaq-categories&amp;id=<?php echo $category->id?>&amp;edit=true">Edit</a> | </span>

<?php
  $delete_url = add_query_arg( array('delete_cat'=>$category->id));
  $nonced_url = wp_nonce_url( $delete_url, 'tffaq-del-one-cat');
  ?>
<span class="delete"><a href="<?php echo $nonced_url; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a></span>

                            </div>
                        </td>

 <td class="row-title"><a>[faq id="<?php echo $category->id; ?>"]</a> or <a>[faq name="<?php echo $category->category; ?>"]<a></td>

 <td class="row-title" style="text-align:center;">

	<?php if($i!=1) { ?>
	<?php $up_url = add_query_arg( array('order-id'=>$category->id, 'direction' => 'up')); ?>
	<?php $nonce_url = wp_nonce_url( $up_url , 'tffaq_sort' ); ?>
	<a href="<?php echo $nonce_url ?>">&#x25B2;</a>
	<?php } ?>

	<?php if($i != count($categories)) { ?>
	<?php $down_url = add_query_arg( array('order-id'=>$category->id, 'direction' => 'down')); ?>
	<?php $nonce_url = wp_nonce_url( $down_url , 'tffaq_sort' ); ?>
	<a href="<?php echo $nonce_url ?>">&#x25BC;</a>
	<?php } ?>

 </td>
 </tr>
				<?php
					}
                } else {
				?>
                	<tr><td colspan="2"><?php _e('You did not create any categories yet.','tf-faq')?> <a href="?page=tffaq-categories&amp;new=new"><?php _e('Create one','tf-faq') ?></a></td></tr>
                <?php
				}
				?>
        </tbody>
    </table>
    <p>
    	<select name="tffaq_action2" id="tffaq_action2">
        	<option value="actions"><?php _e('Actions','tf-faq')?></option>
        	<option value="delete"><?php _e('Delete','tf-faq')?></option>
        </select>
        <input type="submit" id="tffaq_checkbox_submit2" name="tffaq_checkbox_submit2" class="button-secondary" value="<?php _e('Apply','tf-faq')?>" />

<?php if(!$new_metabox_active) { ?>

    <a class="button-secondary" href="?page=tffaq-categories&amp;new=new" title=""><?php _e('Add a new category','tf-faq')?></a>

<?php } ?>

    </p>
    </form>
</div>
<script type='text/javascript' src='<?php echo plugins_url( 'admin/js/tffaq-admin.js' , __FILE__ ); ?>'></script>
<?php }