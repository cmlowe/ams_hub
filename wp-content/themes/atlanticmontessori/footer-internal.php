<footer class="container">
	<div class="row">
		<div class="twocol"></div>
		<nav class="footer-nav threecol">
			<ul>
				<?php wp_list_pages('title_li=&include=6, 14, 12, 10, 8&sort_column=ID'); ?>
			</ul>
		</nav>
		<nav class="twocol tool-nav last">
			<ul>
				<li><a href="/sitemap">Sitemap</a></li>
			</ul>
		</nav>
	</div>
	<div class="row">
		<div class="twocol"></div>
		<div class="threecol">
			<p>&copy; 2012 Atlantic Montessori School</p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>