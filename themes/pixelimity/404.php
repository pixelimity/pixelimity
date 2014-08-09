<?php get_header(); ?>
	
	<?php if (is_search()) : ?>
		<h1 class="archive-title">Search Not Found</h1>
		<p>Sorry but your search result is not found.</p>
	<?php else : ?>
		<h1 class="archive-title">Page Not Found</h1>
		<p>Sorry but you are accessing invalid page.</p>
	<?php endif; ?>

<?php get_footer(); ?>