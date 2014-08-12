<?php

if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

$portfolio = get_admin_items('portfolio', get_option('admin_portfolio_show'));

?>
    <form class="form-edit lists portfolio-lists clearfix" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <header id="page-header" class="clearfix">
        <h1><?php echo $title; ?></h1>
        <div class="actions">
          <a class="btn btn-blue" href="<?php admin_url(); ?>/portfolio.php?action=add_new">Add New</a>
        </div>
      </header>
      <!-- .page-header -->
      <div id="content">
        <div class="statistics">
          <p class="title">Portfolio Statistics</p>
          <p class="published"><?php echo get_statistics('published_portfolio'); ?> Portfolio published.</p>
          <p class="in-draft"><?php echo get_statistics('portfolio_in_draft'); ?> Portfolio in draft.</p>
          <p class="in-trash"><?php echo get_statistics('portfolio_in_trash'); ?> Portfolio in trash.</p>
          <p class="images"><?php echo get_statistics('portfolio_images'); ?> Images.</p>
          <p class="tags"><a href="<?php admin_url(); ?>/portfolio.php?manage=tags"><?php echo get_statistics('portfolio_tags'); ?> Tags.</a></p>
        </div>
        <!-- .statistics -->
        <div class="content">
          <div class="message">
<?php if (is_message(1)) : ?>
                    <div class="msg msg-form">Portfolio has been succesfully moved to trash.</div>
<?php elseif (is_message(2)) : ?>
                    <div class="msg msg-form">Portfolio has been succesfully untrashed.</div>
<?php elseif (is_message(3)) : ?>
                    <div class="msg msg-form">Portfolio has been succesfully deleted.</div>
<?php endif; ?>
          </div>
          <!-- .message -->
<?php if (count($portfolio)) : ?>
          <div class="portfolio-items items">
<?php foreach ($portfolio as $data) :
      $status = '';
      if ($data['status'] == 0)     $status = 'Published';
      elseif ($data['status'] == 1)   $status = 'In draft';
      elseif ($data['status'] == 2)   $status = 'In trash';
?>
            <div class="item">
              <div class="thumbnail">
                <?php
                    if (get_portfolio_thumbnail($data['id'])) :
                        echo get_portfolio_thumbnail($data['id']);
                    else :
                        echo '<img src="'. get_admin_url() .'/images/no-image.png" alt="" />';
                    endif;
                ?>

              </div>
              <!-- .thumbnail -->
              <div class="details">
                <h3 class="title">
                  <a href="<?php admin_url(); ?>/portfolio.php?action=edit&amp;id=<?php echo $data['id']; ?>"><?php echo ($data['title']) ? $data['title'] : 'No portfolio title'; ?></a>
                  <a href="<?php admin_url(); ?>/portfolio.php?action=delete_portfolio&amp;id=<?php echo $data['id']; ?>" class="delete" title="Delete permanently?"></a>
                  <span class="<?php echo strtolower(implode(explode(' ', $status), '-')); ?>"><?php echo $status; ?></span>
                </h3>
                <p>
                  <span><?php echo date('F d, Y', $data['publish_date']); ?></span>
                  <span class="separator">&bull;</span>
                  <span><?php echo get_portfolio_images_count($data['id']); ?> Images</span>
<?php if ($data['status'] == 0 || $data['status'] == 1) : ?>
                  <span class="separator">&bull;</span>
                  <span><a href="<?php admin_url(); ?>/portfolio.php?action=trash&amp;id=<?php echo $data['id']; ?>">Trash this?</a></span>
<?php endif; ?>
<?php if ($data['status'] == 0) : ?>
                  <span class="separator">&bull;</span>
                  <span><a href="<?php echo get_site_url() .'/portfolio/'. $data['slug']; ?>">View portfolio &rarr;</a></span>
<?php elseif ($data['status'] == 2) : ?>
                  <span class="separator">&bull;</span>
                  <span><a href="<?php admin_url(); ?>/portfolio.php?action=untrash&amp;id=<?php echo $data['id']; ?>">Untrash This?</a></span>
<?php endif; ?>
                </p>
              </div>
              <!-- .details -->
            </div>
<?php endforeach; ?>
          </div>
          <!-- items -->
<?php admin_pagination(get_option('admin_portfolio_show'), 'portfolio', 'portfolio.php'); ?>
<?php else : ?>
          <div class="msg msg-form msg-no-margin">You are accessing invalid page or you haven't publish any portfolio.</div>
<?php endif; ?>
        </div>
        <!-- .content -->
      </div>
      <!-- #content -->
    </form>
    <!-- form -->
