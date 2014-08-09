jQuery(document).ready(function($) {
		
	$(window).resize(function() {
		var container_width = $('.container').width(),
			logo_width = $('#header .logo').outerWidth(true);
			menu_width = $('.menu').outerWidth(true);
			
		if (menu_width > (container_width - logo_width)) {
			$('#header .menu').detach().appendTo('#menu-placeholder');
			$('#menu-placeholder').show();
			menu_width = 240;
		} else {
			$('#menu-placeholder').hide();
			$('#menu-placeholder .menu').detach().appendTo('#header .main-menu');
		}
	});
	
	$(window).trigger('resize');
	
	$('.toggle-menu').click(function() {
		if ($(this).hasClass('toggled')) {
			$('#menu-placeholder .menu ul').hide();
			$(this).removeClass('toggled');
		} else {
			$('#menu-placeholder .menu ul').show();
			$(this).addClass('toggled');
		}
	});

	if ($(window).width() > 320) {
		$('.portfolio-items').masonry({
			itemSelector: '.portfolio',
			isAnimated: true,
			gutterWidth: 0,
			columnWidth: 20,
		});
	}
	
	$('.portfolio a').hover(
		function() { 
			$(this).find('.title').css({ top: ($(this).find('.overlay').height() - $(this).find('.title').height()) / 2 });
			$(this).find('.overlay, .title').fadeIn(200); 
		},
		function() { $(this).find('span').fadeOut(200); }
	);

});