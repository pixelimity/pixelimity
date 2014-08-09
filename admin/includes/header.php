<?php

if (!defined('PXL'))
	exit('You can\'t access direct script.');

global $title;

?>

<!DOCTYPE html>

<html>

	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow" />
		
		<title><?php echo $title; ?> &lsaquo; Admin ~ <?php site_name(); ?></title>
		
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo admin_css_uri(); ?>" />
		<link rel="shortcut icon" href="<?php admin_url(); ?>/images/favicon.png" />
		
		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<script type="text/javascript" src="<?php admin_url(); ?>/js/jquery.min.js"></script>
	
	</head>
	
	<body <?php body_class(); ?>>
	
		<span class="load" id="loaded">Loaded</span>
		<span class="load" id="loading">Loading...</span>
		
		<?php require_once ('menu.php'); ?>
		
		<div id="main">