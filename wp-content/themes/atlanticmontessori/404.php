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
					<h1>Page Not Found - 404</h1>
  					<p>Sorry, but the page you were trying to view does not exist.</p>
			    	<p>It looks like this was the result of either:</p>
			    	<ul>
			      		<li>a mistyped address</li>
			      		<li>an out-of-date link</li>
			    	</ul>
			    	<p>Try returning to the <a href="/">homepage</a> or using the search above.</p>
				</article>
			</div>
		</div>
  </div>
<?php get_footer(); ?>

				