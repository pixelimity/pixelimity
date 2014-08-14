<?php

/* Exit if PXL not defined */
if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

/* Check portfolio id if user want to edit */
if (is_edit() && is_invalid_id('portfolio')) :
    redirect_to('/admin/portfolio.php', true);
endif;

/* Action for submit portfolio */
if (is_submit('portfolio')) :
    global $db;

    /* Set data */
    $data = array();
    foreach ($_POST['data'] as $key => $value) :
        $data[':'. $key] = trim($value);
    endforeach;

    /* Set id for add new portfolio */
    $get_recent_id = $db->select_by_order('portfolio', ' ORDER BY id DESC LIMIT 1', 'id');
    $id = $get_recent_id + 1;
    if (!is_edit()) :
        $data[':id'] = $id;
    endif;

    /* Reset data */
    $data[':publish_date'] = time();
    $data[':slug'] = string_to_slug($data[':title']);
    $data[':seo_follow'] = (isset($data[':seo_follow']) && $data[':seo_follow'] == 'on') ? 0 : 1;
    $data[':seo_index'] = (isset($data[':seo_index']) && $data[':seo_index'] == 'on') ? 0 : 1;
    $data[':status'] = (isset($data[':status']) || empty($data[':title'])) ? 1 : 0;

    /* Set old slug empty */
    $old_slug = '';

    if (is_edit()) :
        $id = get_edit_data('portfolio', 'id');
        $old_slug = get_edit_data('portfolio', 'slug');

        if (isset($_POST['published']) && $_POST['published'] == 0) :
            $data[':publish_date'] = get_edit_data('portfolio', 'publish_date');
        endif;

    endif;

    /* If slug exists */
    $is_slug_exists = $db->row_count('portfolio', 'slug', $data[':slug']);
    if ($old_slug != $data[':slug'] && $is_slug_exists == 1) :
        $data[':slug'] = $data[':slug'] .'-'. $id;
    endif;

    $thumbnail = (isset($_POST['thumbnail'])) ? $_POST['thumbnail'] : '';
    $tags = (isset($_POST['tags'])) ? trim($_POST['tags']) : '';

    if (is_edit()) :
        if ($thumbnail) :
            $reset_thumbnail = array(':as_thumbnail' => 0);
            $update_thumbnail = array(':as_thumbnail' => 1);
            $thumbnail_field_values = array($id, $thumbnail);

            $db->update('portfolio_images', $reset_thumbnail, 'portfolio_id', $id);
            $db->update('portfolio_images', $update_thumbnail, 'portfolio_id', $thumbnail_field_values, ' AND id = ?');
        endif;

        $db->update('portfolio', $data, 'id', $id);
    else :
        $db->insert('portfolio', $data);
    endif;

    if ($tags) :
        $tags = explode(',', $tags);

        $db->delete('portfolio_tags_rel', 'portfolio_id', $id);

        foreach ($tags as $tag) :
            $tag_data[':name'] = trim($tag);

            if ($tag_data[':name']) :
                $tag_data[':slug'] = string_to_slug($tag_data[':name']);
                $is_tag_slug_exists = $db->row_count('portfolio_tags', 'slug', $tag_data[':slug']);

                if ($is_tag_slug_exists == 0) :
                    $get_recent_tag_id = $db->select_by_order('portfolio_tags', ' ORDER BY id DESC LIMIT 1', 'id');
                    $tag_id = $get_recent_tag_id + 1;
                    $tag_data[':id'] = $tag_id;
                    $db->insert('portfolio_tags', $tag_data);
                else :
                    $get_tag = $db->select('portfolio_tags', 'slug', $tag_data[':slug']);
                    $tag_id = $get_tag['id'];
                endif;

                $tag_field_values = array($id, $tag_id);
                $is_tags_rel_exists = $db->row_count('portfolio_tags_rel', 'portfolio_id', $tag_field_values, ' AND tag_id = ?');
                if ($is_tags_rel_exists == 0) :
                    $tags_rel_data = array(':portfolio_id' => $id, ':tag_id' => $tag_id);
                    $db->insert('portfolio_tags_rel', $tags_rel_data);
                endif;
            endif;
        endforeach;
    endif;

    if (is_edit()) :
        redirect_to('/admin/portfolio.php?action=edit&id='. $id .'&message=3', true);
    else :
        if ($data[':status'] == 1) :
            redirect_to('/admin/portfolio.php?action=edit&id='. $id .'&message=2', true);
        else :
            redirect_to('/admin/portfolio.php?action=edit&id='. $id .'&message=1', true);
        endif;
    endif;
