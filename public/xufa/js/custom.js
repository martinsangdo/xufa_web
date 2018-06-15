jQuery(document).ready(function() {
    // Top bar menu
    jQuery( '.dt-sec-nav' ).on( 'click', function(){
        jQuery( '.dt-sec-menu' ).toggleClass( 'dt-sec-menu-show' );
        jQuery(this).find( '.fa' ).toggleClass( 'fa-bars fa-close' );
    });

    jQuery(document).on( 'click', function (e) {
        if ( jQuery( e.target).closest( '.dt-bar-left' ).length === 0 ) {
            jQuery( '.dt-sec-menu' ).removeClass( 'dt-sec-menu-show' );
            jQuery( '.dt-sec-nav .fa-close' ).addClass( 'fa-bars').removeClass( 'fa-close' );
        }
    });

    // Top Search bar
    jQuery( '.dt-search-icon' ).on( 'click', function(){
        jQuery( '.dt-search-bar' ).toggleClass( 'dt-search-bar-show' );
        jQuery(this).find( '.fa' ).toggleClass( 'fa-search fa-close' );
    });

    jQuery(document).on( 'click', function (e) {
        if ( jQuery( e.target).closest( '.dt-search-bar, .dt-search-icon' ).length === 0 ) {
            jQuery( '.dt-search-bar' ).removeClass( 'dt-search-bar-show' );
            jQuery( '.dt-search-icon .fa-close' ).addClass( 'fa-search' ).removeClass( 'fa-close' );
        }
    });

    // Main Menu Mobile
    jQuery( '.dt-nav-md-trigger' ).on( 'click', function(){
        jQuery( '.dt-nav-md' ).toggleClass( 'dt-nav-md-expand' );
        jQuery(this).find( '.fa' ).toggleClass( 'fa-bars fa-close' );
    });

    // Top Social Sticky bar
    jQuery( '.dt-social-trigger' ).on( 'click', function(){
        jQuery( '.dt-social-sticky-bar' ).toggleClass( 'dt-social-sticky-bar-show' );
        jQuery(this).find( '.fa' ).toggleClass( 'fa-share-alt fa-close' );
    });

    jQuery(document).on( 'click', function (e) {
        if ( jQuery( e.target).closest( '.dt-social-sticky-bar, .dt-social-trigger' ).length === 0 ) {
            jQuery( '.dt-social-sticky-bar' ).removeClass( 'dt-social-sticky-bar-show' );
            jQuery( '.dt-social-trigger .fa-close' ).addClass( 'fa-share-alt' ).removeClass( 'fa-close' );
        }
    });

    jQuery(document).on( 'click', function (e) {
        if ( jQuery( e.target).closest( '.dt-bar-left' ).length === 0 ) {
            jQuery( '.dt-sec-menu' ).removeClass( 'dt-sec-menu-show' );
            jQuery( '.dt-sec-nav .fa-close' ).addClass( 'fa-bars').removeClass( 'fa-close' );
        }
    });

    // Convert Hex to RGBA
    function convertHex( hex, opacity ){
        hex = hex.replace('#','');
        r = parseInt(hex.substring(0,2), 16);
        g = parseInt(hex.substring(2,4), 16);
        b = parseInt(hex.substring(4,6), 16);

        result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
        return result;
    }

    // News Ticker
    jQuery('.dt-newsticker').newsTicker({
        row_height: 42,
        max_rows: 1,
        speed: 600,
        direction: 'up',
        duration: 3500,
        autostart: 1,
        pauseOnHover: 1
    });

    // Initialize post slider
    var dt_banner_slider = new Swiper('.dt-featured-post-slider', {
        paginationClickable: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: 3000,
        speed: 600
    });

    // Back to Top
    if (jQuery('#back-to-top').length) {
        var scrollTrigger = 600, // px
            backToTop = function () {
                var scrollTop = jQuery(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    jQuery('#back-to-top').addClass('show');
                } else {
                    jQuery('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        jQuery(window).on('scroll', function () {
            backToTop();
        });
        jQuery('#back-to-top').on('click', function (e) {
            e.preventDefault();
            jQuery('html,body').animate({
                scrollTop: 0
            }, 600);
        });
    }

    // Sticky Menu
    var stickyNavTop = jQuery( '.dt-sticky' );
    if (!stickyNavTop.length) {
        return;
    }
    var stickyNavTop = stickyNavTop.offset().top;

    var stickyNav = function(){
        var scrollTop = jQuery(window).scrollTop();

        if (scrollTop > stickyNavTop) {
            jQuery( '.dt-sticky' ).addClass( 'dt-menu-bar-sticky');
        } else {
            jQuery( '.dt-sticky' ).removeClass( 'dt-menu-bar-sticky' );
        }
    };

    stickyNav();
    jQuery(window).scroll(function() {
        stickyNav();
    });

});
