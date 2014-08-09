<?php

/* Define PXL, ADMIN */
define ('PXL', true, true);
define ('ADMIN', true, true);

/* Call config file */
require_once ('../config.php');

/* Set page data */
$data = array('current_menu' => 1);
if (is_add_new()) :
	$data = array_merge($data, array('title' => 'Add New Portfolio', 'load_scripts' => array('portfolio-edit')));
elseif (is_edit()) :
	$data = array_merge($data, array('title' => 'Edit Portfolio', 'load_scripts' => array('portfolio-edit', 'portfolio-images')));
elseif (is_manage('tags')) :
	$data = array_merge($data, array('title' => 'Portfolio Tags', 'load_scripts' => array('portfolio-tags')));
else :
	$data = array_merge($data, array('title' => 'Portfolio', 'load_scripts' => array()));
endif;

/* Extract page data */
extract($data);

/* Call action */
call_action('portfolio');

/* Start compress HTML */
ob_start('compress_html');

/* Get admin header */
get_admin_header();

/* Edit form */
if (is_add_new() || is_edit())
	require_once ('includes/portfolio-edit-form.php');
elseif (is_manage('tags')) /* Portfolio tag lists */
	require_once ('includes/portfolio-tags.php');
else /* Portfolio item lists */
	require_once ('includes/portfolio-items.php');

/* Get admin footer */
get_admin_footer();

/* End compress HTML */
ob_end_flush();

?>