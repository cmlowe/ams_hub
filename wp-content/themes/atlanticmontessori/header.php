<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php _e(get_bloginfo('name'), 'kula'); ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width initial-scale=1">
	<?php wp_head(); ?>
</head>
<body>
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
 	<header>
 		<div class="row nav">
 			<div class="twocol">
		 		<a href="/" class="logo"><img src="<?php _e(get_stylesheet_directory_uri()); ?>/img/logo-ams.png" alt="Atlantic Montessori School"></a>
	 		</div>
	  		<nav class="sixcol main-nav last">
				<ul>
					<?php wp_list_pages('title_li=&include=6, 14, 12, 10, 8&sort_column=ID'); ?>
				</ul>
			</nav>
		</div>
		<div class="row">
			<div class="eightcol hero">
			<?php
				$args = array( 'post_type' => 'home_slide', 'posts_per_page' => 1 );
				$loop = new WP_Query( $args );
				while ( $loop->have_posts() ) : $loop->the_post();
					the_post_thumbnail();
					echo '<div class="content">';
					echo 	'<blockquote>';
					echo 		the_content();
					echo 	'<p class="author">', the_title(), '</p>';
					echo 	'</blockquote>';
					echo '</div>';
					echo '<a href="', get_bloginfo('url'), '/contact-us" class="cta-button">Apply Now &raquo;</a>';
					
				endwhile;
			?>			
			</div>
 			<div class="onecol last"></div>
			
		</div>
	</header>