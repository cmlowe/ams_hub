<?php get_header('internal'); ?>
  <div class="container page">
		<div class="row">
			<article class="sevencol">
				<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<h1><? the_title(); ?></h1>
					<?php the_content(); ?>
				<?php endwhile; ?>
				<?php endif; ?>
			</article>
		</div>
  </div>
<?php get_footer(); ?>