/*! 
 * admin javascript aplication for portfolio tags
 * @package pixelimity
 */

// prevent jQuery Conflict
(function($) {

    /* Document ready
    ------------------------------ */
    $(document).ready(function() {

        // determine variable object
        var edittag = $('.edit-tag .tag');

        $('.submit-tag').click(function() {
            if (!edittag.val()) {
                edittag.focus();
                return false;
            }
        });

        $('.portfolio-tag-items .title .name').click(function() {
            var id = $(this).attr('data-id'),
                name = $(this).attr('data-name'),
                slug = $(this).attr('data-slug');

            // insert data-name into input
            edittag.val(name);
            $('.edit-tag .old-tag').val(slug);
            $('.edit-tag .old-tag-id').val(id);
            $('.edit-tag .submit-tag').attr('name', 'update_tag').val('Update Tag');

            if ($('.edit-tag .cancel-edit').length == 0) {
                $('.edit-tag .submit-tag').after('<a href="javascript:void(0);" class="cancel-edit">Cancel</a>');
            }

        });

        $(document).on('click', '.edit-tag .cancel-edit', function(e) {
            // prevent do original proccessing
            e.preventDefault();
            $('.edit-tag .tag, .edit-tag .old-tag, .edit-tag .old-tag-id').val('');
            $('.edit-tag .submit-tag').attr('name', 'add_tag').val('Add New Tag');
            $(this).remove();
            $('.edit-tag .msg').remove();
        });

    });
})(window.jQuery);
