/*! 
 * admin javascript aplication
 * @package pixelimity
 */

// prevent jQuery Conflict
(function($) {

    /* Document ready
    ------------------------------ */
    $(document).ready(function() {
        /* Add JS when browser enable javascript
         * and this script is rendered for different rule
         * --------------------------------------- */
        $('#main, #admin-menu, #footer,#mobilebutton').addClass('js');
        $('#mobilebutton').click(function(e) {
            // prevent original proccessing
            e.preventDefault();
            // toggle class active to button
            $(this).toggleClass('active')
            // toggle class on certain rule id
            $('#main, #admin-menu, #footer').toggleClass('active');
        });

        /* Add preloader animation when windows load
         ----------------------------------------- */
        $(window).load(function() {
            // hide the loaded 
            $('#loaded').fadeIn(200);
            // add interval 0.8 second / wait the browser loads
            setInterval(function() {
                $('#loaded').fadeOut(200);
            }, 800);
        });

    });
})(window.jQuery);
