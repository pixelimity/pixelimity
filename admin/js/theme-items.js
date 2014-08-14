/*! 
 * admin javascript aplication for themes
 * @package pixelimity
 */

// prevent jQuery Conflict
(function($) {

    /* Document ready
    ------------------------------ */
    $(document).ready(function() {
        // if install theme is enabled and has clicked
        $('.install-theme.enabled').on('click', function(e) {
            // prevent do original proccessing
            e.preventDefault();
            $("#install-theme #theme").click();
        });

        // when on change
        $('#install-theme #theme').on('change', function() {
            $("#install-theme").ajaxForm({
                beforeSubmit: function() {
                    $('.install-theme').removeClass('enabled').addClass('uploading').text('Uploading...');
                },
                success: function(data) {
                    $('.install-theme').removeClass('uploading').addClass('enabled').text('Install New Theme');
                    window.location.href = admin_url + '/themes.php?message=3';
                },
                error: function() {}
            }).submit();
        });

    });
})(window.jQuery);
