<?php get_header(); ?>

	<?php if (have_portfolio()) { ?>
	
		<!-- BEGIN .portfolio-items -->
		<div class="portfolio-items">
	
			<?php while (have_portfolio()) { the_portfolio(); ?>
			
				<?php get_template_part('content'); ?>
				
			<?php } ?>
		
		<!-- END .portfolio-items -->
		</div>
		
		<?php portfolio_nav(); ?>
		
	<?php } ?>

<?php get_footer(); ?>