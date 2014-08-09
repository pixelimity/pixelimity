<?php

/* Define PXL, ADMIN */
define ('PXL', true, true);
define ('ADMIN', true, true);

/* Call config file */
require_once ('../config.php');

/* Set page data */
$data = array('current_menu' => 2);
if (is_add_new()) :
	$data = array_merge($data, array('title' => 'Add New Page', 'load_scripts' => array('page-edit')));
elseif (is_edit()) :
	$data = array_merge($data, array('title' => 'Edit Page', 'load_scripts' => array('page-edit')));
else :
	$data = array_merge($data, array('title' => 'Pages', 'load_scripts' => array()));
endif;

/* Extract page data */
extract($data);

/* Call action */
call_action('pages');

/* Start compress HTML */
ob_start('compress_html');

/* Get admin header */
get_admin_header();

/* Edit form */
if (is_add_new() || is_edit())
	require_once ('includes/page-edit-form.php');
else /* Page item lists */
	require_once ('includes/page-items.php');

/* Get admin footer */
get_admin_footer();

/* End compress HTML */
ob_end_flush();

?>