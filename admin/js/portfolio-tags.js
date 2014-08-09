jQuery(document).ready(function($) {
	
	if ($('.portfolio-tag-lists .content').height() < $('.portfolio-tag-lists .edit-tag').outerHeight(true))
		$('.portfolio-tag-lists .content').css({ minHeight: $('.portfolio-tag-lists .edit-tag').outerHeight(true) });
	
	$('.submit-tag').click(function() {
		if (!$('.edit-tag .tag').val()) {
			$('.edit-tag .tag').focus();
			return false;
		}
	});
	
	$('.portfolio-tag-items .title .name').click(function() {
		var id = $(this).attr('data-id'),
			name = $(this).attr('data-name'),
			slug = $(this).attr('data-slug');
		
		$('.edit-tag .tag').val(name);
		$('.edit-tag .old-tag').val(slug);
		$('.edit-tag .old-tag-id').val(id);
		$('.edit-tag .submit-tag').attr('name', 'update_tag').val('Update Tag');
		
		if ($('.edit-tag .cancel-edit').length == 0)
			$('.edit-tag .submit-tag').after('<a href="javascript:;" class="cancel-edit">Cancel</a>');
	});
	
	$('.edit-tag .cancel-edit').live('click', function() {
		$('.edit-tag .tag, .edit-tag .old-tag, .edit-tag .old-tag-id').val('');
		$('.edit-tag .submit-tag').attr('name', 'add_tag').val('Add New Tag');
		$(this ).remove();
		$('.edit-tag .msg').remove();
	});

});