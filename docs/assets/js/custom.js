/**
 * Custom JavaScript for Author Profile Blocks Documentation
 * Adds interactive features to the documentation site
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize copy code buttons
    initializeCodeCopyButtons();

    // Enhance callouts with interactive features
    enhanceCallouts();

    // Add smooth scrolling to anchor links
    initializeSmoothScrolling();

    // Initialize mobile navigation improvements
    improveMobileNavigation();

    // Add image zoom functionality
   //  initializeImageZoom();

    // Initialize table of contents highlighting
    initializeTocHighlighting();
});

/**
 * Adds copy buttons to all code blocks
 */
function initializeCodeCopyButtons() {
    // Find all pre elements with code
    const codeBlocks = document.querySelectorAll('pre');

    codeBlocks.forEach(function(codeBlock, index) {
        // Create header for the code block
        const header = document.createElement('div');
        header.className = 'code-header';

        // Create copy button
        const copyButton = document.createElement('button');
        copyButton.className = 'copy-code-button';
        copyButton.textContent = 'Copy';
        copyButton.setAttribute('aria-label', 'Copy code to clipboard');
        copyButton.setAttribute('data-code-index', index);

        // Add click event to copy button
        copyButton.addEventListener('click', function() {
            const code = codeBlock.querySelector('code') ?
                codeBlock.querySelector('code').textContent :
                codeBlock.textContent;

            // Copy to clipboard
            navigator.clipboard.writeText(code).then(function() {
                // Success feedback
                copyButton.textContent = 'Copied!';
                copyButton.classList.add('copied');

                // Reset button text after 2 seconds
                setTimeout(function() {
                    copyButton.textContent = 'Copy';
                    copyButton.classList.remove('copied');
                }, 2000);
            }, function() {
                // Error feedback
                copyButton.textContent = 'Failed to copy';
                copyButton.classList.add('copy-error');

                // Reset button text after 2 seconds
                setTimeout(function() {
                    copyButton.textContent = 'Copy';
                    copyButton.classList.remove('copy-error');
                }, 2000);
            });
        });

        // Add button to header
        header.appendChild(copyButton);

        // Insert header before code block
        codeBlock.parentNode.insertBefore(header, codeBlock);

        // Ensure code block has the right border radius
        codeBlock.style.borderTopLeftRadius = '0';
        codeBlock.style.borderTopRightRadius = '0';
    });
}

/**
 * Enhance callouts with collapsible functionality
 */
function enhanceCallouts() {
    const callouts = document.querySelectorAll('.callout');

    callouts.forEach(function(callout) {
        // Add hover effect
        callout.addEventListener('mouseenter', function() {
            callout.classList.add('callout-hover');
        });

        callout.addEventListener('mouseleave', function() {
            callout.classList.remove('callout-hover');
        });

        // Find the callout title if present (first strong or b element)
        const title = callout.querySelector('strong, b');

        if (title) {
            // Make the title more prominent
            title.style.display = 'block';
            title.style.fontSize = '1.1em';
            title.style.marginBottom = '0.5rem';
        }
    });
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initializeSmoothScrolling() {
    // Get all anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Get the target element
            const targetId = this.getAttribute('href').substring(1);

            if (!targetId) return; // Skip if href is just "#"

            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                e.preventDefault();

                // Add smooth scrolling
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Offset for fixed header
                    behavior: 'smooth'
                });

                // Update URL hash without scrolling
                history.pushState(null, null, `#${targetId}`);

                // Focus the target for accessibility
                targetElement.setAttribute('tabindex', '-1');
                targetElement.focus({ preventScroll: true });
            }
        });
    });
}

/**
 * Improve mobile navigation experience
 */
function improveMobileNavigation() {
    // Get the navigation toggle button
    const navToggle = document.querySelector('.js-main-nav-trigger');

    if (navToggle) {
        // Add transition effect to the sidebar
        const sidebar = document.querySelector('.side-bar');
        if (sidebar) {
            sidebar.style.transition = 'transform 0.3s ease';
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isNavOpen = document.body.classList.contains('nav-open');
            const clickedInsideNav = event.target.closest('.side-bar');
            const clickedOnToggle = event.target.closest('.js-main-nav-trigger');

            if (isNavOpen && !clickedInsideNav && !clickedOnToggle) {
                navToggle.click(); // Close the navigation
            }
        });

        // Close menu when pressing escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && document.body.classList.contains('nav-open')) {
                navToggle.click(); // Close the navigation
            }
        });
    }
}

/**
 * Add image zoom functionality
 */
// function initializeImageZoom() {
//     // Get all images except icons and logos
//     const images = document.querySelectorAll('.main-content img:not(.site-logo):not(.apb-feature-icon img)');
//
//     images.forEach(function(img) {
//         // Make images interactive
//         img.style.cursor = 'zoom-in';
//         img.setAttribute('tabindex', '0');
//
//         // Create a lightweight zoom effect
//         img.addEventListener('click', function() {
//             toggleImageZoom(this);
//         });
//
//         // Support keyboard navigation
//         img.addEventListener('keydown', function(e) {
//             if (e.key === 'Enter' || e.key === ' ') {
//                 e.preventDefault();
//                 toggleImageZoom(this);
//             }
//         });
//     });
//
//     // Function to toggle zoom state
//     function toggleImageZoom(img) {
//         if (img.classList.contains('zoomed')) {
//             // Unzoom
//             img.classList.remove('zoomed');
//             img.style.cursor = 'zoom-in';
//
//             // Remove the overlay
//             const overlay = document.querySelector('.image-zoom-overlay');
//             if (overlay) {
//                 document.body.removeChild(overlay);
//                 document.body.style.overflow = '';
//             }
//         } else {
//             // Zoom
//             img.classList.add('zoomed');
//             img.style.cursor = 'zoom-out';
//
//             // Create an overlay
//             const overlay = document.createElement('div');
//             overlay.className = 'image-zoom-overlay';
//             overlay.style.position = 'fixed';
//             overlay.style.top = '0';
//             overlay.style.left = '0';
//             overlay.style.width = '100%';
//             overlay.style.height = '100%';
//             overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
//             overlay.style.zIndex = '9999';
//             overlay.style.display = 'flex';
//             overlay.style.alignItems = 'center';
//             overlay.style.justifyContent = 'center';
//             overlay.style