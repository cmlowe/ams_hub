/* GALLERY -------------------------------------------------- */

	jQuery(document).ready(function(){
		jQuery('.gallery_add_new').click(function(){
			var append 	= '<p>';
				append +=   '<a href="#" class="remove button">Remove Image</a>';
				append +=	'<label>Image</label>';
				append +=	'<input type="text" name="images[image][]" size="25" /><br/>';
				append +=	'<label>Caption</label>';
				append +=	'<input type="text" name="images[caption][]" size="25" />';
				append += '</p>';
			jQuery('.gallery_container').append(append);
			return false;
		});
		
		jQuery('.remove').live('click',function(){
			jQuery(this).parent().remove();
			return false;
		});
	});