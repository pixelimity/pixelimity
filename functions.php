<?php

/* Exit if PXL not defined */
if (!defined('PXL'))
	exit('You can\'t access direct script.');
	
function get_option($key) {
	global $db;
	
	$options = $db->select_all('options');
	
	foreach ($options as $option)
		if ($option['option_key'] == $key)
			return htmlspecialchars($option['value']);
}

function get_site_name() {
	return get_option('site_name');
}

function site_name() {
	echo get_site_name();
}

function get_site_url() {
	return URL;
}

function site_url() {
	echo get_site_url();
}
 
function get_admin_url() {
	return get_site_url() .'/admin';
}

function admin_url() {
	echo get_admin_url();
}

function compress_html($html) {
	return str_replace(array("\n", "\t", "\r"), "", $html);
}
 
function get_portfolio_images_count($id) {
	global $db;
	return $db->row_count('portfolio_images', 'portfolio_id', $id);
}

function setup_pagination($per_page) {
	$page = 1;
	
	if (isset($_GET['p'])) 
		$page = $_GET['p'];
		
	$current_page = $page;
	$page -= 1;
	$per_page = $per_page;
	$start = $page * $per_page;
	
	return array('current_page' => $current_page, 'start' => $start, 'per_page' => $per_page);
}

function redirect_to($uri, $self = false) {
	if ($self)
		header("Location: ". get_site_url() . $uri);
	else
		header("Location: ". $uri);
	
	exit();
}

/*
  Menamabhakan fungsi untuk memudahkan menangani kesalahan pada aplikasi menjadi terpusat dan mudah kita kembangkan
  [!] Ini hanya kerangka untuk sementara
*/

function handle_error($E_type='',$E_soloution='') {
   exit('Error Found:'.$E_type.$E_soloution);
}

?>
