<?php

if (!defined('PXL'))
	exit('You can\'t access direct script.');

$tags = get_admin_items('portfolio_tags', get_option('admin_tags_show'));

?>

<form class="form-edit lists portfolio-tag-lists clearfix" method="post" action="">

	<header id="page-header" class="clearfix">
		
		<h1><?php echo $title; ?></h1>
		
		<div class="actions">
			<a class="btn btn-blue" href="<?php admin_url(); ?>/portfolio.php?action=add_new" target="_blank">Add New Portfolio</a>
		</div>
		
	</header>
	
	<div id="content">
	
		<div class="edit-tag">
		
			<div class="message clearfix">
				<?php if (is_message(1)) : ?>
					<div class="msg msg-form">Tag was successfully added.</div>
				<?php elseif (is_message(2)) : ?>
					<div class="msg msg-form">Tag was successfully updated.</div>
				<?php elseif (is_message(3)) : ?>
					<div class="msg msg-form">Tag was successfully deleted.</div>
				<?php endif; ?>
			</div>
			
			<div class="field">
				<label for="tag" class="label">Tag<span>Separated with comma to add more tags.</span></label>
				<input type="text" name="tag" id="tag" class="tag input" />
				<input type="hidden" name="old_tag" class="old-tag" />
				<input type="hidden" name="old_tag_id" class="old-tag-id" />
				<input type="submit" name="add_tag" class="btn btn-blue submit-tag" value="Add New Tag" />
			</div>
			
		</div>
		
		<div class="content">
		
			<?php if (count($tags)) : ?>
				
				<div class="portfolio-tag-items items">
				
					<?php foreach ($tags as $data) : ?>
			
						<div class="item clearfix">
						
							<h3 class="title">
								<a href="javascript:;" class="name" data-id="<?php echo $data['id']; ?>" data-name="<?php echo $data['name']; ?>" data-slug="<?php echo $data['slug']; ?>"><?php echo $data['name']; ?></a>
								<a href="<?php admin_url(); ?>/portfolio.php?action=delete_tag&amp;id=<?php echo $data['id']; ?>" class="delete" title="Delete permanently?"></a>
								<span class="count"><?php echo get_portfolio_tags_count($data['id']); ?> Portfolio</span>
							</h3>
							
							<p>
								<span>
									<?php if (get_portfolio_tags_count($data['id'])) : ?>
										<a href="<?php site_url(); ?>/tag/<?php echo $data['slug']; ?>">View all portfolio on this tag &rarr;</a>
									<?php else : ?>
										Empty portfolio
									<?php endif; ?>
								</span>
							</p>
							
						</div>
					
					<?php endforeach; ?>
					
				</div>
				
				<?php admin_pagination(get_option('admin_tags_show'), 'portfolio_tags', 'portfolio.php?manage=tags', '&amp;'); ?>
			
			<?php else : ?>
				<div class="msg msg-form">You are accessing invalid page or you haven't publish any tag.</div>
			<?php endif; ?>
		
		</div>
	
	</div>
	
</form>