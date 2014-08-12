<?php

if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

if (is_edit() && is_invalid_id('pages')) :
    redirect_to('/admin/pages.php', true);
endif;

if (isset($_POST['submit_page'])) :
    global $db;

    $data = array();
    
    foreach ($_POST['data'] as $key => $value) :
        $data[':'. $key] = trim($value);
    endforeach;

    $get_recent_id = $db->select_by_order('pages', ' ORDER BY id DESC LIMIT 1', 'id');
    $id = $get_recent_id + 1;
    
    if (!is_edit()) :
        $data[':id'] = $id;
    endif;

    $data[':publish_date'] = time();

    $data[':slug'] = string_to_slug($data[':title']);
    $data[':seo_follow'] = (isset($data[':seo_follow']) && $data[':seo_follow'] == 'on') ? 0 : 1;
    $data[':seo_index'] = (isset($data[':seo_index']) && $data[':seo_index'] == 'on') ? 0 : 1;
    $data[':status'] = (isset($data[':status']) || empty($data[':title'])) ? 1 : 0;

    $old_slug = '';

    if (is_edit()) :
        $id = get_edit_data('pages', 'id');
        $old_slug = get_edit_data('pages', 'slug');

        if (isset($_POST['published']) && $_POST['published'] == 0) :
            $data[':publish_date'] = get_edit_data('pages', 'publish_date');
        endif;
    endif;

    $is_slug_exists = $db->row_count('pages', 'slug', $data[':slug']);
    if ($old_slug != $data[':slug'] && $is_slug_exists == 1) :
        $data[':slug'] = $data[':slug'] .'-'. $id;
    endif;
    if (is_edit()) :
        $db->update('pages', $data, 'id', $id);
    else :
        $db->insert('pages', $data);
    endif;

    if (is_edit()) :
        redirect_to('/admin/pages.php?action=edit&id='. $id .'&message=3', true);
    else :
        if ($data[':status'] == 1) :
            redirect_to('/admin/pages.php?action=edit&id='. $id .'&message=2', true);
        else :
            redirect_to('/admin/pages.php?action=edit&id='. $id .'&message=1', true);
        endif;
    endif;

endif;

if (is_trash()) :
    global $db;

    $id = $_GET['id'];

    $is_id_exists = $db->row_count('pages', 'id', array($id, 2), ' AND status != ?');
    if ($is_id_exists == 0) :
        redirect_to('/admin/pages.php', true);
    else :
        $db->update('pages', array(':status' => 2), 'id', $id);
        redirect_to('/admin/pages.php?message=1', true);
    endif;
endif;

if (is_untrash()) :
    global $db;

    $id = $_GET['id'];

    $is_id_exists = $db->row_count('pages', 'id', array($id, 2), ' AND status = ?');
    if ($is_id_exists == 0) :
        redirect_to('/admin/pages.php', true);
    else :
        $db->update('pages', array(':status' => 1), 'id', $id);
        redirect_to('/admin/pages.php?message=2', true);
    endif;
endif;

if (is_delete('page')) {
    global $db;

    $id = $_GET['id'];

    $is_id_exists = $db->row_count('pages', 'id', $id);
    if ($is_id_exists == 0) :
        redirect_to('/admin/pages.php', true);
    else :
        $db->delete('pages', 'id', $id);
        redirect_to('/admin/pages.php?message=3', true);
    endif;
}
