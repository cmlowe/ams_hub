<footer class="container">
	<div class="row">
		<nav class="threecol footer-nav">
			<ul>
				<?php wp_list_pages('title_li=&include=6, 14, 12, 10, 8&sort_column=ID'); ?>
			</ul>
		</nav>
		<nav class="threecol tool-nav last">
			<p>&copy; 2012 Atlantic Montessori School</p>
			<ul>
				<li><a href="/privacy">Privacy Policy</a></li>
				<li><a href="/sitemap">Sitemap</a></li>
			</ul>
		</nav>
	</div>
	<div class="footer-border"></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>