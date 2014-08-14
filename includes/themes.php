<?php

global $db;
$error_404 = false;
$get_portfolio = null;
$get_page = null;
$setup_pagination = setup_pagination(get_option('portfolio_show'));

if (is_single()) :
	$slug = $_GET['portfolio'];
	$is_slug_exists = $db->row_count('portfolio', 'slug', array($slug, 0), ' AND status = ?');
	if ($is_slug_exists == 0) :
		$error_404 = true;
	else :
		$get_portfolio = $db->select('portfolio', 'slug', array($slug, 0), ' AND status = ?');
	endif;
elseif (is_tag()) :
	$slug = $_GET['tag'];
	$is_slug_exists = $db->row_count('portfolio_tags', 'slug', $slug);
	if ($is_slug_exists == 0) :
		$error_404 = true;
	else :
		$get_tag = $db->select('portfolio_tags', 'slug', $slug);
		$get_tags = $db->select_more('portfolio_tags_rel', 'tag_id', $get_tag['id'], ' ORDER BY id DESC LIMIT '. $setup_pagination['start'] .', '. $setup_pagination['per_page']);
		$get_portfolio = array();
		foreach ($get_tags as $tag) :
			$get_portfolio[] = $db->select('portfolio', 'id', array($tag['portfolio_id'], 0), ' AND status = ?');
		endforeach;
	endif;
elseif (is_search()) :
	$query = $_GET['search'];
	$get_portfolio = $db->select_all('portfolio', " WHERE status = ? AND title LIKE '%$query%' ORDER BY id DESC LIMIT ". $setup_pagination['start'] .", ". $setup_pagination['per_page'], array(0));
elseif (is_page()) :
	$slug = $_GET['page'];
	$is_slug_exists = $db->row_count('pages', 'slug', array($slug, 0), ' AND status = ?');
	if ($is_slug_exists == 0) :
		$error_404 = true;
	else :
		$get_page = $db->select('pages', 'slug', array($slug, 0), ' AND status = ?');
	endif;
else :
	$get_portfolio = $db->select_all('portfolio', ' WHERE status = ? ORDER BY id DESC LIMIT '. $setup_pagination['start'] .', '. $setup_pagination['per_page'], array(0));
endif;

if (count($get_portfolio) < 1 && count($get_page) < 1) 
	$error_404 = true;

$page = $get_page;
$all_portfolio = $get_portfolio;
$portfolio = null;
$portfolio_count = 0;
$portfolio_index = 0;

function is_home() {
	if (!is_single() && !is_page() && !is_404())
		return true;
}

function is_single() {
	if (isset($_GET['portfolio']))
		return true;
}

function is_tag() {
	if (isset($_GET['tag']))
		return true;
}

function is_search() {
	if (isset($_GET['search']))
		return true;
}

function is_page() {
	if (isset($_GET['page']))
		return true;
}

function is_404() {
	global $error_404;
	
	if ($error_404 || isset($_GET['error']))
		return true;
}

function have_portfolio() {
	global $all_portfolio, $portfolio_count, $portfolio_index;
	
	if ($all_portfolio && ($portfolio_index <= $portfolio_count)) :
		$portfolio_count = count($all_portfolio) - 1;
		return true;
	else :
		$portfolio_count = 0;
		return false;
	endif;
}

function the_portfolio() {
	global $all_portfolio, $portfolio, $portfolio_count, $portfolio_index;
	
	if ($portfolio_index > $portfolio_count)
		return false;
	
	$portfolio = $all_portfolio[$portfolio_index];
	$portfolio_index++;
	
	return $portfolio;
}

function get_the_ID($other = '') {
	global $all_portfolio, $portfolio, $page;
	
	if (is_single())
		$portfolio = $all_portfolio;
	
	if ($other == 'page') 
		return $page['id'];
	else
		return $portfolio['id'];
}

function the_ID($other = '') {
	echo get_the_ID($other);
}

function get_permalink() {
	global $all_portfolio, $portfolio;
	
	if (is_single()) 
		$portfolio = $all_portfolio;
		
	return get_site_url() .'/portfolio/'. $portfolio['slug'] .'/';
}

function the_permalink() {
	echo get_permalink();
}

