<?php
// create custom plugin settings menu
add_action('admin_menu', 'kula_create_menu');

function kula_create_menu() {

	//create new top-level menu
	add_menu_page('Social Media Settings', 'Social Media Settings', 'administrator', __FILE__, 'social_media_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_social_media_settings' );
}


function register_social_media_settings() {
	//register our settings
	register_setting( 'social-media-settings-group', '_social_media_facebook' );
    register_setting( 'social-media-settings-group', '_social_media_twitter' );
    register_setting( 'social-media-settings-group', '_social_media_linkdin' );
    register_setting( 'social-media-settings-group', '_social_media_youtube' );
}

function social_media_page() {
?>
<div class="wrap">
<h2>Social Media Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'social-media-settings-group' ); ?>
    <?php do_settings_sections( 'social-media-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Facebook</th>
        <td><input type="text" name="_social_media_facebook" value="<?php echo get_option('_social_media_facebook'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Twitter</th>
        <td><input type="text" name="_social_media_twitter" value="<?php echo get_option('_social_media_twitter'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">LinkdIn</th>
        <td><input type="text" name="_social_media_linkdin" value="<?php echo get_option('_social_media_linkdin'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Youtube</th>
        <td><input type="text" name="_social_media_youtube" value="<?php echo get_option('_social_media_youtube'); ?>" /></td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } 