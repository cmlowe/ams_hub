<?php
	
	function kula_comment($comment, $args, $depth){
		$GLOBALS['comment'] = $comment;
		switch($comment->comment_type):
			case 'pingback' :
			case 'trackback' :
		?>
		
		<li class="post pingback">
			<p><?php _e('Pingback:', 'kula'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'kula'), '<span class="edit-link">', '</span>'); ?></p>
		<?php
				break;
			default :
		?>
		
		<li class="comment">
			<div id="comment-<?php comment_ID(); ?>" class="comment_left">
				<p>From: <strong><?php echo get_comment_author_link()?></strong></p>
				<small><?php echo get_comment_date()?></small>
				<?php //get_comment_link($comment->comment_ID)?>
				<div class="reply">
					<?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'kula'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
					<?php edit_comment_link(__('Edit', 'kula'), '<span class="edit_link">', '</span>')?>
				</div>
			</div>

			<?php if($comment->comment_approved == '0'): ?>
				<em class="comment_awaiting_moderation"><?php _e('Your comment is awaiting moderation.', 'kula'); ?></em>
				<br />
			<?php endif; ?>

			<div class="comment_right"><?php comment_text(); ?></div>

			
		</li>

		<?php
				break;
		endswitch;
	}

?>