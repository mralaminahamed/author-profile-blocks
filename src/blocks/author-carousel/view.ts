/**
 * Front-end script for the Author Carousel block
 * Uses Slick Carousel for the sliding functionality
 */

// Import jQuery since Slick depends on it
import jQuery from 'jquery';

// Import Slick Carousel JS + core layout CSS
import 'slick-carousel';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

/**
 * Initialize carousels when the DOM is ready
 */
jQuery( document ).ready( function( $ ) {
	// Check if slick is available
	if ( typeof $.fn.slick === 'undefined' ) {
		console.error(
			'Slick Carousel library is not loaded. Author Carousel functionality will be limited.',
		);
		return;
	}
	// Initialize all author carousels on the page
	$( '.apbl-author-carousel' ).each( function() {
		const $carousel = $( this );
		const settings = $carousel.data( 'settings' ) || {};
		// Direct .apbl-author-carousel-item children are the slick slides.
		// Use that selector rather than .children() because the DOM may
		// contain wrapper nodes that slick shouldn't treat as slides.
		const slideCount = $carousel.find( '> .apbl-author-carousel-item' ).length
			|| $carousel.children().length;

		// Slick's initADA crashes when slideCount <= 1. Skip init entirely;
		// the markup degrades gracefully to a static stack of cards.
		if ( slideCount <= 1 ) {
			return;
		}

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
					},
				},
				{
					breakpoint: 576,
					settings: {
						slidesToShow: 1,
						arrows: false,
					},
				},
			],
		};

		// Merge defaults with user settings
		const slickSettings = $.extend( {}, defaults, settings );

		// Slick's ADA / arrow init crashes when slideCount <= slidesToShow.
		// Disable arrows + dots + infinite + autoplay in that case.
		if ( slideCount <= ( slickSettings.slidesToShow ?? 3 ) ) {
			slickSettings.arrows = false;
			slickSettings.dots = false;
			slickSettings.infinite = false;
			slickSettings.autoplay = false;
		}

		// Initialize slick
		$carousel.slick( slickSettings );
	} );
} );
