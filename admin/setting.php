<?php

/* Define PXL, ADMIN */
define ('PXL', true, true);
define ('ADMIN', true, true);

/* Call config file */
require_once '../config.php';

/* Set page data */
$data = array('current_menu' => 4, 'title' => 'Setting', 'load_scripts' => array('setting'));

/* Extract page data */
extract($data);

/* Call action */
call_action('setting');

/* Start compress HTML */
ob_start();

/* Get admin header */
get_admin_header();

/* Setting form */
require_once (DIR .'/admin/includes/setting-form.php');

/* Get admin footer */
get_admin_footer();

/* End compress HTML */
ob_end_flush();
