/*! 
 * admin javascript aplication for portfolio edit page
 * @package pixelimity
 */

// prevent jQuery Conflict
(function($) {

    /* Document ready
    ------------------------------ */
    $(document).ready(function() {

        if ($('#as-draft').length > 0) {
            if ($('#as-draft').is(':checked')) {
                $('.btn-submit').val('Save Portfolio');
            } else {
                $('.btn-submit').val('Publish Portfolio');
            }
        }

        $('.as-draft').click(function() {
            if ($('#as-draft').is(':checked')) {
                $('.btn-submit').val('Publish Portfolio');
            } else {
                $('.btn-submit').val('Save Portfolio');
            }
        });

        $('.field-images .add-images').hover(
            function() {
                $(this).find('span').fadeIn(400);
            },
            function() {
                $(this).find('span').fadeOut(400);
            }
        );

        $('.description-area .buttons .bold').click(function() {
            insert_at_caret('description', '****', 2);
            return false;
        });
        $('.description-area .buttons .italic').click(function() {
            insert_at_caret('description', '**', 1);
            return false;
        });
        $('.description-area .buttons .h1').click(function() {
            insert_at_caret('description', '\n# ', 0);
            return false;
        });
        $('.description-area .buttons .h2').click(function() {
            insert_at_caret('description', '\n## ', 0);
            return false;
        });
        $('.description-area .buttons .blockquote').click(function() {
            insert_at_caret('description', '\n> ', 0);
            return false;
        });
        $('.description-area .buttons .ul').click(function() {
            insert_at_caret('description', '\n* \n* ', 3);
            return false;
        });
        $('.description-area .buttons .ol').click(function() {
            insert_at_caret('description', '\n1. \n2. ', 4);
            return false;
        });

    });
})(window.jQuery);
