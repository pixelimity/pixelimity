jQuery(document).ready(function() {

	$(window).load(function() {
		$('#loaded').fadeIn(200);
		setInterval(function() {
			$('#loaded').fadeOut(200);
		}, 800);	
		$('#page-header').addClass('fixed').css({ width: $('#main').width() });
		$('#content').css({ marginTop: $('#page-header').outerHeight(true) });
		
		if ($('.lists .content').height() < $('.lists .statistics').outerHeight(true))
			$('.lists .content').css({ minHeight: $('.lists .statistics').outerHeight(true) });
		
		if ( $('.lists .statistics').length > 0 ) {
			var statistic_pos = $('.lists .statistics').position();
			var statistic_pos_start = statistic_pos.top - 40;
		
			$(window).scroll(function() {
				if ($(window).scrollTop() >= statistic_pos_start)
					$('.lists .statistics').css({ top: $(window).scrollTop() + 40 });
			});
		}
	});

});
