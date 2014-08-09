<?php

/* Define PXL */
define ('PXL', true, true);

/* Call required files */
require_once ('config.php');
require_once (DIR .'/includes/themes.php');

/* Run the theme */
$run = true;

/**
 * Required theme files
 * Stop run theme if file don't exists
 */
$required_theme_files = array('index', 'single', 'tag', 'search', 'page', '404');
foreach ($required_theme_files as $file)
	if (!file_exists(DIR .'/themes/'. get_option('active_theme') .'/'. $file .'.php')) 	
		$run = false;

/* Display notification if theme isn't running */
if (!$run) 
	exit('Your required theme files are not completed. <br />Your theme must be required with these files: index.php, single.php, tag.php, search.php, page.php, 404.php');
	
/* Call theme files if theme running */
if (is_404()) 
	require_once (DIR .'/themes/'. get_option('active_theme') .'/404.php');
elseif (is_single())
	require_once (DIR .'/themes/'. get_option('active_theme') .'/single.php');
elseif (is_tag())
	require_once (DIR .'/themes/'. get_option('active_theme') .'/tag.php');
elseif (is_page())
	require_once (DIR .'/themes/'. get_option('active_theme') .'/page.php');
elseif (is_search())
	require_once (DIR .'/themes/'. get_option('active_theme') .'/search.php');
else
	require_once (DIR .'/themes/'. get_option('active_theme') .'/index.php');

?>