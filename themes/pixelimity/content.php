<!-- BEGIN #portfolio-<?php the_ID(); ?> -->
<article id="portfolio-<?php the_ID(); ?>" <?php portfolio_class(); ?>>
	
	<div class="thumbnail">
		<a href="<?php the_permalink(); ?>">
			<span class="title"><?php the_title(); ?></span>
			<span class="overlay"></span>
			<?php the_portfolio_thumbnail('portfolio-thumbnail'); ?>
		</a>
	</div>
	
<!-- END #portfolio-<?php the_ID(); ?> -->
</article>