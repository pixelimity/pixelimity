<?php

/* Exit if PXL not defined */
if (!defined('PXL'))
	exit('You can\'t access direct script.');
	
/* Start the session */

// Akan Pindah Ke Session class
session_start();

/*
Berpikir untuk membuat Dir Class, misalnya agar session_start(); tidak berulang dipanggil disetiap halaman
*/

 // Kita Mulai Dengan Menfatur Lokasi Baru
 
  // Default SEPARATOR
 define('DS', DIRECTORY_SEPARATOR);
 // The base direktori of app
 define('D_BASE', dirname(__FILE__).DS); // $_SERVER['DOCUMENT_ROOT']
 // The core directori
 define('CORE_DIR', D_BASE. 'core' .DS);
 // Class directori
 define('CLASS_DIR', CORE_DIR. 'class' .DS);

/* Define some parameters */
define ('BASE', str_replace("\\", "/", dirname(__FILE__)), true);
define ('PATH', str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '', BASE));
define ('DIR', BASE);
define ('URL', 'http://'. $_SERVER['HTTP_HOST'] . PATH);
define ('UPLOAD_DIR', DIR .'/uploads/', true);
define ('UPLOAD_MAX_SIZE', 20000, true);

/* Get config file if installed */
if (file_exists(DIR . '/install/config.php'))
	require (DIR .'/install/config.php');
else /* Install if config file isn't exists */
	header("Location: ". URL ."/install");

/* Define database parameters */
define ('DB_HOST', $config['db_host'], true);
define ('DB_NAME', $config['db_name'], true);
define ('DB_USER', $config['db_user'], true);
define ('DB_PASSWORD', $config['db_password'], true);
	
/* Run database */
require (DIR .'/db.php');
$db = new DB();

/* Call functions file */
require (DIR .'/functions.php');

if (defined('ADMIN'))
	require (DIR .'/admin/admin-functions.php');

if (get_option('active_theme') && file_exists(DIR .'/themes/'. get_option('active_theme') .'/functions.php'))
	require (DIR .'/themes/'. get_option('active_theme') .'/functions.php');
	
/* Redirect to sign in page if user not signed in */
if (defined('ADMIN') && !is_signed_in() && $_SERVER['SCRIPT_NAME'] != PATH .'/admin/signin.php')
	redirect_to('/admin/signin.php?return='. urlencode(str_replace(PATH, '', $_SERVER['REQUEST_URI'])), true);

?>
