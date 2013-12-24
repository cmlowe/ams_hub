<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie9 lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie9 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie9 lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="en"> <![endif]-->
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php wp_title(); ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<?php wp_head(); ?>               
</head>         
<body>
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	<header class="container">
		<div class="row">
	 		<nav id="tool-nav" class="sixcol">
				<ul>
					<li><a href="<?php echo get_permalink(37)?>">Sitemap</a></li>
					<li><a href="<?php echo get_permalink(39)?>">Contact</a></li>
				</ul>
			</nav>
			<div id="search" class="sixcol last">
				<form action="/">
					<input type="submit" value="Search"/>
					<input type="text" name="s"/>
				</form>
			</div>
			<nav id="main-nav" class="twelvecol">
					<ul>
						<?php wp_list_pages('title_li=&include=2,15,17,19,6&sort_column=ID'); ?>
					</ul>
			</nav>
		</div>
  </header>