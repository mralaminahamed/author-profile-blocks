/**
 * Custom JavaScript for Author Profile Blocks Documentation
 * Adds interactive features to the documentation site
 */

document.addEventListener("DOMContentLoaded", function () {
	// Initialize copy code buttons
	initializeCodeCopyButtons();

	// Enhance callouts with interactive features
	enhanceCallouts();

	// Add smooth scrolling to anchor links
	initializeSmoothScrolling();

	// Initialize mobile navigation improvements
	improveMobileNavigation();

	// Initialize floating table of contents
	initializeFloatingToc();

	// Initialize progress indicator
	initializeProgressIndicator();

	// Initialize enhanced search
	initializeEnhancedSearch();

	// Initialize code tabs
	initializeCodeTabs();

	// Add image lazy loading
	initializeLazyLoading();

	// Initialize table of contents highlighting
	initializeTocHighlighting();
});

/**
 * Adds copy buttons to all code blocks
 */
function initializeCodeCopyButtons() {
	// Find all pre elements with code
	const codeBlocks = document.querySelectorAll("pre");

	codeBlocks.forEach(function (codeBlock, index) {
		// Create header for the code block
		const header = document.createElement("div");
		header.className = "code-header";

		// Create copy button
		const copyButton = document.createElement("button");
		copyButton.className = "copy-code-button";
		copyButton.textContent = "Copy";
		copyButton.setAttribute("aria-label", "Copy code to clipboard");
		copyButton.setAttribute("data-code-index", index);

		// Add click event to copy button
		copyButton.addEventListener("click", function () {
			const code = codeBlock.querySelector("code")
				? codeBlock.querySelector("code").textContent
				: codeBlock.textContent;

			// Copy to clipboard
			navigator.clipboard.writeText(code).then(
				function () {
					// Success feedback
					copyButton.textContent = "Copied!";
					copyButton.classList.add("copied");

					// Reset button text after 2 seconds
					setTimeout(function () {
						copyButton.textContent = "Copy";
						copyButton.classList.remove("copied");
					}, 2000);
				},
				function () {
					// Error feedback
					copyButton.textContent = "Failed to copy";
					copyButton.classList.add("copy-error");

					// Reset button text after 2 seconds
					setTimeout(function () {
						copyButton.textContent = "Copy";
						copyButton.classList.remove("copy-error");
					}, 2000);
				},
			);
		});

		// Add button to header
		header.appendChild(copyButton);

		// Insert header before code block
		codeBlock.parentNode.insertBefore(header, codeBlock);

		// Ensure code block has the right border radius
		codeBlock.style.borderTopLeftRadius = "0";
		codeBlock.style.borderTopRightRadius = "0";
	});
}

/**
 * Enhance callouts with collapsible functionality
 */
