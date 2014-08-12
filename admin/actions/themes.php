<?php

if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

if (is_active_theme()) :
    $theme = trim($_GET['theme']);
    if (is_theme_activated($theme) || !is_theme_exists($theme)) :
        redirect_to('/admin/themes.php', true);
    else :
        global $db;
        $db->update('options', array(':value' => $theme), 'option_key', 'active_theme');
        redirect_to('/admin/themes.php?message=1', true);
    endif;
endif;

if (is_delete_theme()) :
    $theme = trim($_GET['theme']);
    if (is_theme_activated($theme) || !is_theme_exists($theme, true)) :
        redirect_to('/admin/themes.php', true);
    else :
        delete_files(DIR .'/themes/'. $theme);
        redirect_to('/admin/themes.php?message=2', true);
    endif;
endif;