function get_the_title($other = '') {
	global $all_portfolio, $portfolio, $page;
	
	if (is_single())
		$portfolio = $all_portfolio;
	
	if ($other == 'page')
		return $page['title'];
	else
		return $portfolio['title'];
}

function the_title($other = '') {
	echo get_the_title($other);
}

function get_the_description($other = '') {
	global $all_portfolio, $portfolio, $page;
	
	if (is_single())
		$portfolio = $all_portfolio;
	
	require_once (DIR .'/includes/parsedown.class.php');
	$parsedown = new Parsedown();
	
	if ($other == 'page') :
		return $parsedown->text($page['description']);
	else :
		return $parsedown->text($portfolio['description']);
	endif;
}

function the_description($other = '') {
	echo get_the_description($other);
}

function get_the_excerpt($string_limit = 150, $other = '') {
	if ($string_limit < strlen(get_the_description($other)))
		return substr(strip_tags(get_the_description($other)), 0, $string_limit) .' ...';
	else
		return strip_tags(get_the_description($other));
}

function the_excerpt($string_limit = 150, $other = '') {
	echo get_the_excerpt($string_limit, $other);
}

function get_the_meta($get, $other = '') {
	global $all_portfolio, $portfolio, $page;
	
	if (is_single()) 
		$portfolio = $all_portfolio;
		
	if ($other == 'page')
		return $page[$get];
	else
		return $portfolio[$get];
}

function the_meta($get, $other = '') {
	echo get_the_meta($get, $other = '');
}

function has_tags() {
	global $db, $portfolio;
	
	$id = $portfolio['id'];
	$is_portfolio_id_exists = $db->select('portfolio_tags_rel', 'portfolio_id', $id);
	if ($is_portfolio_id_exists > 0)
		return true;
}

function get_the_tags($divider = ', ') {
	global $db, $portfolio;
	
	$id = $portfolio['id'];
	$tags = $db->select_more('portfolio_tags_rel', 'portfolio_id', $id);
	$tag = array();
	foreach ($tags as $the_tag) :
		$get_tag = $db->select('portfolio_tags', 'id', $the_tag['tag_id']);
		$tag[] = '<a href="'. get_site_url() .'/tag/'. $get_tag['slug'] .'/">'. $get_tag['name'] .'</a>';
	endforeach;
		
	return implode($tag, $divider);
}

function the_tags($divider = ', ') {
	echo get_the_tags($divider);
}

function has_portfolio_thumbnail() {
	global $db, $portfolio;

	$id = $portfolio['id'];
	$is_as_thumbnail_exists = $db->row_count('portfolio_images', 'portfolio_id', array($id, 1), ' AND as_thumbnail = ?');
	if ($is_as_thumbnail_exists > 0)
		return true;
}

function get_portfolio_thumbnail() {
	global $db, $portfolio;
	
	$id = $portfolio['id'];
	$image = $db->select('portfolio_images', 'portfolio_id', array($id, 1), ' AND as_thumbnail = ?');
	
	return '<img src="'. get_site_url() .'/uploads/home-thumb-'. $image['image_name'] .'" alt="" />';
}

function the_portfolio_thumbnail() {
	if(has_portfolio_thumbnail())
		return false;

	echo get_portfolio_thumbnail();
}
	
function portfolio_images() {
	global $db, $portfolio;
	
	$id = $portfolio['id'];
	$is_images_exists = $db->row_count('portfolio_images', 'portfolio_id', $id);
	if ($is_images_exists > 0) :
		$images = $db->select_more('portfolio_images', 'portfolio_id', $id, ' ORDER BY image_order ASC');
		foreach ($images as $image)
			echo '<p><img src="'. get_site_url() .'/uploads/single-thumb-'. $image['image_name'] .'" alt="" /></p>' . "\n";
	endif;
}

function has_pages() {
	global $db;
	
	$is_pages = $db->row_count('pages', 'status', 2, '', '!=');
	if ($is_pages > 0) 
		return true;
}

