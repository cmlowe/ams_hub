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
					<h1>Search Results</h1>
	  				<?php if($wp_query->found_posts == 0): ?>
	  				<h3 class="results-count">There were no results for <span><?php echo $_GET['s']; ?></span></h3>
	  				<?php else: ?>
	  				<h3 class="results-count">Displaying <span><?php echo $wp_query->found_posts; ?></span> results for <span><?php echo $_GET['s']; ?></span></h3>
	  				<?php endif; ?>
	  				<!-- Search results loop -->			
					<div class="results-nav">
						<div class="nav-previous older"><?php previous_posts_link('Previous'); ?></div>
						<div class="nav-next newer"><?php next_posts_link('Next'); ?></div>
					</div>
					<?php while(have_posts()):the_post(); ?>
					<div class="result">
						<h2><a href="<? the_permalink() ?>"><?php the_title(); ?></a></h2>
						<?php the_content('More&raquo;'); ?>
					</div>
					<?php endwhile; ?>
					<div class="results-nav bottom">
						<div class="nav-previous older"><?php previous_posts_link('Previous'); ?></div>
						<div class="nav-next newer"><?php next_posts_link('Next'); ?></div>
					</div>
				</article>
			</div>
		</div>
  </div>
<?php get_footer(); ?>

				
  				