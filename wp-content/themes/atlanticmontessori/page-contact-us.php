<?php get_header('internal'); ?>
  <div class="container page">
		<div class="row">
			<div class="twocol sidebar">
				<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<? the_content(); ?>
				<?php endwhile; ?>
				<?php endif; ?>
			</div>
			<article class="sixcol">
				<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<h1><? the_title(); ?></h1>
				<?php endwhile; ?>
				<?php endif; ?>
				<div class="map">
					<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.ca/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=12+Flamingo+Drive,+Halifax,+NS&amp;aq=0&amp;oq=12+Flamingo+Drive,+Halifax&amp;sll=29.661766,-95.315964&amp;sspn=0.054744,0.06403&amp;ie=UTF8&amp;hq=&amp;hnear=12+Flamingo+Dr,+Halifax,+Nova+Scotia+B3M+2K3&amp;t=m&amp;ll=44.68208,-63.645344&amp;spn=0.02136,0.036478&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>				</div>
				<div class="clearfix"></div>
				<h2>Get in touch for more information</h2>
				<? echo do_shortcode('[contact-form-7 id="34" title="Contact Us"]'); ?>
			</article>
		</div>
  </div>
<?php get_footer('internal'); ?>