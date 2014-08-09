<?php

/* Define PXL, ADMIN */
define ('PXL', true, true);
define ('ADMIN', true, true);

/* Call config file */
require_once ('../config.php');

/* Set page data */
$data = array('current_menu' => 3, 'title' => 'Themes', 'load_scripts' => array('theme-items'));

/* Extract page data */
extract($data);

/* Call action */
call_action('themes');

/* Start compress HTML */
ob_start('compress_html');

/* Get admin header */
get_admin_header();

/* Get theme item lists */
require_once (DIR .'/admin/includes/theme-items.php');

/* Get admin footer */
get_admin_footer();

/* End compress HTML */
ob_end_flush();

?>