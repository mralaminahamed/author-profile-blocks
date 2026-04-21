---
layout: default
title: Home
nav_order: 1
permalink: /
---

# Author Profile Blocks
{: .fs-9 .no_toc }

<div class="apbl-eyebrow">✦ WordPress Gutenberg Plugin</div>

Four production-ready Gutenberg blocks for showcasing WordPress users — profile, grid, list, and carousel — with an indigo editorial design system.
{: .fs-6 .fw-300 }

[Get Started]({{ site.baseurl }}{% link getting-started.md %}){: .btn .btn-primary .fs-5 .mb-4 .mb-md-0 .mr-2 }
[View on GitHub](https://github.com/mralaminahamed/author-profile-blocks){: .btn .fs-5 .mb-4 .mb-md-0 }

<div class="apbl-version-badge"><span class="apbl-version-tag">v1.0.1</span><span>Tested on WordPress 6.7 · PHP 7.4+</span></div>

---

<div class="apbl-palette"><span class="apbl-palette-label">Design palette</span><div class="apbl-palette-swatches"><div class="apbl-swatch" style="background:#1e1b4b"></div><div class="apbl-swatch" style="background:#3730a3"></div><div class="apbl-swatch" style="background:#4f46e5"></div><div class="apbl-swatch" style="background:#818cf8"></div><div class="apbl-swatch" style="background:#c7d2fe"></div><div class="apbl-swatch" style="background:#eef2ff"></div><div class="apbl-swatch" style="background:#f59e0b"></div><div class="apbl-swatch" style="background:#f1f1f6"></div><div class="apbl-swatch" style="background:#ffffff;border:1px solid #e2e2ec"></div></div></div>

<div class="apbl-section-heading"><h2>Blocks</h2><div class="apbl-section-line"></div></div>

<div class="apbl-blocks-grid">

<div class="apbl-block-card apbl-card-profile"><div class="apbl-block-card-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg></div><h3>Author Profile</h3><p>Single-author display with card, compact, and centered layouts. Perfect for about pages and post signatures.</p><div class="apbl-block-card-footer"><span class="apbl-block-card-slug">author-profile</span><a href="{{ site.baseurl }}{% link blocks/author-profile.md %}" class="apbl-block-card-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a></div></div>

<div class="apbl-block-card apbl-card-grid"><div class="apbl-block-card-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></div><h3>Author Grid</h3><p>Responsive 1–4 column grid with author picker and role filter. Ideal for team and contributor pages.</p><div class="apbl-block-card-footer"><span class="apbl-block-card-slug">author-grid</span><a href="{{ site.baseurl }}{% link blocks/author-grid.md %}" class="apbl-block-card-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a></div></div>

<div class="apbl-block-card apbl-card-carousel"><div class="apbl-block-card-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/><path d="m15 18 6-6-6-6"/></svg></div><h3>Author Carousel</h3><p>Slick-powered sliding carousel with autoplay, arrows, dots, and responsive breakpoints (3 → 2 → 1).</p><div class="apbl-block-card-footer"><span class="apbl-block-card-slug">author-carousel</span><a href="{{ site.baseurl }}{% link blocks/author-carousel.md %}" class="apbl-block-card-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a></div></div>

<div class="apbl-block-card apbl-card-list"><div class="apbl-block-card-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></div><h3>Author List</h3><p>Vertical flex directory with compact and detailed display styles, gap, dividers, and hover effects.</p><div class="apbl-block-card-footer"><span class="apbl-block-card-slug">author-list</span><a href="{{ site.baseurl }}{% link blocks/author-list.md %}" class="apbl-block-card-arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a></div></div>

</div>

<div class="apbl-section-heading"><h2>Key Features</h2><div class="apbl-section-line"></div></div>

<div class="apbl-features-grid">
<div class="apbl-feature-item"><div class="apbl-feature-dot" style="background:#4f46e5"></div><div><h4>No Custom Post Types</h4><p>Uses native WordPress users — no duplicate content, no migration, no extra DB tables.</p></div></div>
<div class="apbl-feature-item"><div class="apbl-feature-dot" style="background:#0891b2"></div><div><h4>Server-side Rendering</h4><p>All blocks use PHP <code>render_callback</code> — always-fresh output, no client hydration.</p></div></div>
<div class="apbl-feature-item"><div class="apbl-feature-dot" style="background:#7c3aed"></div><div><h4>Indigo Design System</h4><p>Consistent <code>#4f46e5</code> palette, CSS custom properties, editorial card hover animations.</p></div></div>
<div class="apbl-feature-item"><div class="apbl-feature-dot" style="background:#059669"></div><div><h4>Animations &amp; Hover Effects</h4><p>7 entry animations, 5 hover effects — all durations controlled by CSS custom property.</p></div></div>
<div class="apbl-feature-item"><div class="apbl-feature-dot" style="background:#f59e0b"></div><div><h4>Lucide React Icons</h4><p>Editor controls use crisp SVG Lucide icons — no emoji, no Dashicons in the block UI.</p></div></div>
<div class="apbl-feature-item"><div class="apbl-feature-dot" style="background:#db2777"></div><div><h4>Social Profiles</h4><p>Facebook, Twitter, LinkedIn, Instagram, website — stored in <code>apbl_social_profiles</code> user meta.</p></div></div>
</div>

<div class="apbl-quickstart">
<div class="apbl-quickstart-label">Quick Start</div>
<h3>Up and running in 3 steps</h3>
<ol class="apbl-quickstart-steps">
<li class="apbl-step"><div class="apbl-step-num">1</div><div class="apbl-step-text"><strong>Install</strong> from WP.org or upload the ZIP via <em>Plugins → Add New → Upload Plugin</em>.</div></li>
<li class="apbl-step"><div class="apbl-step-num">2</div><div class="apbl-step-text"><strong>Enrich profiles</strong> — edit any user, fill in <em>Author Profile Information</em> (position, bio, social links).</div></li>
<li class="apbl-step"><div class="apbl-step-num">3</div><div class="apbl-step-text"><strong>Insert a block</strong> — open the block inserter, search <code>Author</code>, pick a block, configure in the sidebar.</div></li>
</ol>
</div>

<div class="apbl-callout apbl-callout-info"><svg class="apbl-callout-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><div class="apbl-callout-body"><strong>Requirements</strong>WordPress 6.0+, PHP 7.4+, any theme with block editor support. The carousel block requires jQuery (bundled in WordPress core).</div></div>

<div class="apbl-section-heading"><h2>Need Help?</h2><div class="apbl-section-line"></div></div>

<div class="apbl-help-grid">
<a href="{{ site.baseurl }}{% link faq.md %}" class="apbl-help-item"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>FAQ</a>
<a href="{{ site.baseurl }}{% link troubleshooting.md %}" class="apbl-help-item"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Troubleshooting</a>
<a href="https://github.com/mralaminahamed/author-profile-blocks/issues" class="apbl-help-item"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>GitHub Issues</a>
</div>
