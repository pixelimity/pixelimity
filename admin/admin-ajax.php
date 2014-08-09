<?php

/* Define PXL, ADMIN */
define ('PXL', true, true);
define ('ADMIN', true, true);

/* Call config file */
require_once ('../config.php');

/* Action for upload images */
if (isset($_GET['action']) && $_GET['action'] == 'upload_images') :
	if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") :
		global $db;

		require_once (DIR .'/admin/classes/resize.class.php');
		
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
		$data[':portfolio_id'] = $_POST['portfolio_id'];
		
		$get_images = $db->select('portfolio_images', 'portfolio_id', $data[':portfolio_id'], ' ORDER BY image_order DESC');
		$i = $get_images['image_order'];
		
		foreach ($_FILES['images']['name'] as $name => $value) :
			$i++;
			$random = time();
			$file_name = stripslashes($_FILES['images']['name'][$name]);
			$file_size = filesize($_FILES['images']['tmp_name'][$name]);
			$extension = get_extension($file_name);
			$extension = strtolower($extension);
			
			if (in_array($extension, $valid_formats)) :
				if ($file_size < (UPLOAD_MAX_SIZE * 1024)) :
					$image_name = $random . $data[':portfolio_id'] . $i;
					$data[':image_name'] = $image_name .'.'. $extension;
					$data[':image_order'] = $i;
					$new_name = UPLOAD_DIR . $data[':image_name'];
					
					if (move_uploaded_file($_FILES['images']['tmp_name'][$name], $new_name)) :
						$data[':as_thumbnail'] = 0;
						
						$image_field_values = array($data[':portfolio_id'], 1);
						$is_image_exists = $db->row_count('portfolio', 'portfolio_id', $image_field_values, ' AND as_thumbnail = ?');
						if ($i == 1 && $is_image_exists == 0)
							$data[':as_thumbnail'] = 1;
						
						foreach (get_image_sizes() as $image) :
							$resize_image = new resize($new_name);
							$resize_image->resizeImage($image['width'], $image['height'], $image['dimension']);
							$resize_image->saveImage(UPLOAD_DIR . $image['name'] .'-'. $data[':image_name'], 100);
						endforeach;
						
						$db->insert('portfolio_images', $data);
					endif;
				endif;
			endif;
		endforeach;
	endif;
endif;

/* Action for load images */
if (isset($_POST['action']) && $_POST['action'] == 'load_images') :
	global $db;
	
	$portfolio_id = $_POST['portfolio_id'];
	$images = array();
	$get_images = $db->select_more('portfolio_images', 'portfolio_id', $portfolio_id, ' ORDER BY image_order ASC');
	
	foreach ($get_images as $image) :
		$as_thumbnail = '';
		$checked = '';
		
		if ($image['as_thumbnail'] == 1) :
			$as_thumbnail = 'as-thumbnail ';
			$checked = ' checked="checked"';
		endif;
		
		$detail = '<div class="image-item '. $as_thumbnail .' image-item-'. $image['id'] .'" id="image-'. $image['id'] .'">';
		$detail.= '<div class="action action-'. $image['id'] .'" data-id="'. $image['id'] .'">';
		$detail.= '<span class="title">'. $image['image_name'] .'<a class="delete" title="Delete this image?" data-id="'. $image['id'] .'" href="javascript:;"></a></span>';
		$detail.= '<input type="radio" name="thumbnail" value="'. $image['id'] .'" id="as-thumbnail-'. $image['id'] .'" class="radio-hidden" '. $checked .'/>';
		$detail.= '<label for="as-thumbnail-'. $image['id'] .'" class="custom-radio">As thumbnail?</label>';
		$detail.= '</div>';
		$detail.= '<img src="'. get_site_url() .'/uploads/admin-thumb-'. $image['image_name'] .'" />';
		$detail.= '</div>';
		
		$images[] = $detail;
	endforeach;
	
	$all_images = implode('', $images);
	
	echo json_encode(array('images' => $all_images, 'count' => count($images)));
endif;

/* Action for delete images */
if (isset($_POST['action']) && $_POST['action'] == 'delete_image') :
	global $db;
	
	$id = $_POST['id'];
	$portfolio_id = $_POST['portfolio_id'];
	
	$image_field_values = array($id, $portfolio_id);
	$get_image = $db->select('portfolio_images', 'id', $image_field_values, ' AND portfolio_id = ?');
	
	if ($get_image['as_thumbnail'] == 1) :
		$image_order_field_values = array($id, $portfolio_id);
		$get_image_order = $db->select('portfolio_images', 'id', $image_order_field_values, ' AND portfolio_id = ? ORDER BY image_order ASC LIMIT 1', '!=');
		$other_id = $get_image_order['id'];
		$db->update('portfolio_images', array(':as_thumbnail' => 1), 'id', array($other_id, $portfolio_id), ' AND portfolio_id = ?');
	endif;
	
	unlink(UPLOAD_DIR . $get_image['image_name']);
	
	foreach (get_image_sizes() as $image)
		unlink(UPLOAD_DIR . $image['name'] .'-'. $get_image['image_name']);

	$db->delete('portfolio_images', 'id', $id);
endif;

/* Action for order images */
if (isset($_POST['action']) && $_POST['action'] == 'order_images') :
	global $db;
	
	parse_str($_POST['images'], $image);
	$i = -1;
	
	foreach ($image['image'] as $id) :
		$i++;
		$db->update('portfolio_images', array(':image_order' => $i), 'id', $id);
	endforeach;
endif;

/* Action for install theme */
if (isset($_GET['action']) && $_GET['action'] == 'install_theme') :
	if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") :
		$file_name = $_FILES["theme"]["name"];
		$source = $_FILES["theme"]["tmp_name"];
		$type = $_FILES["theme"]["type"];
		$name = explode(".", $file_name);
		$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
		
		foreach($accepted_types as $mime_type) :
			if($mime_type == $type) :
				$continue = strtolower($name[1]) == 'zip' ? true : false;
		
				if($continue) :
					$path = DIR . '/themes/';
					$file_no_ext = basename ($file_name, '.zip');
					$file_no_ext = basename ($file_no_ext, '.ZIP');
					$target_dir = $path . $file_no_ext;
					$target_zip = $path . $file_name;
					
					 if (is_dir($target_dir)) 
						delete_files($target_dir);
					
					if(move_uploaded_file($source, $target_zip)) :
						$zip = new ZipArchive();
						$x = $zip->open($target_zip);
						
						if ($x === true) :
							$zip->extractTo($path); 
							$zip->close();
							unlink($target_zip);
						endif;
					endif;
				endif;
				
				break;
			endif;
		endforeach;
	endif;
endif;

?>