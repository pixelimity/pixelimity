<?php get_header(); ?>

	<!-- BEGIN #page-<?php the_ID('page'); ?> -->
	<div id="page-<?php the_ID('page'); ?>" <?php page_class(array('content', 'clearfix')); ?>>
		
		<h1 class="title"><?php the_title('page'); ?></h1>
		<div class="description"><?php the_description('page'); ?></div>
	
	<!-- END #page-<?php the_ID('page'); ?> -->
	</div>

<?php get_footer(); ?>