endif;

if (is_trash()) :
    global $db;

    $id = $_GET['id'];

    $is_id_exists = $db->row_count('portfolio', 'id', array($id, 2), ' AND status != ?');
    if ($is_id_exists == 0) :
        redirect_to('/admin/portfolio.php', true);
    else :
        $db->update('portfolio', array(':status' => 2), 'id', $id);
        redirect_to('/admin/portfolio.php?message=1', true);
    endif;
endif;

if (is_untrash()) :
    global $db;

    $id = $_GET['id'];

    $is_id_exists = $db->row_count('portfolio', 'id', array($id, 2), ' AND status = ?');
    if ($is_id_exists == 0) :
        redirect_to('/admin/portfolio.php', true);
    else :
        $db->update('portfolio', array(':status' => 1), 'id', $id);
        redirect_to('/admin/portfolio.php?message=2', true);
    endif;
endif;

if (is_delete('portfolio')) :
    global $db;

    $id = $_GET['id'];

    $is_id_exists = $db->row_count('portfolio', 'id', $id);
    if ($is_id_exists == 0) :
        redirect_to('/admin/portfolio.php', true);
    else :
        $portfolio_images = $db->select_more('portfolio_images', 'portfolio_id', $id);
        foreach ($portfolio_images as $images) :
            unlink(UPLOAD_DIR . $images['image_name']);

            foreach (get_image_sizes() as $image) :
                unlink(UPLOAD_DIR . $image['name'] .'-'. $images['image_name']);
            endforeach;
        endforeach;

        $db->delete('portfolio', 'id', $id);
        $db->delete('portfolio_tags_rel', 'portfolio_id', $id);
        $db->delete('portfolio_images', 'portfolio_id', $id);

        redirect_to('/admin/portfolio.php?message=3', true);
    endif;
endif;

if (isset($_POST['add_tag']) || isset($_POST['update_tag'])) :
    global $db;

    $the_tag = trim($_POST['tag']);

    if (isset($_POST['add_tag'])) :
        if ($the_tag) :
            $tags = explode(',', $the_tag);
            $add = false;

            foreach ($tags as $tag) :
                $data[':name'] = trim($tag);

                if ($data[':name']) :
                    $data[':slug'] = string_to_slug($data[':name']);

                    $is_tag_slug = $db->select('portfolio_tags', 'slug', $data[':slug']);
                    if ($is_tag_slug == 0) :
                        $add = true;
                        $get_recent_tag_id = $db->select_by_order('portfolio_tags', ' ORDER BY id DESC LIMIT 1', 'id');
                        $data[':id'] = $get_recent_tag_id + 1;
                        $db->insert('portfolio_tags', $data);
                    endif;
                endif;
            endforeach;

            if ($add) :
                redirect_to('/admin/portfolio.php?manage=tags&message=1', true);
            else :
                redirect_to('/admin/portfolio.php?manage=tags', true);
            endif;
        endif;
    elseif (isset($_POST['update_tag'])) :
        $data[':name'] = $the_tag;
        $old_tag = trim($_POST['old_tag']);
        $old_tag_id = trim($_POST['old_tag_id']);

        if ($data[':name']) :
            $data[':slug'] = string_to_slug($data[':name']);

            $is_tag_slug_exists = $db->row_count('portfolio_tags', 'slug', $data[':slug']);
            if ($data[':slug'] != $old_tag && $is_tag_slug_exists == 0) :
                $db->update('portfolio_tags', $data, 'id', $old_tag_id);
            endif;
        endif;

        redirect_to('/admin/portfolio.php?manage=tags&message=2', true);
    endif;
endif;

if (is_delete('tag')) :
    global $db;

    $id = $_GET['id'];

    $is_id_exists = $db->select('portfolio_tags', 'id', $id);
    if ($is_id_exists == 0) :
        redirect_to('/admin/portfolio.php?manage=tags', true);
    else :
        $db->delete('portfolio_tags_rel', 'tag_id', $id);
        $db->delete('portfolio_tags', 'id', $id);
        redirect_to('/admin/portfolio.php?manage=tags&message=3', true);
    endif;
endif;
