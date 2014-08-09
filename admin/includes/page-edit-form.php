<?php

if (!defined('PXL'))
	exit('You can\'t access direct script.');

$data['status'] = 0;
$buttons = array ('bold', 'italic', 'h1', 'h2', 'blockquote', 'ul', 'ol'); 

if (is_edit())
	$data = get_edit_data('pages');

?>

<form class="form-edit clearfix" method="post" action="">

	<header id="page-header" class="clearfix">
		
		<h1><?php echo $title; ?></h1>
		
		<?php if (is_edit() && $data['status'] == 0) : ?>
			<?php if ($data['link']) : ?>
				<a class="view" target="_blank" href="<?php echo $data['link']; ?>/">Visit this page &rarr;</a>
			<?php else : ?>
				<a class="view" target="_blank" href="<?php echo site_url(); ?>/page/<?php echo $data['slug']; ?>/">View this page &rarr;</a>
			<?php endif; ?>
		<?php elseif (is_edit() && $data['status'] == 1) : ?>
			<span class="unpublished">Unpublished</span>
		<?php endif; ?>
		
		<div class="actions">
			
			<?php if (!is_edit() && $data['status'] == 0 || is_edit() && $data['status'] == 1) : ?>
				<p>
					<input type="checkbox" id="as-draft" name="data[status]" class="checkbox-hidden" <?php echo ($data['status'] == 1) ? ' checked="checked"' : ''; ?>/>
					<label for="as-draft" class="custom-checkbox as-draft">Set as draft?</label>
				</p>
			<?php endif; ?>
			
			<?php if (is_edit()) : ?>
				<p class="move-to-trash<?php if ($data['status'] == 1) echo ' with-divider'; ?>">
					<a href="<?php echo site_url(); ?>/admin/pages.php?action=trash&amp;id=<?php echo $data['id']; ?>"><span></span>Move to trash?</a>
				</p>
			<?php endif; ?>
			
			<input type="submit" class="btn btn-blue btn-submit" name="submit_page" value="<?php echo (is_edit() && $data['status'] == 0) ? 'Update Page' : 'Publish Page'; ?>" />
	
		</div>
		
	</header>
	
	<div id="content">
	
		<div class="message clearfix">
			<?php if (is_message(1)) : ?>
				<div class="msg msg-form">Page was successfully added. <a target="_blank" href="<?php echo site_url(); ?>/page/<?php echo $data['slug']; ?>/">View this page &rarr;</a></div>
			<?php elseif (is_message(2)) : ?>
				<div class="msg msg-form">Page was successfully saved. You can edit again before publishing your page.</div>
			<?php elseif (is_message(3)) : ?>
				<?php if ($data['status'] == 1) : ?>
					<div class="msg msg-form">Page was successfully updated. But this is still in the draft.</div>
				<?php else : ?>
					<div class="msg msg-form">Page was successfully updated. <a target="_blank" href="<?php echo ($data['link']) ? $data['link'] : get_site_url() .'/page/'. $data['slug'] .'/'; ?>"><?php echo ($data['link']) ? 'Visit' : 'View'; ?> this page &rarr;</a></div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
			
		<div class="field">
			<label for="title" class="label">Title<span>Enter your page title with 20 words maximum.</span></label>
			<input type="text" name="data[title]" id="title" class="input title" value="<?php if (is_edit()) echo htmlentities($data['title']); ?>" />
		</div>
		
		<div class="field">
			<label for="description" class="label">Description<span>Enter your page description. You can using markdown syntax for this.</span></label>
			<div class="description-area">
				<div class="buttons">
					<?php foreach ($buttons as $button) : ?>
						<a href="javascript:;" class="<?php echo $button; ?>"><?php echo $button; ?></a>
					<?php endforeach; ?>
				</div>
				<textarea name="data[description]" id="description" class="input textarea description" spellcheck="false" placeholder="Type description here ..."><?php ob_end_flush(); if (is_edit()) : echo htmlentities($data['description']); endif; ob_start('compress_html'); ?></textarea>
			</div>
		</div>
		
		<div class="field">
			<label for="link" class="label">Link<span>Enter custom link to redirect to another URL.</span></label>
			<input type="text" name="data[link]" id="link" class="input link" value="<?php if (is_edit()) echo htmlentities($data['link']); ?>" />
		</div>
		
		<div class="field">
			<label for="keywords" class="label">Keywords<span>Enter portfolio keywords for SEO. Separated with comma.</span></label>
			<input type="text" name="data[seo_keywords]" id="keywords" class="input keywords" value="<?php if (is_edit()) echo htmlentities($data['seo_keywords']); ?>" />
		</div>
		
		<div class="field">
			<input type="checkbox" name="data[seo_follow]" id="nofollow" class="checkbox-hidden" <?php echo (is_edit() && $data['seo_follow'] == 0) ? ' checked="checked"' : ''; ?>/>
			<label for="nofollow" class="custom-checkbox nofollow">No Follow?</label>
			<input type="checkbox" name="data[seo_index]" id="noindex" class="checkbox-hidden" <?php echo (is_edit() && $data['seo_index'] == 0) ? ' checked="checked"' : ''; ?>/>
			<label for="noindex" class="custom-checkbox">No Index?</label>
		</div>
		
	</div>
	
	<?php if (is_edit() && $data['status'] == 0) : ?>
		<input type="hidden" name="published" value="<?php echo $data['status']; ?>" />
	<?php endif; ?>
	
</form>