function get_page_lists($home = true, $exclude = '') {
	global $db;
	
	$is_slug = (isset($_GET['page'])) ? $_GET['page'] : '';
	$get_pages = $db->select_more('pages', 'status', 0);
	$home_current = '';
	$add_home = array();
	$all_pages = array();
	
	if (is_home())
		$home_current = ' page-current';
		
	if ($home) 
		$add_home = array('<li class="page'. $home_current .' page-home"><a href="'. get_site_url() .'">Home</a></li>');

	foreach ($get_pages as $page) :
		if (!in_array($page['id'], (array)$exclude)) :
			$url = get_site_url() .'/page/'. $page['slug'] .'/';
			
			if ($page['link']) 
				$url = $page['link'];
			
			if ($is_slug == $page['slug']) 
				$all_pages[] = '<li class="page page-current page-'. $page['id'] .'"><a href="'. $url .'">'. $page['title'] .'</a></li>'. "\n";
			else
				$all_pages[] = '<li class="page page-'. $page['id'] .'"><a href="'. $url .'">'. $page['title'] .'</a></li>'. "\n";
		endif;
	endforeach;
	
	return implode(array_merge($add_home, $all_pages), '');
}

function page_lists($home = true, $exclude = '') {
	echo get_page_lists($home, $exclude);
}

function get_tag_name() {
	global $db;
	
	$slug = $_GET['tag'];
	$tag = $db->select('portfolio_tags', 'slug', $slug);
	return $tag['name'];
}

function tag_name() {
	echo get_tag_name();
}

function seo_meta() {
	$keywords = get_option('home_seo_keywords');
	$description = get_option('home_seo_description');
	$robots = 'index, follow';
	
	if (is_single()) :
		$description = get_the_excerpt(150);
		
		if (get_the_meta('seo_keywords')) 
			$keywords = get_the_meta('seo_keywords');
			
		$seo_index = (get_the_meta('seo_index') == 1) ? 'index' : 'noindex';
		$seo_follow = (get_the_meta('seo_follow') == 1) ? 'follow' : 'nofollow';
		$robots = $seo_index .', '. $seo_follow;
	elseif (is_page()) :
		$description = get_the_excerpt(150, 'page');
		
		if (get_the_meta('seo_keywords', 'page')) 
			$keywords = get_the_meta('seo_keywords', 'page');
			
		$seo_index = (get_the_meta('seo_index', 'page') == 1) ? 'index' : 'noindex';
		$seo_follow = (get_the_meta('seo_follow', 'page') == 1) ? 'follow' : 'nofollow';
		$robots = $seo_index .', '. $seo_follow;
	endif;
	
	if ($keywords) 
		echo '<meta name="keywords" content="'. $keywords . '" />' , "\n";
	if ($description) 
		echo '<meta name="description" content="'. $description . '" />' . "\n";
	if ($robots) 
		echo '<meta name="robots" content="'. $robots . '" />' . "\n";
}

function setup_portfolio_nav() {
	global $db, $setup_pagination;
	
	$current_page = $setup_pagination['current_page'];
	$c = '?';
	$path = '/';
	
	if (is_tag()) :
		$slug = $_GET['tag'];
		$get_tag = $db->select('portfolio_tags', 'slug', $slug);
		$count = $db->row_count('portfolio_tags_rel', 'tag_id', $get_tag['id']);
		$path = '/tag/'. $slug .'/';
	elseif (is_search()) :
		$query = $_GET['search'];
		$count = $db->row_count('portfolio', 'status', 0, " AND title LIKE '%$query%'");
		$path = '?search='. $query;
		$c = '&';
	elseif (is_home()) :
		$count = $db->row_count('portfolio', 'status', 0);
	endif;
	
	$total_pages = ceil($count / $setup_pagination['per_page']);
	
	return array('total_pages' => $total_pages, 'current_page' => $current_page, 'path' => $path, 'c' => $c);
}

function is_navigate() {
	$setup = setup_portfolio_nav();
	if ($setup['total_pages'] > 1)
		return true;
}

function get_previous_portfolio_link() {
	$setup = setup_portfolio_nav();
	if ($setup['total_pages'] > 1) :
		if ($setup['current_page'] > 1) :
			$previous = $setup['current_page'] - 1;
			return '<a href="'. get_site_url() . $setup['path'] . $setup['c'] .'p='. $previous .'">&larr; Previous</a>';
		endif;
	endif;
}

