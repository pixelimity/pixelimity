/*! 
 * admin javascript aplication for portfolio images upload
 * @package pixelimity
 */

// prevent jQuery Conflict
(function($) {

    /* Document ready
    ------------------------------ */
    $(document).ready(function() {
        // callback
        load_images();

        $('.add-images.enabled').on('click', function() {
            $("#upload-images #images").click();
        });

        $('#upload-images #images').on('change', function() {
            $("#upload-images").ajaxForm({
                beforeSubmit: function() {
                    $('.add-images').removeClass('enabled').addClass('uploading');
                },
                success: function() {
                    $('.add-images').removeClass('uploading').addClass('enabled');
                    load_images();
                },
                error: function() {}
            }).submit();
        });

        $(document).on('click', '.image-item', function() {
            var old_id = $('.image-action .action').attr('data-id');
            if (old_id)
                $('.image-action .action-' + old_id).hide().detach().appendTo('.image-item-' + old_id);

            $('.image-lists .image-item').removeClass('as-thumbnail');
            $(this).addClass('as-thumbnail');
            $(this).find('.action').show().detach().appendTo('.image-action');
        });

        $(document).on('click', '.image-action .action .delete', function() {
            $('#loading').hide().fadeIn(200);

            var id = $(this).attr('data-id'),
                portfolio_id = $('.portfolio-id').val();

            $.post(ajax_url, {
                action: 'delete_image',
                id: id,
                portfolio_id: portfolio_id
            }, function() {
                $('#loading').hide().fadeOut(200);
                $('.image-action .action-' + id + ', .image-item-' + id).remove();
                load_images();
                if ($('.image-item').length <= 1) {
                    $('.image-lists').css({
                        marginTop: 0
                    });
                }
            });
        });

        /* SORTABLE THE IMAGES
        ------------------------------ */
        $(".image-lists").sortable({
            update: function(event, ui) {
                $('#loading').hide().fadeIn(200);
                $.post(ajax_url, {
                    action: 'order_images',
                    images: $('.image-lists').sortable('serialize')
                }, function() {
                    $('#loading').hide().fadeOut(200);
                });
            }
        });

    });
})(window.jQuery);
