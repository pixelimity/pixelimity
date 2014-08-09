<?php

if (!defined('PXL'))
	exit('You can\'t access direct script.');

$pages = get_admin_items('pages', get_option('admin_pages_show'));

?>

<form class="form-edit lists page-lists clearfix" method="post" action="">

	<header id="page-header" class="clearfix">
		
		<h1><?php echo $title; ?></h1>
		
		<div class="actions">
			<a class="btn btn-blue" href="<?php admin_url(); ?>/pages.php?action=add_new" target="_blank">Add New</a>
		</div>
		
	</header>
	
	<div id="content">
	
		<div class="statistics">
			<p class="title">Page Statistics</p>
			<p class="published"><?php echo get_statistics('published_pages'); ?> Pages published.</p>
			<p class="in-draft"><?php echo get_statistics('pages_in_draft'); ?> Pages in draft.</p>
			<p class="in-trash"><?php echo get_statistics('pages_in_trash'); ?> Pages in trash.</p>
		</div>
		
		<div class="content">
		
			<div class="message clearfix">
				<?php if (is_message(1)) : ?>
					<div class="msg msg-form">Page has been succesfully moved to trash.</div>
				<?php elseif (is_message(2)) : ?>
					<div class="msg msg-form">Page has been succesfully untrashed.</div>
				<?php elseif (is_message(3)) : ?>
					<div class="msg msg-form">Page has been succesfully deleted.</div>
				<?php endif; ?>
			</div>
	
			<?php if (count($pages)) : ?>
				
				<div class="page-items items">
				
					<?php foreach ($pages as $data) : ?>
					
						<?php 
							$status = '';
							if ($data['status'] == 0) 		$status = 'Published';
							elseif ($data['status'] == 1) 	$status = 'In draft';
							elseif ($data['status'] == 2) 	$status = 'In trash';
						?>
			
						<div class="item clearfix">
						
							<h3 class="title">
								<a href="<?php admin_url(); ?>/pages.php?action=edit&amp;id=<?php echo $data['id']; ?>"><?php echo ($data['title']) ? $data['title'] : 'No page title'; ?></a>
								<a href="<?php admin_url(); ?>/pages.php?action=delete_page&amp;id=<?php echo $data['id']; ?>" class="delete" title="Delete permanently?"></a>
								<span class="<?php echo strtolower(implode(explode(' ', $status), '-')); ?>"><?php echo $status; ?></span>
							</h3>
						
							<p>
							
								<span><?php echo date('F d, Y', $data['publish_date']); ?></span>
								
								<?php if ($data['status'] == 0 || $data['status'] == 1) : ?>
									<span class="separator">&bull;</span>
									<span><a href="<?php admin_url(); ?>/pages.php?action=trash&amp;id=<?php echo $data['id']; ?>">Trash this?</a></span>
								<?php endif; ?>
								
								<?php if ($data['status'] == 0) : ?>
									<span class="separator">&bull;</span>
									<span><a href="<?php echo ($data['link']) ? $data['link'] : get_site_url() .'/page/'. $data['slug']; ?>"><?php echo ($data['link']) ? 'Visit' : 'View'; ?> page &rarr;</a></span>
								<?php elseif ($data['status'] == 2) : ?>
									<span class="separator">&bull;</span>
									<span><a href="<?php admin_url(); ?>/pages.php?action=untrash&amp;id=<?php echo $data['id']; ?>">Untrash This?</a></span>
								<?php endif; ?>
								
							</p>
							
						</div>
					
					<?php endforeach; ?>
					
				</div>
				
				<?php admin_pagination(get_option('admin_pages_show'), 'pages', 'pages.php'); ?>
			
			<?php else : ?>
				<div class="msg msg-form msg-no-margin">You are accessing invalid page or you haven't publish any page.</div>
			<?php endif; ?>
			
		</div>
	
	</div>
	
</form>