<?php
/*
Plugin Name: RPS Image Gallery
Plugin URI: http://redpixel.com/rps-image-gallery-plugin
Description: An image gallery with caption support and ability to link to a slideshow or alternate URL. 
Version: 1.2.14
Author: Red Pixel Studios
Author URI: http://redpixel.com/
License: GPL3
*/

/* 	Copyright (C) 2011 - 2012  Red Pixel Studios  (email : support@redpixel.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful, HI hi
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * An image gallery with caption support and ability to link to a slideshow or alternate URL.
 *
 * @package rps-image-gallery
 * @author Red Pixel Studios
 * @version 1.2.14
 */

if ( ! class_exists( 'RPS_Image_Gallery', false ) ) :

class RPS_Image_Gallery {

	public function __construct() {
		add_action( 'init', array( &$this, 'cb_init' ) );
		add_action( 'wp_footer', array( &$this, 'cb_footer_styles_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'cb_enqueue_styles_scripts' ) );
		
		add_filter( 'attachment_fields_to_edit', array( &$this, 'f_media_edit_gallery_link' ), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( &$this, 'f_media_save_gallery_link' ), 10, 2 );
		
		add_filter( 'attachment_fields_to_edit', array( &$this, 'f_media_edit_gallery_link_target' ), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( &$this, 'f_media_save_gallery_link_target' ), 10, 2 );
	}

	/*
	 * Add the gallery_link field to the page for editing.
	 *
	 * @since version 1.2
	 */
	public function f_media_edit_gallery_link( $fields, $post ) {
		if ( stristr( $post->post_mime_type, 'image' ) === false ) return $fields;
		
		$fields['post_gallery_link'] = array(
			'label' => 'Gallery Link URL',
			'value' => esc_attr( get_post_meta( $post->ID, '_rps_attachment_post_gallery_link', true ) ),
			'input' => 'text',
			'helps' => 'Enter a relative or absolute link that should be followed when the image is clicked<br />from within an image gallery.'
		);
	
		return $fields;
	}

	/*
	 * Add the gallery_link_target field to the page for editing
	 *
	 * @since version 1.2.6
	 */
	public function f_media_edit_gallery_link_target( $fields, $post ) {
		if ( stristr( $post->post_mime_type, 'image' ) === false ) return $fields;
		
		$target = get_post_meta( $post->ID, '_rps_attachment_post_gallery_link_target', true );
		$options_inner_html = '';
		
		$options = array(
			'_self',
			'_blank',
			'_parent',
			'_top'
		);
		
		foreach ( $options as $option ) :
			$selected = ( $target == $option ) ? 'selected="selected"' : '';
			$default = ( $option == '_self' ) ? ' (default)' : '';
			$options_inner_html .= '<option value="' . $option . '"' . $selected . '>' . $option . $default . '</option>';
		endforeach;
		
		$fields['post_gallery_link_target'] = array(
			'label' => 'Gallery Link Target',
			'value' => $target,
			'input' => 'html',
			'html' => '<select name="attachments[' . $post->ID . '][post_gallery_link_target]" id="attachments[' . $post->ID . '][post_gallery_link_target]">' . $options_inner_html . '</select>',
			'helps' => 'Select the target for the Gallery Link URL.'
		);
	
		return $fields;
	}
	
	/*
	 * Save the gallery_link field.
	 *
	 * @since version 1.2
	 */
	public function f_media_save_gallery_link( $post, $fields ) {
		if ( !isset( $fields['post_gallery_link'] ) ) return $post;

		$safe_url = trim( $fields['post_gallery_link'] );
		if ( empty( $safe_url ) ) {
			if ( get_post_meta( $post['ID'], '_rps_attachment_post_gallery_link', true ) ) {
				delete_post_meta( $post['ID'], '_rps_attachment_post_gallery_link' );
			}
			return $post;
		}
		
		$safe_url = esc_url( $safe_url );
		if ( empty( $safe_url ) ) return $post;
		
		update_post_meta( $post['ID'], '_rps_attachment_post_gallery_link', $safe_url );
		
		return $post;
	}

	/*
	 * Save the gallery_link_target field.
	 *
	 * @since version 1.2.6
	 */
	public function f_media_save_gallery_link_target( $post, $fields ) {
		if ( !isset( $fields['post_gallery_link_target'] ) ) return $post;
		
		if ( empty( $fields['post_gallery_link_target'] ) ) {
			if ( get_post_meta( $post['ID'], '_rps_attachment_post_gallery_link_target', true ) ) {
				delete_post_meta( $post['ID'], '_rps_attachment_post_gallery_link_target' );
			}
			return $post;
		}
		
		update_post_meta( $post['ID'], '_rps_attachment_post_gallery_link_target', $fields['post_gallery_link_target'] );
		
		return $post;
	}

	/*
	 * Return the gallery_link field.
	 *
	 * @since version 1.2
	 */
	public function get_gallery_link( $attachment_id ) {
		return get_post_meta( $attachment_id, '_rps_attachment_post_gallery_link', true );
	}

	/*
	 * Return the gallery_link_target field.
	 *
	 * @since version 1.2
	 */
	public function get_gallery_link_target( $attachment_id ) {
		return get_post_meta( $attachment_id, '_rps_attachment_post_gallery_link_target', true );
	}

	public function cb_init() {
		add_shortcode( 'rps-image-gallery', array( &$this, 'cb_gallery_shortcode' ) );
		add_shortcode( 'gallery', array( &$this, 'cb_gallery_shortcode' ) );
		
		wp_register_style( 'rps-image-gallery-fancybox', plugins_url( 'dependencies/fancybox/jquery.fancybox-1.3.4.css', __FILE__ ), false, '1.0.0' );
		wp_register_style( 'rps-image-gallery', plugins_url( 'rps-image-gallery.css', __FILE__ ), array( 'rps-image-gallery-fancybox' ), '1.2.8' );
		
		wp_register_script( 'rps-image-gallery-easing', plugins_url( 'dependencies/fancybox/jquery.easing-1.3.pack.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
		wp_register_script( 'rps-image-gallery-fancybox', plugins_url( 'dependencies/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__ ), array( 'rps-image-gallery-easing' ), '1.0.0', true );
	}
	
	public function cb_enqueue_styles_scripts() {
		wp_enqueue_style( 'rps-image-gallery' );
	}
		
	public function cb_gallery_shortcode( $atts, $content = null ) {
	
		global $post;
		$str_output = '';
		$attachments = array();

		/*
		 * Specify allowed values for shortcode attributes.
		 */
		$allowed_columns_min = 1;
		$allowed_columns_max = 9;
		
		$allowed_align = array( 
			'left',
			'center',
			'right'
		);
		
		$allowed_orderby = array( 
			'menu_order',
			'title',
			'post_date',
			'rand',
			'ID',
			'post__in'
		);
		
		$allowed_order = array(
			'asc',
			'desc'
		);
		
		$allowed_headingtag = array(
			'h2',
			'h3',
			'h4',
			'h5',
			'h6'
		);
		
		$allowed_link = array(
			'permalink',
			'file',
			'none'
		);
		
		$allowed_container = array(
			'div',
			'p',
			'span'
		);
		
		/*
		 * Allowed values for fancybox shortcode attributes.
		 */
		$allowed_fb_transition_in = array(
			'elastic',
			'fade',
			'none'
		);
		
		$allowed_fb_transition_out = array(
			'elastic',
			'fade',
			'none'
		);
		
		$allowed_fb_title_position = array(
			'outside',
			'inside',
			'over'
		);
		
		$allowed_fb_speed_in_min = 100;
		$allowed_fb_speed_in_max = 1000;
		
		$allowed_fb_speed_out_min = 100;
		$allowed_fb_speed_out_max = 1000;

		/*
		 * Specify defaults for shortcode attributes.
		 */
		$defaults = array(
			'columns' => '3',
			'align' => 'left',
			'id' => get_the_id(),
			'ids' => '',
			'size' => 'thumbnail',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'link' => 'permalink',
			'size_large' => 'large',
			'group_name' => 'rps-image-group-' . $post->ID,
			'container' => 'div',
			'heading' => 'false', // true allows the title of the attachment to be displayed above the caption in grid view
			'headingtag' => 'h2',
			'caption' => 'false', // false = default
			'slideshow' => 'true', // false causes 'gallery link' to be used
			'include' => '',
			'exclude' => '',
			
			// fancybox attributes
			'fb_title_show' => 'true',
			'fb_transition_in' => 'none',
			'fb_transition_out' => 'none',
			'fb_title_position' => 'over',
			'fb_speed_in' => 300,
			'fb_speed_out' => 300,
			'fb_show_close_button' => 'true',
			'fb_title_counter_show' => 'true',
			'fb_cyclic' => 'true',
			'fb_center_on_scroll' => 'true'
		);
		
		if ( ! empty( $atts['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $atts['orderby'] ) )
				$atts['orderby'] = 'post__in';
			$atts['include'] = $atts['ids'];
		}
		
		$shortcode_atts = shortcode_atts( $defaults, $atts );
		extract( $shortcode_atts, EXTR_SKIP );
				
		// convert string values to lowercase and trim
		$align = trim( strtolower( $align ) );
		$size = trim( strtolower( $size ) );
		$orderby = trim( strtolower( $orderby ) );
		$order = trim( strtolower( $order ) );
		$link = trim( strtolower( $link ) );
		$size_large = trim( strtolower( $size_large ) );
		$group_name = trim( strtolower( $group_name ) );
		$container = trim( strtolower( $container ) );
		$heading = trim( strtolower( $heading ) );
		$headingtag = trim( strtolower( $headingtag ) );
		$caption = trim( strtolower( $caption ) );
		$slideshow = trim( strtolower( $slideshow ) );
		$fb_title_show = trim( strtolower( $fb_title_show ) );
		$fb_transition_in = trim( strtolower( $fb_transition_in ) );
		$fb_transition_out = trim( strtolower( $fb_transition_out ) );
		$fb_title_position = trim( strtolower( $fb_title_position ) );
		$fb_show_close_button = trim( strtolower( $fb_show_close_button ) );
		
		// type cast strings as necessary
		$columns = absint( $columns );
		$gallery_ids = $this->process_gallery_id( $id );
		$heading = ( $heading == 'true' ) ? true : false;
		$caption = ( $caption == 'true' ) ? true : false;
		$slideshow = ( $slideshow == 'true' ) ? true : false;
		$fb_title_show = ( $fb_title_show == 'true' ) ? true : false;
		$fb_speed_in = absint( $fb_speed_in );
		$fb_speed_out = absint( $fb_speed_out );
		$fb_show_close_button = ( $fb_show_close_button == 'true' ) ? true : false;
		$fb_title_counter_show = ( $fb_title_counter_show == 'true' ) ? true : false;
		$fb_cyclic = ( $fb_cyclic == 'true' ) ? true : false;
		$fb_center_on_scroll = ( $fb_center_on_scroll == 'true' ) ? true : false;

		// test for allowed values
		$columns = max( $allowed_columns_min, min( $allowed_columns_max, $columns ) );
		if ( !in_array( $align, $allowed_align ) ) $align = $defaults['align'];
		if ( !in_array( $orderby, $allowed_orderby ) ) $orderby = $defaults['orderby'];
		if ( !in_array( $order, $allowed_order ) ) $order = $defaults['order'];
		if ( !in_array( $headingtag, $allowed_headingtag ) ) $headingtag = $defaults['headingtag'];
		if ( !in_array( $link, $allowed_link ) ) $link = $defaults['link'];
		if ( !in_array( $container, $allowed_container ) ) $container = $defaults['container'];
		if ( !in_array( $fb_transition_in, $allowed_fb_transition_in ) ) $fb_transition_in = $defaults['fb_transition_in'];
		if ( !in_array( $fb_transition_out, $allowed_fb_transition_out ) ) $fb_transition_out = $defaults['fb_transition_out'];
		if ( !in_array( $fb_title_position, $allowed_fb_title_position ) ) $fb_title_position = $defaults['fb_title_position'];
		$fb_speed_in = max( $allowed_fb_speed_in_min, min( $allowed_fb_speed_in_max, $fb_speed_in ) );
		$fb_speed_out = max( $allowed_fb_speed_out_min, min( $allowed_fb_speed_out_max, $fb_speed_out ) );
		
		// Safely parse include and exclude for use with get_posts().
		$include = trim( ( ( $ids !== '' ) ? $ids : $include ) );
		$exclude = trim( $exclude );
		
		$include_arr = ( ! empty( $include ) ) ? explode( ',', $include ) : array();
		$exclude_arr = ( ! empty( $exclude ) ) ? explode( ',', $exclude ) : array();
		
		$include_arr = array_map( 'trim', $include_arr );
		$include_arr = array_map( 'absint', $include_arr );
		
		$exclude_arr = array_map( 'trim', $exclude_arr );
		$exclude_arr = array_map( 'absint', $exclude_arr );
				
		// You can't use include and exclude at the same time, so we'll let exclude trump include.
		if ( ! empty( $exclude_arr ) && ! empty( $include_arr ) ) $include_arr = array();
				
		/*
		 * Make sure that the attachment ids are not being provided alongside gallery ids
		 * since this will cause the gallery to be output more than once.
		 */
		if ( ! empty( $ids ) ) : // attachment ids were specified and should be used (WordPress 3.5)

			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'numberposts' => -1,
				'post_status' => null,
				'post_parent' => ( ( empty( $include ) ) ? $id : '' ),
				'order' => $order,
				'orderby' => $orderby,
				'include' => $include_arr,
				'exclude' => $exclude_arr
			) );
		
		else : // process the galleries as normal
		
			foreach ( $gallery_ids as $id ) :
				$post_attachments = get_posts( array(
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'numberposts' => -1,
					'post_status' => null,
					'post_parent' => $id,
					'order' => $order,
					'orderby' => $orderby,
					'include' => $include_arr,
					'exclude' => $exclude_arr
				) );
				$attachments = array_merge( $attachments, $post_attachments );
			endforeach;

			if ( empty( $attachments ) ) return '';
			$attachments = $this->reorder_merged_attachments( $attachments, $orderby, $order );
			
		endif;
				
		/**
		 * Determine if fancybox should be loaded if the user wants a slideshow
		 * if so, store shortcode information for later use when outputting dynamic javascript
		 */
		if ( $slideshow ) {
			$this->slideshows[] = compact(
				'group_name',
				'fb_title_show',
				'fb_transition_in',
				'fb_transition_out',
				'fb_title_position',
				'fb_speed_in',
				'fb_speed_out',
				'fb_show_close_button',
				'fb_title_counter_show',
				'fb_cyclic',
				'fb_center_on_scroll'
			);
		}
		
		$quantity = count( $attachments );
		
		/**
		 * The outer wrapper for the gallery.
		 */
		$str_output .= '<' . $container . ' class="rps-image-gallery gallery-columns-' . $columns . ' gallery-size-' . $size . '" style="text-align:' . $align . '">';
		
		$str_output .= '<ul>';
		
		/**
		 * Initialize the counter that is used while looping through the attachments
		 * to set classes on the list items and images.
		 */
		$i = 0;

		foreach ( $attachments as $attachment ) {
			$i++;
			$str_href = '';
			$str_rel = '';
			$str_title = $str_heading = strip_tags( $attachment->post_title );
			$str_alt_text = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
			$str_caption = $attachment->post_excerpt;
			$arr_image_small = wp_get_attachment_image_src( $attachment->ID, $size );
			$arr_image_large = wp_get_attachment_image_src( $attachment->ID, $size_large );
			$str_class = '';
			$gallery_heading = '';
			$gallery_caption = '';
			$gallery_target = '';
			$str_target = get_post_meta( $attachment->ID, '_rps_attachment_post_gallery_link_target', true );
			
			/*
			 * Fall back to using the title if the alt text is not provided for accessibility requirements.
			 */
			if ( $str_alt_text == '' ) $str_alt_text = $str_title;
			
			/*
			 * Flag the last image with a class for the slideshow just in case the slideshow option is set to true.
			 */
			if ( $i == $quantity ) $str_class = ' class="last"';
			
			/*
			 * The gallery-icon class is default for WordPress so we preserve it.
			 * Add gallery-icon-end-row and gallery-icon-begin-row classes to assist with override styling.
			 * If the counter modulus the number of columns equals zero then append the end-row class.
			 * However if there is only one column required then there is no need to evaluate modulus.
			 */
			if ( $i % $columns == 0 and $columns > 1 ) :
				$str_output .= '<li class="gallery-icon gallery-icon-end-row">';
			/*
			 * If the counter modulus the number of columns equals one then append the begin-row class.
			 */
			elseif ( $i % $columns == 1 and $columns > 1 ) :
				$str_output .= '<li class="gallery-icon gallery-icon-begin-row">';
			/*
			 * Otherwise just output the default gallery-icon class.
			 */
			else :
				$str_output .= '<li class="gallery-icon">';
			endif;
			
			/*
			 * Get the value of the gallery link field of the attachment.
			 */
			if ( $this->get_gallery_link( $attachment->ID ) != '' ) $str_href = $this->get_gallery_link( $attachment->ID );
						
			/*
			 * Check if the slideshow shortcode attribute is true and the href is empty.
			 * If so, link to the larger version of the image and group it with the other images.
			 * This, in effect, makes the image part of the slideshow through the rel tag.
			 */
			if ( $slideshow and $str_href == '' ) :

				$str_href = $arr_image_large[0];
				$str_rel = ' rel="' . $group_name . '"';

			/* 
			 * If no slideshow, then check to see if the href is set and if not then use
			 * the "Link thumbnails to" setting. A value of "file" links to the full size
			 * version of the image, while a value of "permalink" links to the attachment template.
			 */
			else:
				if ( $str_href == '' ) :
					if($link == 'file'):
						$str_href = $arr_image_large[0];
					elseif ($link == 'permalink'):
						$str_href = get_attachment_link($attachment->ID);
					endif;
				else:
					/*
					 * If the gallery link is defined check and set the gallery link target.
					 * If the target is _self or empty then no need to output since it is the default behavior.
					 */
					$gallery_target = ( $str_target == '_self' or $str_target == '' ) ? '' : ' target="' . $str_target . '"';
				endif;
			endif;

			/*
			 * Determine what strings need to be used for the title attribute of the HREF and the caption.
			 * Each image has the possibility of having a Title, Alternate Text and Caption.
			 * The attachment title is already set to $str_title and is replaced if any of the
			 * other values are populated in order of precedence Caption, Alt Text then Title.
			 */
			if ( $str_caption != '' ) :
				$str_title = strip_tags( $str_caption );
			elseif ( $str_alt_text != '' ) :
				$str_title = $str_alt_text;
			endif;
			
			/*
			 * If the heading is set to show then add it to the str_title so that it can also show in the slideshow
			 */
			if ( $heading ) $str_title = $str_heading . ' &mdash; ' . $str_title;
			
			/* 
			 * If the slideshow is set to false, and the link value is set to none and the href is empty,
			 * just output the gallery image and don't link it to anything, otherwise output the image link.
			 */
			if ( !$slideshow && $link == 'none' && $str_href == '' ) :
				$str_output .= '<img' . $str_class . ' alt="' . $str_alt_text . '" src="' . $arr_image_small[0] . '" title="' . $str_title . '" />';
			else :
				$str_output .= '<a' . $str_rel . ' href="' . $str_href . '" title="' . $str_title . '"' . $gallery_target . '><img' . $str_class . ' alt="' . $str_alt_text . '" src="' . $arr_image_small[0] . '" /></a>';
			endif;
			
			/*
			 * Define the gallery heading tag if the heading is set to true.
			 */
			if ( $heading ) $gallery_heading = '<' . $headingtag . ' class="wp-heading-text gallery-heading">' . $str_heading . '</' . $headingtag . '>';
			
			/*
			 * Define the gallery caption tag if caption is set to true.
			 * Note that the wp-caption-text and gallery-caption classes are default for WordPress.
			 */
			if ( $caption ) $gallery_caption = '<span class="wp-caption-text gallery-caption">' . $str_caption . '</span>';
			
			$str_output .= $gallery_heading . $gallery_caption . '</li>';
			
		}
		
		$str_output .= '</ul>';
		
		$str_output .= '</' . $container . '>';
		
		return $str_output;
	}
	
	/**
	 * @since version 1.2.9
	 * @return array IDs of the posts from which to source the attachments
	 *
	 * The gallery id can contain a string including a single integer or can be a
	 * series of integers that is delimited by a comma. We remove duplicate IDs to
	 * prevent a single gallery from appearing more than once. We also check to see
	 * if the integer returned is zero and if so assume that a non integer was provided.
	 */
	private function process_gallery_id( $id ) {
		$ids_sanitized = array();
		
		$ids = explode( ',', $id );
		
		foreach ( $ids as $id ) :
			$id = absint( trim( $id ) );
			if ( $id > 0 ) $ids_sanitized[] = $id;
		endforeach;
		
		return array_unique( $ids_sanitized );
		
	}
	
	/**
	 * @since version 1.2.9
	 * @return array Resorted array of attachments as objects.
	 *
	 * Possible orderby values are 'menu_order', 'title', 'post_date' or 'random'.
	 * If 'random' is used then we just need to shuffle the array of attachments.
	 */
	private function reorder_merged_attachments( $attachments, $orderby, $order ) {
		$menu_order = $title = $post_date = array();
		if ( $orderby == 'rand' ) :
			shuffle( $attachments );
		else :
			foreach ( $attachments as $key => $row ) :
				$menu_order[$key] = $row->menu_order;
				$title[$key] = $row->post_title;
				$post_date[$key] = $row->post_modified_gmt;
			endforeach;
		
			switch ( $orderby ) {
				case 'menu_order' :
					$resort_orderby = $menu_order;
					break;
				case 'title' :
					$resort_orderby = $title;
					break;
				case 'post_date' :
					$resort_orderby = $post_date;
					break;
			}
			
			array_multisort( $resort_orderby, ( ( $order == 'asc' ) ? SORT_ASC : SORT_DESC ), $attachments );
		endif;
		
		return $attachments;
	}
	
	/*
	 * Output the necessary styles and scripts in the footer.
	 *
	 * @since version 1.2
	 */
	public function cb_footer_styles_scripts () {
		if ( empty( $this->slideshows ) ) return;
		wp_print_scripts( 'rps-image-gallery-fancybox' );
		
		?>
		<script type="text/javascript">
			;jQuery.noConflict();
	
			( function( $, window ) {
			var document = window.document;
			$( document ).ready( function() {
				<?php foreach ( $this->slideshows as $slideshow ) { ?>
				    $('a[rel="<?php echo $slideshow['group_name']; ?>"]').fancybox({
				    	'titleShow' : <?php echo ( $slideshow['fb_title_show'] ) ? 'true' : 'false'; ?>,
						'transitionIn' : '<?php echo $slideshow['fb_transition_in']; ?>',
						'transitionOut' : '<?php echo $slideshow['fb_transition_out']; ?>',
						'titlePosition' : '<?php echo $slideshow['fb_title_position']; ?>',
						'speedIn' : <?php echo $slideshow['fb_speed_in']; ?>,
						'speedOut' : <?php echo $slideshow['fb_speed_out']; ?>,
						'showCloseButton' : <?php echo ( $slideshow['fb_show_close_button'] ) ? 'true' : 'false'; ?>,
						'cyclic' : <?php echo ( $slideshow['fb_cyclic'] ) ? 'true' : 'false'; ?>,
						'centerOnScroll' : <?php echo ( $slideshow['fb_center_on_scroll'] ) ? 'true' : 'false'; ?>,
					<?php if ( $slideshow['fb_title_counter_show'] ) : ?>
						'titleFormat' : function(title, currentArray, currentIndex, currentOpts) { return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>'; },
					<?php else : ?>
						'titleFormat' : function(title, currentArray, currentIndex, currentOpts) { return '<span id="fancybox-title-over">' + (title.length ? title : '') + '</span>'; }
					<?php endif; ?>
					});
				<?php } ?>
			});
			
			} )( jQuery, window );
		</script>
	<?php }
	
	private $slideshows = array();
}

if ( ! isset( $rps_image_gallery ) ) $rps_image_gallery = new RPS_Image_Gallery;

endif;

?>