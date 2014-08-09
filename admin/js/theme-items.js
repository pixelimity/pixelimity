jQuery(document).ready(function($) {

	$('.theme').css({ width: ($('#content').width() - 120) / 4 });
	
	$('.install-theme.enabled').live('click', function(){
		$("#install-theme #theme").click();
    });
	
	$('#install-theme #theme').die('click').live('change', function() { 
    	$("#install-theme").ajaxForm({
		   	beforeSubmit: function(){ $('.install-theme').removeClass('enabled').addClass('uploading').text('Uploading...'); }, 
			success: function(data){ 
				$('.install-theme').removeClass('uploading').addClass('enabled').text('Install New Theme'); 
				window.location.href = admin_url + '/themes.php?message=3';
			}, 
			error: function(){}
		}).submit();
	});

});