<?php

if (is_submit('setting')) :
	global $db;
	$error = false;
	$admin = $_SESSION['ADMIN'];
	$old_admin_data = $db->select('admin', 'username', $admin['u']);
	
	$data = array();
	foreach ($_POST['data'] as $key => $value)
		$data[$key] = trim($value);
		
	$admin_data = array();
	foreach ($_POST['admin_data'] as $key => $value)
		$admin_data[':'. $key] = trim($value);
		
	if (!is_numeric($data['admin_portfolio_show']) || $data['admin_portfolio_show'] < 1)
		$data['admin_portfolio_show'] = 5;
		
	if (!is_numeric($data['admin_pages_show']) || $data['admin_pages_show'] < 1)
		$data['admin_pages_show'] = 5;
		
	if (!is_numeric($data['portfolio_show']) || $data['portfolio_show'] < 1)
		$data['portfolio_show'] = 5;
	
	if (empty($admin_data[':password']) || strlen($admin_data[':password']) < 6) 
		$admin_data[':password'] = $old_admin_data['password'];
	else
		$admin_data[':password'] = md5($admin_data[':password']);
		
	if (empty($admin_data[':email']) || !filter_var($admin_data[':email'], FILTER_VALIDATE_EMAIL)) 
		$admin_data[':email'] = $old_admin_data['email'];
		
	if ($data['home_image_size']) :
		$home_image_size = explode(',', trim($data['home_image_size']));
		
		if (!is_numeric($home_image_size[0]) || $home_image_size[0] < 1)
			$home_image_size[0] = 240;
		if (!isset($home_image_size[1]) || !is_numeric($home_image_size[1]) || $home_image_size[1] < 1)
			$home_image_size[1] = 0;
		if (!isset($home_image_size[2]) || $home_image_size[2] != 'auto' && $home_image_size[2] != 'crop')
			$home_image_size[2] = 'auto';
		
		$data['home_image_size'] = implode($home_image_size, ',');
	else :
		$data['home_image_size'] = get_option('home_image_size');
	endif;
	
	if ($data['single_image_size']) :
		$single_image_size = explode(',', trim($data['single_image_size']));
		
		if (!is_numeric($single_image_size[0]) || $single_image_size[0] < 1)
			$single_image_size[0] = 240;
		if (!isset($single_image_size[1]) || !is_numeric($single_image_size[1]) || $single_image_size[1] < 1)
			$single_image_size[1] = 0;
		if (!isset($single_image_size[2]) || $single_image_size[2] != 'auto' && $single_image_size[2] != 'crop')
			$single_image_size[2] = 'auto';
		
		$data['single_image_size'] = implode($single_image_size, ',');
	else :
		$data['single_image_size'] = get_option('single_image_size');
	endif;
		
	foreach ($data as $key => $value)
		$db->update('options', array(':value' => $value), 'option_key', $key);
	
	$db->update('admin', $admin_data, 'username', $old_admin_data['username']);
	
	$_SESSION['ADMIN'] = array('u' => $old_admin_data['username'], 'hash' => md5($old_admin_data['username'] . $admin_data[':password']));
	
	redirect_to('/admin/setting.php?message=1', true);
endif;

?>