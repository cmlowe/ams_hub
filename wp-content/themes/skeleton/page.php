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
					<?php if(have_posts()): while(have_posts()): the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; ?>
					<?php endif; ?>
				</article>
			</div>
		</div>
  </div>
<?php get_footer(); ?>