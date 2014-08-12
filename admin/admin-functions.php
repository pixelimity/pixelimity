<?php

/* Exit if PXL not defined */
if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

function admin_css_uri() {
    return admin_url() .'/css/admin.css';
}

function is_signed_in() {
    if (isset($_SESSION['ADMIN'])) :
        return true;
    endif;
}

function is_add_new() {
    if (isset($_GET['action']) && $_GET['action'] == 'add_new') :
        return true;
    endif;
}

function is_submit($page) {
    if (isset($_POST['submit_'. $page])) :
        return true;
    endif;
}

function is_edit() {
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && !empty($_GET['id'])) :
        return true;
    endif;
}

function is_manage($value) {
    if (isset($_GET['manage']) && $_GET['manage'] == $value) :
        return true;
    endif;
}

function is_message($message) {
    if (isset($_GET['message']) && $_GET['message'] == $message) :
        return true;
    endif;
}

function is_trash() {
    if (isset($_GET['action']) && $_GET['action'] == 'trash' && isset($_GET['id']) && !empty($_GET['id'])) :
        return true;
    endif;
}

function is_untrash() {
    if (isset($_GET['action']) && $_GET['action'] == 'untrash' && isset($_GET['id']) && !empty($_GET['id'])) :
        return true;
    endif;
}

function is_delete($delete) {
    if (isset($_GET['action']) && $_GET['action'] == 'delete_'. $delete && isset($_GET['id']) && !empty($_GET['id'])) :
        return true;
    endif;
}

function is_page($page) {
    if ($_SERVER['SCRIPT_NAME'] == PATH . '/admin/'. $page .'.php') :
        return true;
    endif;
}

function is_invalid_id($table) {
    global $db;

    if (!isset($_GET['id'])) :
        return false;
    endif;

    $id = $_GET['id'];
    $field_values = array($id, 2);
    $is_id_exists = $db->row_count($table, 'id', $field_values, ' AND status != ?');
    if ($is_id_exists == 0) :
        return true;
    endif;
}

function call_action($file) {
    if (file_exists(DIR .'/admin/actions/'. $file .'.php')) :
        require_once DIR .'/admin/actions/'. $file .'.php';
    endif;
}

function body_class() {
    $class = '';

    if (is_page('index')) :
        $class = 'admin dashboard';
    elseif (is_page('portfolio')) :
        if (is_edit()) :
            $class = 'admin portfolio edit-portfolio';
        elseif (is_manage('tags')) :
            $class = 'admin portfolio tags';
        else :
            $class = 'admin portfolio';
        endif;
    elseif (is_page('pages')) :
        if (is_edit()) :
            $class = 'admin pages edit-page';
        else :
            $class = 'admin pages';
        endif;
    elseif (is_page('themes')) :
        $class = 'admin themes';
    elseif (is_page('setting')) :
        $class = 'admin setting';
    endif;

    echo 'class="'. $class . '"';
}

function get_admin_header() {
    if (file_exists(DIR .'/admin/includes/header.php')) :
        require_once DIR .'/admin/includes/header.php';
    endif;
}

function get_admin_footer() {
    if (file_exists(DIR .'/admin/includes/footer.php')) :
        require_once DIR .'/admin/includes/footer.php';
    endif;
}

function string_to_slug($string) {
    $slug = preg_replace('/[«»""!?,.!@£$%^&*{};:()><]+/', '', $string);
    $slug = strtolower(preg_replace( '/[^A-Za-z0-9-]+/', '-', $slug));

    return $slug;
}

function get_edit_data($table, $select = '', $query_param = ' AND status != 2') {
    if (!isset($_GET['id'])) :
        return false;
    endif;

    global $db;

    $id = $_GET['id'];
    $result = $db->select($table, 'id', $id, $query_param);

    if ($select) :
        return $result[$select];
    else :
        return $result;
    endif;
}

function get_admin_items($table, $per_page) {
    global $db;

    $setup_pagination = setup_pagination($per_page);
    $items = $db->select_all($table, ' ORDER BY id DESC LIMIT '. $setup_pagination['start'] .', '. $setup_pagination['per_page']);

    return $items;
}

function admin_pagination($show, $table, $page_now, $c = '?') {
    global $db;

    $setup_pagination = setup_pagination($show);
    $current_page = $setup_pagination['current_page'];
    $count = $db->row_count_all($table);
    $total_pages = ceil($count / $setup_pagination['per_page']);

    if ($current_page >= 4) :
        $start_loop = $current_page - 1;

        if ($total_pages > $current_page + 1) :
            $end_loop = $current_page + 1;
        elseif ($current_page <= $total_pages && $current_page > $total_pages - 3) :
            $start_loop = $total_pages - 3;
            $end_loop = $total_pages;
        else :
            $end_loop = $total_pages;
        endif;
    else :
        $start_loop = 1;

        if ($total_pages > 4) :
            $end_loop = 4;
        else :
            $end_loop = $total_pages;
        endif;
    endif;

    if ($total_pages > 1) :
        echo '<div class="nav clearfix">';

        if ($current_page > 1) :
            $previous = $current_page - 1;
            echo '<a href="'. get_admin_url() . '/'. $page_now . $c .'p='. $previous .'">&larr; Previous</a>';
        endif;

        for ($i = $start_loop; $i <= $end_loop; $i++) :
            if ($current_page == $i) :
                echo '<span class="current">'. $i .'</span>';
            else :
                echo '<a href="'. get_admin_url() . '/'. $page_now . $c .'p='. $i .'">'. $i .'</a>';
            endif;
        endfor;

        if ($current_page < $total_pages) :
            $next = $current_page + 1;
            echo '<a href="'. get_admin_url() . '/'. $page_now . $c .'p='. $next .'">Next &rarr;</a>';
        endif;

        echo '</div>';
    endif;
}

