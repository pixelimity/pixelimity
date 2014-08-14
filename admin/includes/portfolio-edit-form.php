<?php

if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

$data['status'] = 0;
$buttons = array ('bold', 'italic', 'h1', 'h2', 'blockquote', 'ul', 'ol'); 

if (is_edit()) {
    $data = get_edit_data('portfolio');
}

?>
      <form class="form-edit clearfix" method="post" action="">
        <header id="page-header" class="clearfix">
          <h1><?php echo $title; ?></h1>
<?php if (is_edit() && $data['status'] == 0) : ?>
          <a class="view" target="_blank" href="<?php echo site_url(); ?>/portfolio/<?php echo $data['slug']; ?>/">View this portfolio &rarr;</a>
<?php elseif (is_edit() && $data['status'] == 1) : ?>
          <span class="unpublished">Unpublished</span>
<?php endif; ?>
          <div class="actions">
<?php if (!is_edit() && $data['status'] == 0 || is_edit() && $data['status'] == 1) : ?>
            <p>
              <input type="checkbox" id="as-draft" name="data[status]" class="checkbox-hidden" <?php echo ($data['status'] == 1) ? ' checked="checked"' : ''; ?>/>
              <label for="as-draft" class="custom-checkbox as-draft">Set as draft?</label>
            </p>
<?php endif; 
    if (is_edit()) : ?>
            <p class="move-to-trash<?php if ($data['status'] == 1) echo ' with-divider'; ?>">
              <a href="<?php echo site_url(); ?>/admin/portfolio.php?action=trash&amp;id=<?php echo $data['id']; ?>"><span></span>Move to trash?</a>
            </p>
<?php endif; ?>
            <input type="submit" class="btn btn-blue btn-submit" name="submit_portfolio" value="<?php echo (is_edit() && $data['status'] == 0) ? 'Update Portfolio' : 'Publish Portfolio'; ?>">
          </div>
          <!-- .actions -->
        </header>
        <!-- .page-header -->
        <div id="content">
          <div class="message clearfix">
<?php if (is_message(1)) : ?>
            <div class="msg msg-form">Portfolio was successfully added. <a target="_blank" href="<?php echo site_url(); ?>/portfolio/<?php echo $data['slug']; ?>/">View this portfolio &rarr;</a></div>
<?php elseif (is_message(2)) : ?>
            <div class="msg msg-form">Portfolio was successfully saved. You can edit again before publishing your portfolio.</div>
<?php elseif (is_message(3)) :
        if ($data['status'] == 1) : ?>
            <div class="msg msg-form">Portfolio was successfully updated. But this is still in the draft.</div>
<?php else : ?>
            <div class="msg msg-form">Portfolio was successfully updated. <a target="_blank" href="<?php echo site_url(); ?>/portfolio/<?php echo $data['slug']; ?>/">View this portfolio &rarr;</a></div>
<?php   endif;
    endif; ?>
          </div>
          <!-- .message -->
          <div class="field field-images clearfix">
            <div class="clearfix">
<?php if (is_edit()) : ?>
              <a class="add-images enabled" href="javascript:void(0);"></a>
<?php else : ?>
              <p class="add-images" href="javascript:;"><span>Please save or publish your portfolio first to add images</span></p>
<?php endif; ?>
              <div class="upload-note">
                <span>Add or Upload Images</span>
                jpeg, jpg, png or gif formats.
              </div>
              <div class="image-action"></div>  
            </div>
            <div class="image-lists clearfix"></div>
          </div>
          <div class="field">
            <label for="title" class="label">Title<span>Enter your portfolio title with 20 words maximum.</span></label>
            <input type="text" name="data[title]" id="title" class="input title" value="<?php if (is_edit()) echo htmlentities($data['title']); ?>">
            <input type="hidden" name="data[slug]">
          </div>
          <div class="field">
            <label for="description" class="label">Description<span>Enter your portfolio description. You can using markdown syntax for this.</span></label>
            <div class="description-area">
              <div class="buttons">
<?php foreach ($buttons as $button) : ?>
                <a href="javascript:;" class="<?php echo $button; ?>"><?php echo $button; ?></a>
<?php endforeach; ?>
              </div>
              <textarea name="data[description]" id="description" class="input textarea description" spellcheck="false" placeholder="Type description here ..."><?php ob_end_flush(); if (is_edit()) : echo htmlentities($data['description']); endif; ob_start(); ?></textarea>
            </div>
          </div>
          <div class="field">
            <label for="date" class="label">Date<span>Enter your project release date. e.g: July 16, 2014.</span></label>
            <input type="text" name="data[date]" id="date" class="input date" value="<?php if (is_edit()) echo htmlentities($data['date']); ?>">
          </div>
          <div class="field">
            <label for="client" class="label">Client<span>Enter your client name about your project (if available). e.g: John Doe</span></label>
            <input type="text" name="data[client]" id="client" class="input client" value="<?php if (is_edit()) echo htmlentities($data['client']); ?>">
          </div>
          <div class="field">
            <label for="client-url" class="label">Client URL<span>Enter your client URL about your project (if available). e.g: http://johndoe.com</span></label>
            <input type="text" name="data[client_url]" id="client-url" class="input client-url" placeholder="http://" value="<?php if (is_edit()) echo htmlentities($data['client_url']); ?>">
          </div>
          <div class="field">
            <label for="tags" class="label">Tags<span>Separated tags with comma.</span></label>
            <input type="text" name="tags" id="tags" class="input tags" value="<?php if (is_edit()) echo get_portfolio_edit_tags(); ?>">
          </div>
          <div class="field">
            <label for="keywords" class="label">Keywords<span>Enter portfolio keywords for SEO. Separated with comma.</span></label>
            <input type="text" name="data[seo_keywords]" id="seo_keywords" class="input keywords" value="<?php if (is_edit()) echo htmlentities($data['seo_keywords']); ?>">
          </div>
          <div class="field">
            <input type="checkbox" name="data[seo_follow]" id="nofollow" class="checkbox-hidden" <?php echo (is_edit() && $data['seo_follow'] == 0) ? ' checked="checked"' : ''; ?>>
            <label for="nofollow" class="custom-checkbox nofollow">No Follow?</label>
            <input type="checkbox" name="data[seo_index]" id="noindex" class="checkbox-hidden" <?php echo (is_edit() && $data['seo_index'] == 0) ? ' checked="checked"' : ''; ?>>
            <label for="noindex" class="custom-checkbox">No Index?</label>
          </div>
        </div>
        <!-- #content -->
<?php if (is_edit() && $data['status'] == 0) : ?>
        <input type="hidden" name="published" value="<?php echo $data['status']; ?>">
<?php endif; ?>
      </form>
      <!-- form -->
<?php if (is_edit()) : ?>
      <form id="upload-images" method="post" enctype="multipart/form-data" action="<?php echo admin_url(); ?>/admin-ajax.php?action=upload_images">
        <input type="file" name="images[]" id="images" multiple="true">
        <input type="hidden" name="portfolio_id" class="portfolio-id" value="<?php echo $data['id']; ?>">
      </form>
      <!-- form#upload-images -->
<?php endif; ?>
