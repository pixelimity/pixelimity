<?php

/* Exit if PXL not defined */
if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

/* Start the session */
session_start();

/* ------------------------------------------------------------------------------------------------------ \
                                        HELPER
\ ------------------------------------------------------------------------------------------------------ */

/**
 * isset( $_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' # port 443 is not always for ssl
 * load balancer fix that has x forwarder protocol set with. add $_SERVER['HTTPS'] -> 'on' value
 */
if (isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/* GETTING PROTOCOL 
----------------------------------------- */
$protocol = ( isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https' ) ||
            ( isset( $_SERVER['HTTPS']) && ( strtolower( $_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1' ) )
            ? 'https' : 'http';

/* GETTING DOCUMENT ROOT USING REALPATH AVOID SYMLINK
----------------------------------------- */
$docroot = function_exists('realpath') ? realpath($_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT'];
// clean path ( windows directory separator fix )
$docroot = rtrim(str_replace( array( "\\", "\\\\" ), "/", $docroot ), '/');

/* GETTING DOCUMENT ROOT USING REALPATH AVOID DIR SYMLINK IF PROVIDE
----------------------------------------- */
$base    = function_exists('realpath') ? realpath(dirname(__FILE__)) : dirname(__FILE__);
$base    = str_replace( array( "\\", "\\\\" ), "/", $base );

/* GETTING HOST AS DOMAIN
----------------------------------------- */
$host    = ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : false );
// if no host exits
if( ! $host ) :
    header( 'HTTP/1.1 400 Bad Request');
    die('Server Not Supported, HTTP_HOST / SERVER_NAME is not defined!');
endif;

/* ------------------------------------------------------------------------------------------------------ \
                                        END HELPER
\ ------------------------------------------------------------------------------------------------------ */

/* Define some parameters */
define ('BASE', $base);
define ('PATH', '/'.trim(str_replace( $docroot, '', BASE ), '/') );
define ('DIR',  $base );
define ('URL',  rtrim( $protocol.'://'. $host.PATH, '/' ) );
define ('UPLOAD_DIR', DIR .'/uploads/', true);
define ('UPLOAD_MAX_SIZE', 20000, true);

/* Get config file if installed */
if (file_exists(DIR . '/install/config.php')) :
    require (DIR .'/install/config.php');
else : /* Install if config file isn't exists */
    header("Location: ". URL ."/install");
endif;

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

if (defined('ADMIN')) :
    require (DIR .'/admin/admin-functions.php');
endif;

if (get_option('active_theme') && file_exists(DIR .'/themes/'. get_option('active_theme') .'/functions.php')) :
    require (DIR .'/themes/'. get_option('active_theme') .'/functions.php');
endif;

/* Redirect to sign in page if user not signed in */
if (defined('ADMIN') && !is_signed_in() && $_SERVER['SCRIPT_NAME'] != PATH .'/admin/signin.php') :
    redirect_to('/admin/signin.php?return='. urlencode(str_replace(PATH, '', $_SERVER['REQUEST_URI'])), true);
endif;
