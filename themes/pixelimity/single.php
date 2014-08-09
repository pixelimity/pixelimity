<?php get_header(); ?>

	<!-- BEGIN #portfolio-<?php the_ID(); ?>.clearfix -->
	<article id="portfolio-<?php the_ID(); ?>" <?php portfolio_class(array('content', 'clearfix')); ?>>
	
		<div class="entry">
			<h1 class="title"><?php the_title(); ?></h1>
			<div class="description"><?php the_description(); ?></div>
			<div class="meta">
				<?php if (get_the_meta('date')) : ?><p>Release date: <strong><?php the_meta('date'); ?></strong></p><?php endif; ?>
				<?php if (get_the_meta('client')) : ?>
					<p>Client: <strong><?php if (get_the_meta('client_url')) echo '<a href="'. get_the_meta('client_url') .'">'. get_the_meta('client') .'</a>'; else the_meta('client'); ?></strong></p>
				<?php endif; ?>
				<?php if (has_tags()) : echo '<p>Tags: <strong>'; the_tags(); echo '</strong></p>'; endif; ?>
			</div>
		</div>
		
		<div class="images">
			<?php portfolio_images('portfolio-single'); ?>
		</div>
		
	<!-- END #portfolio-<?php the_ID(); ?> -->
	</article>

<?php get_footer(); ?>