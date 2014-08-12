<?php

if (!defined('PXL')) {
   exit('You can\'t access direct script.');
}

global $title;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- meta characterset -->
  <meta charset="utf-8">
  <!-- title -->
  <title><?php echo $title; ?> &lsaquo; Admin ~ <?php site_name(); ?></title>
  <!-- meta viewport as responsive mobile device compatibilities -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- meta robots not allowed index this script -->
  <meta name="robots" content="noindex, nofollow, noodp, noydir, noarchive">
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo admin_css_uri(); ?>">
  <link rel="shortcut icon" href="<?php admin_url(); ?>/images/favicon.png">
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script type="text/javascript" src="<?php admin_url(); ?>/js/jquery.min.js"></script>
</head>
<body <?php body_class(); ?>>
  <div class="wrap">
    <span class="load" id="loaded">Loaded</span>
    <span class="load" id="loading">Loading...</span>
<?php require_once ('menu.php'); ?>
    <div id="main">
      <button class="btn btn-blue btn-submit" id="mobilebutton">| | |</button>
