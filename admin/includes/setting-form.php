<?php

if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}
  
global $db;
$admin = $_SESSION['ADMIN'];
$old_admin_data = $db->select('admin', 'username', $admin['u']);

?>
      <form class="form-edit clearfix" method="post" action="">
        <header id="page-header" class="clearfix">
          <h1><?php echo $title; ?></h1>
          <div class="actions">
            <input type="submit" class="btn btn-blue btn-submit" name="submit_setting" value="Save Setting">
          </div>
          <!-- .actions -->
        </header>
        <!-- .page-header -->
        <div id="content">
          <div class="message clearfix">
<?php if (is_message(1)) : ?>
        <div class="msg msg-form">Setting was successfully saved.</div>
<?php elseif (isset($error_message)) : ?>
        <div class="msg msg-error"><?php echo $error_message; ?></div>
<?php endif; ?>
          </div>
          <!-- .message -->
          <h3 class="field-section-title">Admin Setting</h3>
          <div class="field">
            <label for="admin-portfolio-show" class="label">Admin Portfolio Show<span>Enter numbers of portfolio lists.</span></label>
            <input type="text" name="data[admin_portfolio_show]" id="admin-portfolio-show" class="input admin-portfolio-show" value="<?php echo get_option('admin_portfolio_show'); ?>">
          </div>
          <div class="field">
            <label for="admin-pages-show" class="label">Admin Pages Show<span>Enter numbers of page lists.</span></label>
            <input type="text" name="data[admin_pages_show]" id="admin-pages-show" class="input admin-pages-show" value="<?php echo get_option('admin_pages_show'); ?>">
          </div>
          <div class="field">
            <label for="password" class="label">Password<span>Leave if you not want to replace.</span></label>
            <input type="password" name="admin_data[password]" id="password" class="input password" value="">
          </div>
          <div class="field">
            <label for="email" class="label">Email<span>Leave if you not want to replace.</span></label>
            <input type="text" name="admin_data[email]" id="email" class="input email" value="<?php echo (isset($admin_data['email'])) ? $admin_data['email'] : $old_admin_data['email']; ?>">
          </div>
          <h3 class="field-section-title">Site Setting</h3>
          <div class="field">
            <label for="site-name" class="label">Site Name<span>Enter your site name.</span></label>
            <input type="text" name="data[site_name]" id="site-name" class="input site-name" value="<?php echo get_option('site_name'); ?>">
          </div>
          <div class="field">
            <label for="site-description" class="label">Site Description<span>Enter your site description or tagline.</span></label>
            <input type="text" name="data[site_description]" id="site-description" class="input site-description" value="<?php echo get_option('site_description'); ?>">
          </div>
          <div class="field">
            <label for="portfolio-show" class="label">Portfolio Show<span>Enter numbers of portfolio show.</span></label>
            <input type="text" name="data[portfolio_show]" id="portfolio_show" class="input portfolio-show" value="<?php echo get_option('portfolio_show'); ?>">
          </div>
          <h3 class="field-section-title">Images Setting</h3>
          <div class="field">
            <label for="home-image" class="label">Home Image Size<span>Set size for your home images, separated with commas.</span></label>
            <input type="text" name="data[home_image_size]" id="home-image" class="input home-image" value="<?php echo get_option('home_image_size'); ?>">
          </div>
          <div class="field">
            <label for="single-image" class="label">Single Image Size<span>Set size for your single images, separated with commas.</span></label>
            <input type="text" name="data[single_image_size]" id="single-image" class="input single-image" value="<?php echo get_option('single_image_size'); ?>">
          </div>
        </div> 
        <!-- #content --> 
      </form>
      <!-- form -->
