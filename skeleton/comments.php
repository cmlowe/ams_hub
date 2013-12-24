<div id="comments">
	<?php if(post_password_required()): ?>
	<p class="no_password"><?php _e('This post is password protected. Enter the password to view any comments.', 'kula'); ?></p>
	</div>
	<?php return; endif; ?>

	<?php if(have_comments()): ?>

		<?php if(get_comment_pages_count() > 1 && get_option('page_comments')): ?>
			<nav id="comment_nav_above">
				<h1 class="assistive_text"><?php _e('Comment navigation', 'kula'); ?></h1>
				<div class="nav_previous"><?php previous_comments_link(__('&larr; Older Comments', 'kula')); ?></div>
				<div class="nav_next"><?php next_comments_link(__('Newer Comments &rarr;', 'kula')); ?></div>
			</nav>
		<?php endif; ?>

		<ol class="comment_list">
			<?php wp_list_comments(array('callback' => 'kula_comment')); ?>
		</ol>

		<?php if(get_comment_pages_count() > 1 && get_option('page_comments')): ?>
		<nav id="comment_nav_below">
			<h1 class="assistive_text"><?php _e('Comment navigation', 'kula'); ?></h1>
			<div class="nav_previous"><?php previous_comments_link(__('&larr; Older Comments', 'kula')); ?></div>
			<div class="nav_next"><?php next_comments_link(__('Newer Comments &rarr;', 'kula') ); ?></div>
		</nav>
		<?php endif; ?>

	<?php elseif(!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')): ?>
		<p class="no_comments"><?php _e('Comments are closed.', 'kula'); ?></p>
	<?php endif; ?>

	<?php

	// This customizes the comment form

	/*$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields =  array(
		'author' => '<div class="comment-left"><label for="author">Name</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />',
		'email'  => '<label for="email">Email</label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />',
		'url'    => '<label for="url">Website</label><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
	);
	

	$comment_args = array(
		'fields'=>$fields,
		'comment_field'=>'<div class="comment-right"><label for="comment">Comment</label><textarea id="comment" name="comment" aria-required="true"></textarea>',
		'must_log_in'=>'<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
		'logged_in_as'=>'<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
		'comment_notes_before'=>'',
		'comment_notes_after'=>'',
		'id_form'=>'commentform',
		'id_submit'=>'submit',
		'title_reply'=>'<h4>Leave a Comment</h4>',
		'title_reply_to'=> '<h4>Leave a Reply to %s</h4>',
		'cancel_reply_link'=> '<h4>Cancel reply</h4>',
		'label_submit'=>'Comment'
		); */
	?>	

<?php comment_form($comment_args); ?>

</div>
