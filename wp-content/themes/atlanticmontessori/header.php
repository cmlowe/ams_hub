<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php _e(get_bloginfo('name'), 'kula'); ?></title>
	<link rel="shortcut icon" href="/favicon.ico">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width initial-scale=1">
	<?php wp_head(); ?>

	<script type=“text/javascript” src=“http://ajax.googleapis.com/ajax/libs/webfont/1.0.21/webfont.js” ></script>
	<!– Loads the webfont loader –>

	<script type=“text/javascript”>
	     WebFont.load({
	        monotype: {
	          projectId: '27bc9a81-0487-4fb9-99a8-c5b54a97094c'
	           // replace this with your Fonts.com Web Fonts projectId — don’t forget to          do so below as well
	        }
	     });
	</script>

	<noscript>

	<!– Use this in as a fallback to no JavaScript being available –>
	   <link type="text/css" rel="stylesheet" href="http://fast.fonts.com/cssapi/a04ff147-fb1f-43d9-8a72-a6fd71b4017f.css"/>
	</noscript>

	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-1670755-11']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
</head>
<body>
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
 	<header>
 		<div class="row nav">
 			<div class="twocol">
		 		<a href="/" class="logo"><img src="<?php _e(get_stylesheet_directory_uri()); ?>/img/logo-ams.png" alt="Atlantic Montessori School"></a>
	 		</div>
	  		<nav class="fivecol main-nav last">
				<ul>
					<?php wp_list_pages('title_li=&include=6, 14, 12, 10, 8&sort_column=ID'); ?>
				</ul>
			</nav>
		</div>
		<div class="row">
<div class="alert">
	<p>Limited number of toddler spaces available starting January 2014. <a href="<?php get_bloginfo('url') ?>/contact-us">Apply Now!</a></p>
</div>
			<div class="eightcol hero">
			<?php
				$args = array( 'post_type' => 'home_slide', 'posts_per_page' => 3 );
				$loop = new WP_Query( $args );
				while ( $loop->have_posts() ) : $loop->the_post();
					echo '<div class="slide">';
							the_post_thumbnail();
					echo '</div>';
				endwhile;				
					echo '<div class="content">';
					echo 	'<blockquote>';
					echo 		the_content();
					echo 	'</blockquote>';
					echo '</div>';					
			?>
				<a href="', get_bloginfo('url'), '/contact-us" class="cta-button">Apply Now &raquo;</a>
				<a href="#"	id="prev">&larr;</a>
				<a href="#"	id="next">&rarr;</a>
			</div>
 			<div class="onecol last"></div>
			
		</div>
	</header>