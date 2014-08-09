<?php

if (!defined('PXL'))
	exit('You can\'t access direct script.');
		
$completed_themes = get_themes('completed');
$not_completed_themes = get_themes('not_completed');

?>

<form class="form-edit lists theme-lists clearfix" method="post" action="">

	<header id="page-header" class="clearfix">
		
		<h1><?php echo $title; ?></h1>
		
		<div class="actions">
			<a class="btn btn-blue install-theme enabled" href="javascript:;">Install New Theme</a>
		</div>
		
	</header>
	
	<div id="content">
	
		<div class="message">
			<?php if (is_message(1)) : ?>
				<div class="msg msg-form">Theme was successfully activated.</div>
			<?php elseif (is_message(2)) : ?>
				<div class="msg msg-form">Theme was successfully deleted.</div>
			<?php elseif (is_message(3)) : ?>
				<div class="msg msg-form">A new theme was successfully installed.</div>
			<?php endif; ?>
		</div>
		
		<?php if ($completed_themes) : ?>
	
			<div class="themes clearfix">
			
				<?php foreach ($completed_themes as $theme) : ?>
				
					<div class="theme">
						<?php 
							$data = array();
							$theme_slugs = explode('/', $theme);
							$data['dir'] = end($theme_slugs);
							$data['screenshot'] = get_site_url() .'/themes/'. end($theme_slugs) .'/screenshot.png';
							
							$style = file($theme .'/style.css');
							foreach ($style as $line => $value) :
								if (strpos($value, 'Theme Name:') !== false) :
									list($key, $val) = explode(':', $value, 2);
									$data['theme_name'] = $val;
								endif;
									
								if (strpos($value, 'Version:') !== false) :
									list($key, $val) = explode(':', $value, 2);
									$data['version'] = $val;
								endif;
								
								if (strpos($value, 'Author:') !== false) :
									list($key, $val) = explode(':', $value, 2);
									$data['author'] = $val;
								endif;
								
								if (strpos($value, 'Author URI:') !== false) :
									list($key, $val) = explode(':', $value, 2);
									$data['author_uri'] = $val;
								endif;
							endforeach;
						?>
						
						<div class="thumbnail">
							<?php if (file_exists($theme .'/screenshot.png')) : ?><img src="<?php echo $data['screenshot']; ?>" alt="" />
							<?php else : ?><img src="<?php admin_url(); ?>/images/no-theme-thumb.png" alt="" />
							<?php endif; ?>
						</div>
						
						<div class="details clearfix">
							
							<?php if ($data['theme_name']) : ?>
								<div class="theme-name clearfix">
									<span class="name"><?php echo $data['theme_name']; ?></span>
									<?php if ($data['version']) echo '<span class="ver">'. $data['version'] .'</span>'; ?>
									<?php if (!is_theme_activated($data['dir'])) : ?>
										<a href="<?php admin_url(); ?>/themes.php?action=delete&amp;theme=<?php echo $data['dir']; ?>" class="delete">Delete?</a>
									<?php endif; ?>
									<?php if (is_theme_activated($data['dir'])) : ?>
										<span class="current">Current</span>
									<?php else : ?>
										<a href="<?php admin_url(); ?>/themes.php?action=active&amp;theme=<?php echo $data['dir']; ?>" class="active">Active?</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							
							<div class="author">
								By 
								<?php if (isset($data['author'])) : ?>
									<?php if ($data['author_uri']) echo '<a href="'. $data['author_uri'] .'">'. $data['author'] .'</a>'; else echo $data['author']; ?>
								<?php else : ?>
									Anonymous
								<?php endif; ?>
							</div>
							
						</div>
							
					</div>
				
				<?php endforeach; ?>
			
			</div>
					
		<?php endif; ?>
		
		<?php if ($not_completed_themes) : ?>
		
			<div class="not-completed-themes">
				<div class="title">Not completed themes:</div>
				<ol>
					<?php foreach ($not_completed_themes as $theme) : ?>
						<li>
							<?php if (get_option('site_path')) echo get_option('site_path') .'/'; ?>themes/<strong><?php echo end(explode('/', $theme)); ?></strong>
							<a href="<?php admin_url(); ?>/themes.php?action=delete&amp;theme=<?php echo end(explode('/', $theme)); ?>" class="delete">Delete?</a>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		
		<?php endif; ?>
	
	</div>
	
</form>

<form id="install-theme" method="post" enctype="multipart/form-data" action="<?php echo admin_url(); ?>/admin-ajax.php?action=install_theme">
	<input type="file" name="theme" id="theme"/>
</form>