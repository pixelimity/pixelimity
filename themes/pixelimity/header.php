<!DOCTYPE html>

<!-- BEGIN html -->
<html>

	<!-- BEGIN head -->
	<head>
	
		<!-- Meta Tags -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width" />
		
		<?php seo_meta(); ?>
	
		<title><?php pxl_title(); ?></title><!-- title -->
		
		<!-- Stylesheet and Favicon -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo theme_stylesheet_uri(); ?>" />
		<link rel="shortcut icon" href="<?php echo theme_directory_uri(); ?>/images/favicon.png" />
		
		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<!-- jQuery -->
		<script type="text/javascript" src="<?php echo theme_directory_uri(); ?>/js/jquery.min.js"></script>
		
	<!-- END head -->
	</head>
	
	<!-- BEGIN body -->
	<body <?php body_class(); ?>>
		
		<div id="menu-placeholder"></div>
	
		<!-- BEGIN .container -->
		<div class="container">
	
			<!-- BEGIN #header.clearfix -->
			<header id="header" class="clearfix">
			
				<div class="logo">
					<a href="<?php echo site_url(); ?>"><img src="<?php echo theme_directory_uri(); ?>/images/logo.png" alt="<?php echo get_option('name'); ?>" /></a>
				</div>
				
				<?php if (has_pages()) : ?>
					<div class="main-menu">
						<nav class="menu">
							<a href="javascript:;" class="toggle-menu">Menu</a>
							<ul>
								<?php page_lists(); ?>
							</ul>
						</nav>
					</div>
				<?php endif; ?>
				
				<?php if (is_home()) { ?>	
					<div class="intro clearfix">
						<div class="title">Hello. Welcome. Willkommen. Bonjour!</div>
						<div class="description">I'm Johny Doe, a Graphic Designer and Photographer. Welcome to my personal portfolio.</div>
					</div>
				<?php } ?>
			
			<!-- END #header.clearfix -->
			</header>