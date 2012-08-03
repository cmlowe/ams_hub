<?php get_header(); ?>
 	<div id="main" role="main">
		<div>
			<div>
				<nav id="breadcrumbs">
					<ul>
						<?php echo kula_display_breadcrumbs($post->ID, $post->ancestors); ?>
					</ul>
				</nav>
				<nav id="subnav">
					<ul>
						<?php echo kula_display_page_subnav($post->ID, $post->ancestors); ?>		
					</ul>
				</nav>
				<article>
					<h1><?php echo get_the_title($post->ID); ?></h1>
					<ul id="sitemap"><?php wp_list_pages("title_li="); ?></ul>
				</article>
  			</div>
  		</div>
  	</div>
<?php get_footer(); ?>	
	

  				
	  	