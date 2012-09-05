<?php get_header('internal'); ?>
  <div class="container page">
		<div class="row">
			<? $children = get_pages('child_of='.$post->ID); ?>
			<? if( count( $children ) != 0 ): ?>
			<nav id="subnav" class="twocol">
				<ul>
					<?php echo kula_display_page_subnav($post->ID, $post->ancestors); ?>		
				</ul>
			</nav>
			<article class="fivecol last">
				<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<h1><? the_title(); ?></h1>
					<?php the_content(); ?>
				<?php endwhile; ?>
				<?php endif; ?>
			</article>
			<? else: ?>

			<article class="sevencol">
				<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<h1><? the_title(); ?></h1>
					<?php the_content(); ?>
				<?php endwhile; ?>
				<?php endif; ?>
			</article>
			<? endif ?>
		</div>
  </div>
<?php get_footer('internal'); ?>