function get_portfolio_pagination() {
	$setup = setup_portfolio_nav();
	
	if ($setup['current_page'] >= 4) :
		$start_loop = $setup['current_page'] - 1;
		
		if ($setup['total_pages'] > $setup['current_page'] + 1) :
			$end_loop = $setup['current_page'] + 1;
		elseif ($setup['current_page'] <= $setup['total_pages'] && $setup['current_page'] > $setup['total_pages'] - 3) :
			$start_loop = $setup['total_pages'] - 3;
			$end_loop = $setup['total_pages'];
		else :
			$end_loop = $setup['total_pages'];
		endif;
	else :
		$start_loop = 1;
		
		if ($setup['total_pages'] > 4)
			$end_loop = 4;
		else	
			$end_loop = $setup['total_pages'];
	endif;
	
	if ($setup['total_pages'] > 1) :
		$p = array();
		
		for ($i = $start_loop; $i <= $end_loop; $i++) :
			if ($setup['current_page'] == $i)
				$p[] = '<span class="current">'. $i .'</span>';
			else
				$p[] = '<a href="'. get_site_url() . '/'. $setup['path'] . $setup['c'] .'p='. $i .'">'. $i .'</a>';
		endfor;
		
		return implode($p, '');
	endif;
}

function portfolio_pagination() {
	echo get_portfolio_pagination();
}

function previous_portfolio_link() {
	echo get_previous_portfolio_link();
}

function get_next_portfolio_link() {
	$setup = setup_portfolio_nav();
	if ($setup['total_pages'] > 1) :
		if ($setup['current_page'] < $setup['total_pages']) :
			$previous = $setup['current_page'] + 1;
			return '<a href="'. get_site_url() . $setup['path'] . $setup['c'] .'p='. $previous .'">Next &rarr;</a>';
		endif;
	endif;
}

function next_portfolio_link() {
	echo get_next_portfolio_link();
}

function pxl_title() {
	if (is_404())
		echo '404 &mdash; '. get_option('site_name');
	elseif (is_single())
		echo get_the_title() .' &mdash; '. get_option('site_name');
	elseif (is_tag())
		echo get_tag_name() .' &mdash; '. get_option('site_name');
	elseif (is_search())
		echo 'Search Result: '. strip_tags($_GET['search']) .' &mdash; '. get_option('site_name');
	elseif (is_page())
		echo get_the_title('page') .' &mdash; '. get_option('site_name');
	elseif (is_home())
		echo get_option('site_name') .' &mdash; '. get_option('site_description');
}

function get_header() {
	if (file_exists('themes/'. get_option('active_theme') .'/header.php'))
		require_once ('themes/'. get_option('active_theme') .'/header.php');
}

function get_footer() {
	if (file_exists('themes/'. get_option('active_theme') .'/footer.php'))
		require_once ('themes/'. get_option('active_theme') .'/footer.php');
}

function body_class() {
	if (is_single())
		$class = 'portfolio portfolio-single portfolio-'. get_the_ID();
	elseif (is_page())
		$class = 'page page-'. get_the_ID('page');
	elseif (is_404()) 
		$class = 'home error';
	else 
		$class = 'home';
		
	echo 'class="'. $class .'"';
}

function portfolio_class($external_class = '') {
	
	if (is_single()) 
		$class = 'portfolio portfolio-single portfolio-'. get_the_ID();
	else
		$class = 'portfolio portfolio-'. get_the_ID();
	
	if ($external_class) 
		$class = $class . ' '. implode((array)$external_class, ' ');
	
	echo 'class="'. $class .'"';
}

function page_class($external_class = '') {
	$class = 'page page-'. get_the_ID();
	
	if ($external_class) 
		$class = 'page page-'. get_the_ID() .' '. implode((array)$external_class, ' ');
	
	echo 'class="'. $class .'"';
}

function theme_stylesheet_uri() {
	if (file_exists('themes/'. get_option('active_theme') .'/style.css'))
		return site_url() .'/themes/'. get_option('active_theme') .'/style.css';
}

function theme_directory_uri() {
	return site_url() .'/themes/'. get_option('active_theme') .'';
}

function get_template_part($file) {
	if (file_exists('themes/'. get_option('active_theme') .'/'. $file .'.php'))
		require ('themes/'. get_option('active_theme') .'/'. $file .'.php');
}

?>
