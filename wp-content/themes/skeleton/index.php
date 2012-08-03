<?php get_header(); ?>
	<div class="container">
		<section class="row">
			<!-- archives (category and date) -->
  			<div id="archives" class="threecol">
  				<nav id="categories">
  					<h2>Archives by Category</h2>
		 			<ul>
		 				<?php wp_list_categories(array('orderby'=>'name', 'title_li'=>'')); ?>
		 			</ul>
  				</nav>
  				<nav id="dates">
  					<h2>Archives by Date</h2>
		 			<ul>
		 				<?php wp_get_archives(array('type'=>'monthly', 'echo'=>1)); ?>
		 			</ul>
  				</nav>
  			</div>
  			<!-- main content -->
	  		<div class="ninecol last">
				<h1>Blog</h1>
  				<!-- main loop -->
  				<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<!-- get the categories -->
					<?php
						$categories = wp_get_object_terms($post->ID, 'category');
						$category_links = '';
						foreach($categories as $category){
							if($cat_string){
								$category_links .= ', <a href="/category/'.$category->slug.'">'.$category->name.'</a>';
							}
							else{
								$category_links = '<a href="/category/'.$category->slug.'">'.$category->name.'</a>';	
							}						
						}
					?>
					<!-- the format of the posts -->
					<article>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>	
						<small><span>Posted on </span><?php echo get_the_date('F j, Y'); ?></small>		
						
						<?php the_content('More&raquo;'); ?>									
						
						<footer>
							<div>
								<p>Posted in <a href="#"><?=$category_links?></a></p>
								
								<?php $comments = get_comments_number($post->ID); ?>
										
								<?php if($comments == 0): ?>
								<p><a href="<?=get_permalink($post->ID)?>#comments">No Comments</a></p>
								<?php elseif($comments == 1): ?>
								<p><a href="<?=get_permalink($post->ID)?>#comments"><span><?=get_comments_number($post->ID)?></span> Comment</a></p>
								<?php else: ?>
								<p><a href="<?=get_permalink($post->ID)?>#comments"><span><?=get_comments_number($post->ID)?></span> Comments</a></p>
								<?php endif; ?>
							</div>				
						</footer>
					</article>
				<?php endwhile; ?>
				<?php endif; ?>	
  				<!-- next/previous post links -->
  				<?php if($wp_query->max_num_pages > 1): ?>
					<div>
						<div><?php next_posts_link('&laquo;Older'); ?></div>
						<div><?php previous_posts_link('Newer&raquo;'); ?></div>
					</div>
				<? endif?>
			</div>
		</section>
  </div>
<?php get_footer(); ?>