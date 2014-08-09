<?php

function portfolio_nav() {
	if (is_navigate()) :
		echo '<nav class="nav clearfix">';
		previous_portfolio_link();
		portfolio_pagination();
		next_portfolio_link();
		echo '</nav>';
	endif;
}
		
?>