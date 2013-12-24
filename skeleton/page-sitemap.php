<?php get_header(); ?>
 	<div class="container">
		<div class="row">
			<div class="twelvecol">
				<nav id="breadcrumbs">
					<ul>
						<?php echo kula_display_breadcrumbs($post->ID, $post->ancestors); ?>
					</ul>
				</nav>
			</div>
			<div class="threecol">
				<nav id="subnav">
					<ul>
						<?php echo kula_display_page_subnav($post->ID, $post->ancestors); ?>		
					</ul>
				</nav>
			</div>
			<div class="ninecol last">
				<article>
					<h1><?php echo get_the_title($post->ID); ?></h1>
					<ul id="sitemap"><?php wp_list_pages("title_li="); ?></ul>
				</article>
			</div>
  	</div>
 	</div>
<?php get_footer(); ?>	
	

  				
	  	