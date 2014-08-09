<?php 

if (!defined('PXL'))
	exit('You can\'t access direct script.');

global $current_menu; 

?>

<nav id="admin-menu">
	<div class="logo">
		<a href="<?php echo admin_url(); ?>"><img src="<?php echo admin_url(); ?>/images/admin-logo.png" alt="<?php site_name(); ?>" /></a>
	</div>	
	<ul>
		<li<?php if ($current_menu == 1) echo ' class="current"'; ?>>
			<a href="<?php echo admin_url(); ?>/portfolio.php">Portfolio</a>
			<a href="<?php echo admin_url(); ?>/portfolio.php?action=add_new" class="add">Add New</a>
		</li>
		<li<?php if ($current_menu == 2) echo ' class="current"'; ?>>
			<a href="<?php echo admin_url(); ?>/pages.php">Pages</a>
			<a href="<?php echo admin_url(); ?>/pages.php?action=add_new" class="add">Add New</a>
		</li>
		<li<?php if ($current_menu == 3) echo ' class="current"'; ?>><a href="<?php echo admin_url(); ?>/themes.php">Themes</a></li>
		<li<?php if ($current_menu == 4) echo ' class="current"'; ?>><a href="<?php echo admin_url(); ?>/setting.php">Setting</a></li>
	</ul>
	<ul>
		<li><a href="<?php echo site_url(); ?>" target="_blank">View my site &rarr;</a></li>
		<li><a href="<?php echo admin_url(); ?>/index.php?action=sign_out">Sign Out</a></li>
	</ul>
</nav>