	<footer class="container">
		<div class="row">
			<div class="twelvecol">
				<nav id="footer-nav" class="twelvecol">
						<ul>
							<?php wp_list_pages('title_li=&include=2,15,17,19,6&sort_column=ID'); ?>
						</ul>
				</nav>
				<p>&copy; <? echo date('Y'); ?></p>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>