function enhanceCallouts() {
	const callouts = document.querySelectorAll(".callout");

	callouts.forEach(function (callout) {
		// Add hover effect
		callout.addEventListener("mouseenter", function () {
			callout.classList.add("callout-hover");
		});

		callout.addEventListener("mouseleave", function () {
			callout.classList.remove("callout-hover");
		});

		// Find the callout title if present (first strong or b element)
		const title = callout.querySelector("strong, b");

		if (title) {
			// Make the title more prominent
			title.style.display = "block";
			title.style.fontSize = "1.1em";
			title.style.marginBottom = "0.5rem";
		}
	});
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initializeSmoothScrolling() {
	// Get all anchor links
	const anchorLinks = document.querySelectorAll('a[href^="#"]');

	anchorLinks.forEach(function (link) {
		link.addEventListener("click", function (e) {
			// Get the target element
			const targetId = this.getAttribute("href").substring(1);

			if (!targetId) return; // Skip if href is just "#"

			const targetElement = document.getElementById(targetId);

			if (targetElement) {
				e.preventDefault();

				// Add smooth scrolling
				window.scrollTo({
					top: targetElement.offsetTop - 80, // Offset for fixed header
					behavior: "smooth",
				});

				// Update URL hash without scrolling
				history.pushState(null, null, `#${targetId}`);

				// Focus the target for accessibility
				targetElement.setAttribute("tabindex", "-1");
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
	const navToggle = document.querySelector(".js-main-nav-trigger");

	if (navToggle) {
		// Add transition effect to the sidebar
		const sidebar = document.querySelector(".side-bar");
		if (sidebar) {
			sidebar.style.transition = "transform 0.3s ease";
		}

		// Close menu when clicking outside
		document.addEventListener("click", function (event) {
			const isNavOpen = document.body.classList.contains("nav-open");
			const clickedInsideNav = event.target.closest(".side-bar");
			const clickedOnToggle = event.target.closest(
				".js-main-nav-trigger",
			);

			if (isNavOpen && !clickedInsideNav && !clickedOnToggle) {
				navToggle.click(); // Close the navigation
			}
		});

		// Close menu when pressing escape key
		document.addEventListener("keydown", function (event) {
			if (
				event.key === "Escape" &&
				document.body.classList.contains("nav-open")
			) {
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

/**
 * Initialize table of contents highlighting
 */
function initializeTocHighlighting() {
    // Get the table of contents
    const toc = document.querySelector(".toc");

    if (!toc) return;

    // Get all headings that should be in the TOC
    const headings = document.querySelectorAll("h1, h2, h3, h4, h5, h6");

    // Create intersection observer
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                // Find the corresponding TOC link
                const id = entry.target.id;
                const tocLink = document.querySelector(`.toc a[href="#${id}"]`);

                if (tocLink) {
                    // Remove active class from all TOC links
                    document.querySelectorAll(".toc a").forEach(function(link) {
                        link.classList.remove("active");
                    });

                    // Add active class to current TOC link
                    tocLink.classList.add("active");
                }
            }
        });
    }, {
        rootMargin: "-80px 0px -80% 0px",
        threshold: 0
    });

    // Observe all headings
    headings.forEach(function(heading) {
        if (heading.id) {
            observer.observe(heading);
        }
    });
}

/**
 * Initialize floating table of contents
 */
function initializeFloatingToc() {
    // Check if we have a table of contents
    const toc = document.querySelector(".toc");
    if (!toc) return;

    // Create floating TOC container
    const floatingToc = document.createElement("div");
    floatingToc.className = "apb-floating-toc";

    // Create toggle button
    const toggleButton = document.createElement("button");
    toggleButton.className = "apb-floating-toc-toggle";
    toggleButton.setAttribute("aria-label", "Toggle table of contents");

    // Clone TOC content
    const tocContent = toc.cloneNode(true);
    tocContent.className = "apb-floating-toc-content";

    // Add title
    const title = document.createElement("h3");
    title.className = "apb-floating-toc-title";
    title.textContent = "Table of Contents";

    floatingToc.appendChild(title);
    floatingToc.appendChild(tocContent);

    // Add to page
    document.body.appendChild(floatingToc);
    document.body.appendChild(toggleButton);

    // Toggle functionality
    let isVisible = false;
    toggleButton.addEventListener("click", function() {
        isVisible = !isVisible;
        if (isVisible) {
            floatingToc.classList.add("visible");
            toggleButton.style.right = "320px";
        } else {
            floatingToc.classList.remove("visible");
            toggleButton.style.right = "20px";
        }
    });

    // Close on outside click
    document.addEventListener("click", function(event) {
        if (!floatingToc.contains(event.target) && !toggleButton.contains(event.target) && isVisible) {
            isVisible = false;
            floatingToc.classList.remove("visible");
            toggleButton.style.right = "20px";
        }
    });

    // Smooth scroll for TOC links
    floatingToc.addEventListener("click", function(event) {
        if (event.target.tagName === "A") {
            event.preventDefault();
            const targetId = event.target.getAttribute("href").substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: "smooth"
                });

                // Update URL
                history.pushState(null, null, `#${targetId}`);
            }
        }
    });
}

/**
 * Initialize progress indicator
 */
function initializeProgressIndicator() {
    // Create progress bar
    const progressContainer = document.createElement("div");
    progressContainer.className = "apb-progress";

    const progressBar = document.createElement("div");
    progressBar.className = "apb-progress-bar";

    progressContainer.appendChild(progressBar);
    document.body.insertBefore(progressContainer, document.body.firstChild);

    // Update progress on scroll
    function updateProgress() {
        const scrollTop = window.pageYOffset;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;

        progressBar.style.width = scrollPercent + "%";
    }

    window.addEventListener("scroll", updateProgress);
    updateProgress(); // Initial call
}

/**
 * Initialize enhanced search
 */
function initializeEnhancedSearch() {
    const searchInput = document.querySelector(".search-input");
    if (!searchInput) return;

    let searchTimeout;
    let resultsContainer;

    searchInput.addEventListener("input", function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });

    function performSearch() {
        const query = searchInput.value.toLowerCase().trim();
        if (query.length < 2) {
            hideResults();
            return;
        }

        // Simple client-side search (in a real implementation, this would be server-side)
        const results = searchContent(query);

        if (results.length > 0) {
            showResults(results);
        } else {
            showNoResults();
        }
    }

    function searchContent(query) {
        const contentElements = document.querySelectorAll("h1, h2, h3, h4, h5, h6, p, li");
        const results = [];

        contentElements.forEach(function(element) {
            if (element.textContent.toLowerCase().includes(query)) {
                // Find the nearest heading
                let heading = element;
                while (heading && !/^H[1-6]$/.test(heading.tagName)) {
                    heading = heading.previousElementSibling;
                }

                if (heading && heading.id) {
                    const existingResult = results.find(r => r.id === heading.id);
                    if (!existingResult) {
                        results.push({
                            id: heading.id,
                            title: heading.textContent,
                            preview: getPreviewText(element, query),
                            url: "#" + heading.id
                        });
                    }
                }
            }
        });

        return results.slice(0, 5); // Limit to 5 results
    }

    function getPreviewText(element, query) {
        const text = element.textContent;
        const index = text.toLowerCase().indexOf(query);
        const start = Math.max(0, index - 50);
        const end = Math.min(text.length, index + 50);
        let preview = text.substring(start, end);

        if (start > 0) preview = "..." + preview;
        if (end < text.length) preview = preview + "...";

        return preview;
    }

    function showResults(results) {
        hideResults();

        resultsContainer = document.createElement("div");
        resultsContainer.className = "search-results";

        results.forEach(function(result) {
            const item = document.createElement("div");
            item.className = "search-result-item";

            item.innerHTML = `
                <div class="search-result-title">${highlightText(result.title, searchInput.value)}</div>
                <div class="search-result-preview">${highlightText(result.preview, searchInput.value)}</div>
                <div class="search-result-url">${result.url}</div>
            `;

            item.addEventListener("click", function() {
                window.location.hash = result.url;
                hideResults();
                searchInput.value = "";
            });

            resultsContainer.appendChild(item);
        });

        searchInput.parentNode.appendChild(resultsContainer);
    }

    function showNoResults() {
        hideResults();

        resultsContainer = document.createElement("div");
        resultsContainer.className = "search-results";

        const noResults = document.createElement("div");
        noResults.className = "search-result-item";
        noResults.innerHTML = "<div class=\"search-result-title\">No results found</div>";

        resultsContainer.appendChild(noResults);
        searchInput.parentNode.appendChild(resultsContainer);
    }

    function hideResults() {
        if (resultsContainer) {
            resultsContainer.remove();
            resultsContainer = null;
        }
    }

    function highlightText(text, query) {
        const regex = new RegExp(`(${query})`, "gi");
        return text.replace(regex, "<mark>$1</mark>");
    }

    // Hide results when clicking outside
    document.addEventListener("click", function(event) {
        if (!searchInput.contains(event.target) && resultsContainer && !resultsContainer.contains(event.target)) {
            hideResults();
        }
    });
}

/**
 * Initialize code tabs
 */
function initializeCodeTabs() {
    const codeTabs = document.querySelectorAll(".apb-code-tabs");

    codeTabs.forEach(function(tabsContainer) {
        const tabButtons = tabsContainer.querySelectorAll(".apb-code-tab");
        const tabContents = tabsContainer.querySelectorAll(".apb-code-tab-content");

        tabButtons.forEach(function(button, index) {
            button.addEventListener("click", function() {
                // Remove active class from all tabs
                tabButtons.forEach(function(btn) {
                    btn.classList.remove("active");
                });

                // Add active class to clicked tab
                button.classList.add("active");

                // Hide all tab contents
                tabContents.forEach(function(content) {
                    content.classList.remove("active");
                });

                // Show corresponding content
                tabContents[index].classList.add("active");
            });
        });
    });
}

/**
 * Initialize lazy loading for images
 */
function initializeLazyLoading() {
    const images = document.querySelectorAll("img[data-src]");

    if ("IntersectionObserver" in window) {
        const imageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove("lazy");
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(function(img) {
            imageObserver.observe(img);
        });
    } else {
        // Fallback for browsers without IntersectionObserver
        images.forEach(function(img) {
            img.src = img.dataset.src;
        });
    }
}