function get_extension($str) {
    $i = strrpos($str, ".");

    if (!$i) :
        return "";
    endif;

    $l   = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);

    return $ext;
}

function get_statistics($get) {
    global $db;

    if (!$get) :
        return false;
    endif;

    if ($get == 'published_portfolio') :
        return $db->row_count('portfolio', 'status', 0);
    elseif ($get == 'portfolio_in_draft') :
        return $db->row_count('portfolio', 'status', 1);
    elseif ($get == 'portfolio_in_trash') :
        return $db->row_count('portfolio', 'status', 2);
    elseif ($get == 'portfolio_images') :
        return $db->row_count_all('portfolio_images');
    elseif ($get == 'portfolio_tags') :
        return $db->row_count_all('portfolio_tags');
    elseif ($get == 'published_pages') :
        return $db->row_count('pages', 'status', 0);
    elseif ($get == 'pages_in_draft') :
        return $db->row_count('pages', 'status', 1);
    elseif ($get == 'pages_in_trash') :
        return $db->row_count('pages', 'status', 2);
    endif;
}

function get_portfolio_thumbnail($id) {
    global $db;

    $is_as_thumbnail_exists = $db->row_count('portfolio_images', 'portfolio_id', array($id, 1), ' AND as_thumbnail = ?');
    if ($is_as_thumbnail_exists > 0) :
        $image = $db->select('portfolio_images', 'portfolio_id', array($id, 1), ' AND as_thumbnail = ?');

        return '<img src="'. get_site_url() .'/uploads/admin-thumb-small-'. $image['image_name'] .'" alt="" />';
    endif;
}

function get_portfolio_edit_tags() {
    global $db;

    if (!isset($_GET['id'])) :
        return false;
    endif;

    $id = $_GET['id'];
    $is_tags_rel_exists = $db->row_count('portfolio_tags_rel', 'portfolio_id', $id);
    if ($is_tags_rel_exists > 0) :
        $tags = array();
        $get_tags = $db->select_more('portfolio_tags_rel', 'portfolio_id', $id);
        foreach ($get_tags as $tag) :
            $get_tag = $db->select('portfolio_tags', 'id', $tag['tag_id']);
            $tags[] = $get_tag['name'];
        endforeach;

        return implode($tags, ', ');
    endif;
}

function get_portfolio_tags_count($id) {
    global $db;

    return $db->row_count('portfolio_tags_rel', 'tag_id', $id);
}

function get_image_sizes() {
    $home_image = explode(',', get_option('home_image_size'));
    $single_image = explode(',', get_option('single_image_size'));
    $image_size = array();
    $image_size[] = array('name' => 'admin-thumb','width' => 88, 'height' => 88, 'dimension' => 'crop');
    $image_size[] = array('name' => 'admin-thumb-small','width' => 40, 'height' => 40, 'dimension' => 'crop');
    $image_size[] = array('name' => 'home-thumb','width' => $home_image[0], 'height' => $home_image[1], 'dimension' => $home_image[2]);
    $image_size[] = array('name' => 'single-thumb','width' => $single_image[0], 'height' => $single_image[1], 'dimension' => $single_image[2]);

    return $image_size;
}

function is_active_theme() {
    if (isset($_GET['action']) && $_GET['action'] == 'active' && isset($_GET['theme'])) :
        return true;
    endif;
}

function is_delete_theme() {
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['theme'])) :
        return true;
    endif;
}

function is_theme_exists($the_theme, $all = false) {
    $get_theme = array();

    $themes = get_themes('completed');

    if ($all) :
        $themes = glob(DIR .'/themes/*');
    endif;

    foreach ($themes as $theme) :
        $get_theme[] = end(explode('/', $theme));
    endforeach;

    if (!in_array($the_theme, $get_theme))  :
        return false;
    else :
        return true;
    endif;
}

function is_theme_activated($theme) {
    if (get_option('active_theme') == $theme) :
        return true;
    endif;
}

function get_themes($get) {
    $get_themes = glob(DIR .'/themes/*');
    $required_files = array('style.css', 'index.php', 'tag.php', 'search.php', '404.php', 'single.php', 'page.php');
    $themes = array();

    if ($get == 'completed') :
        foreach ($get_themes as $theme) :
            if (is_theme_required_files($theme)) :
                $themes[] = $theme;
            endif;
        endforeach;
    elseif ($get == 'not_completed') :
        foreach ($get_themes as $theme) :
            if (!is_theme_required_files($theme)) :
                $themes[] = $theme;
            endif;
        endforeach;
    endif;

    return $themes;
}

function is_theme_required_files($theme) {
    $ready = true;
    $required_files = array('style.css', 'index.php', 'tag.php', 'search.php', '404.php', 'single.php', 'page.php');

    foreach ($required_files as $file) :
        if (!file_exists($theme .'/'. $file)) :
            $ready = false;
        endif;
    endforeach;

    return $ready;
}

function delete_files($path) {
    foreach (scandir($path) as $file) :
        if ($file === '.' || $file === '..') :
            continue;
        endif;

        if (is_dir("$path/$file")) :
            delete_files("$path/$file");
        else :
            unlink("$path/$file");
        endif;

    endforeach;

    rmdir($path);
}
