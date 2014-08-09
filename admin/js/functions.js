function insert_at_caret(area_id, text, cursor_position) {
    
	var textarea = document.getElementById(area_id);
    var scroll_pos = textarea.scrollTop;
    var str_pos = 0;
    var br = ((textarea.selectionStart || textarea.selectionStart == '0') ? "ff" : (document.selection ? "ie" : false ) );
    
    if (br == "ie") { 
        textarea.focus();
       
		var range = document.selection.createRange();
        
		range.moveStart ('character', -textarea.value.length);
        str_pos = range.text.length;
    } else if (br == "ff")
    	str_pos = textarea.selectionStart;

    var front = (textarea.value).substring(0,str_pos);  
    var back = (textarea.value).substring(str_pos,textarea.value.length); 
    
	textarea.value = front + text + back;
    str_pos = str_pos + text.length;

    if (br == "ie") { 
        textarea.focus();
		
        var range = document.selection.createRange();
		
        range.moveStart ('character', -textarea.value.length);
        range.moveStart ('character', str_pos);
        range.moveEnd ('character', 0);
        range.select();
    } else if (br == "ff") {
        textarea.selectionStart = str_pos;
        textarea.selectionEnd = str_pos - cursor_position;
        textarea.focus();
    }

    textarea.scrollTop = scroll_pos;
}

function load_images() {
	$.post(ajax_url, { action: 'load_images', portfolio_id: $('.edit-portfolio .portfolio-id').val() }, function(data) {
		$('.image-lists').html(data.images).hide().fadeIn(200);
		
		if ($('.image-action .action').length < 1)
			$('.image-item.as-thumbnail .action').show().detach().appendTo('.image-action');
		
		if (data.count > 0)
			$('.image-lists').css({ marginTop: 30 });
	}, 'json');
}