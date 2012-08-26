<footer class="container">
	<div class="row">
		<div class="onecol"></div>
		<nav class="footer-nav fivecol last">
			<ul>
				<?php wp_list_pages('title_li=&include=6, 14, 12, 10, 8&sort_column=ID'); ?>
			</ul>
		</nav>
		<div class="onecol"></div>
		<div class="twocol">
			<p>&copy; 2012 Atlantic Montessori School</p>
		</div>
		<nav class="threecol tool-nav last">
			<ul>
				<li><a href="/privacy">Privacy Policy</a></li>
				<li><a href="/sitemap">Sitemap</a></li>
			</ul>
		</nav>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>