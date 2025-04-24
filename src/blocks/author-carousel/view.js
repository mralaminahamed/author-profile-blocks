/**
 * Front-end script for the Author Carousel block
 * Uses Slick Carousel for the sliding functionality
 */

// Import jQuery since Slick depends on it
import jQuery from 'jquery';

// Import Slick Carousel
import 'slick-carousel';

/**
 * Initialize carousels when the DOM is ready
 */
jQuery(document).ready(function ($) {
    // Check if slick is available
    if (typeof $.fn.slick === 'undefined') {
        console.error('Slick Carousel library is not loaded. Author Carousel functionality will be limited.');
        return;
    }
    // Initialize all author carousels on the page
    $('.apb-author-carousel').each(function () {
        const $carousel = $(this);
        const settings = $carousel.data('settings') || {};

        // Default settings
        const defaults = {
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: true,
            arrows: true,
            infinite: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        arrows: false,
                    }
                }
            ]
        };

        // Merge defaults with user settings
        const slickSettings = $.extend({}, defaults, settings);

        // Initialize slick
        $carousel.slick(slickSettings);
    });
});
