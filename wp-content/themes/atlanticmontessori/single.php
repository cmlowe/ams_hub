<?php get_header(); ?>
  <div id="main" role="main">
		<section>
  			<div id="archives">
  				<nav id="categories">
  					<h2>Archives by Category</h2>
		 			<ul>
		 				<?php wp_list_categories(array('orderby'=>'name', 'title_li'=>''))?>
		 			</ul>
  				</nav>
  				<nav id="dates">
  					<h2>Archives by Date</h2>
		 			<ul>
		 				<?php wp_get_archives(array('type'=>'monthly', 'echo'=>1))?>
		 			</ul>
  				</nav>
  			</div>
	  		<div>
				<article>
					<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<?php the_content(); ?>
					<?php endwhile; else: ?>
						<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
					<?php endif; ?>
					<footer>
						<h3>Comments</h3>		
						<?php comments_template(); ?>				
					</footer>
				</article>
			</div>
		</section>
  </div>
<?php get_footer(); ?>