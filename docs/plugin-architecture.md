---
layout: default
title: Plugin Architecture
parent: Blocks
nav_order: 11
permalink: /plugin-architecture/
---

# Plugin Architecture
{: .no_toc }

Detailed overview of the Author Profile Blocks plugin structure, design patterns, and implementation.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Overview

Author Profile Blocks is built with modern WordPress development practices, following PSR-4 autoloading, dependency injection, and clean architecture principles. The plugin is designed for maintainability, extensibility, and performance.

## Directory Structure

```
author-profile-blocks/
├── author-profile-blocks.php          # Main plugin file with headers
├── class-author-profile-blocks.php    # Main plugin class (legacy)
├── index.php                          # Security file
├── uninstall.php                      # Uninstall cleanup
├── includes/                          # PSR-4 autoloaded classes
│   ├── Admin/                        # Admin interface classes
│   │   ├── admin.php                 # Admin hooks and setup
│   │   └── pluginlinks.php           # Plugin action links
│   ├── Blocks/                       # Gutenberg block classes
│   │   ├── Author_Block_Base.php     # Abstract base class for blocks
│   │   ├── Author_Carousel_Block.php # Carousel block implementation
│   │   ├── Author_Grid_Block.php     # Grid block implementation
│   │   ├── Author_List_Block.php     # List block implementation
│   │   └── Author_Profile_Block.php  # Profile block implementation
│   ├── Core/                         # Core functionality
│   │   ├── Meta_Data_Provider.php    # Meta field management
│   │   ├── Registerable.php          # Interface for registerable classes
│   │   └── User_Meta_Provider.php    # User meta field provider
│   └── Services/                     # Business logic services
│       └── Author_Profile_Service.php # Author data service
├── src/                              # Frontend assets (JavaScript/React/SCSS)
│   ├── blocks/                       # Block-specific implementations
│   │   ├── author-carousel/          # Carousel block files
│   │   ├── author-grid/              # Grid block files
│   │   ├── author-list/              # List block files
│   │   └── author-profile/           # Profile block files
│   ├── js/                           # Shared JavaScript utilities
│   │   ├── components/               # Reusable React components
│   │   ├── hooks/                    # Custom React hooks
│   │   └── services/                 # API services
│   └── scss/                         # Stylesheets
│       ├── common/                   # Shared SCSS variables/mixins
│       └── blocks/                   # Block-specific styles
├── templates/                        # PHP templates
│   ├── admin/                        # Admin templates
│   ├── blocks/                       # Block output templates
│   └── components/                   # Reusable template components
├── languages/                        # Internationalization files
├── tests/                            # Test suites
│   ├── php/                          # PHPUnit tests
│   └── pw/                           # Playwright E2E tests
├── docs/                            # Documentation
└── build/                           # Compiled assets (generated)
```

## Class Architecture

### Main Plugin Class

**File**: `author-profile-blocks.php`  
**Pattern**: Singleton with dependency injection

The main plugin file serves as the entry point and follows WordPress plugin headers standards:

```php
<?php
/**
 * Plugin Name: Author Profile Blocks
 * Plugin URI: https://wordpress.org/plugins/author-profile-blocks/
 * Description: Display author profiles in various Gutenberg block layouts
 * Version: 1.1.0
 * Author: Al Amin Ahamed
 * License: GPL-2.0+
 * Text Domain: author-profile-blocks
 * Requires at least: 6.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Autoloader
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

// Initialize plugin
function author_profile_blocks_init() {
    $plugin = AuthorProfileBlocks\Plugin::get_instance();
    $plugin->run();
}
add_action('plugins_loaded', 'author_profile_blocks_init');
```

### PSR-4 Autoloading

The plugin uses Composer for PSR-4 autoloading:

```json
{
    "autoload": {
        "psr-4": {
            "AuthorProfileBlocks\\": "includes/"
        }
    }
}
```

This allows clean namespace usage:

```php
use AuthorProfileBlocks\Admin\Admin;
use AuthorProfileBlocks\Blocks\Author_Profile_Block;
use AuthorProfileBlocks\Services\Author_Profile_Service;
```

### Service Layer Pattern

The plugin implements a service layer for business logic separation:

```php
// Author_Profile_Service.php
class Author_Profile_Service {
    private $cache;
    private $user_meta_provider;
    
    public function __construct($cache = null, $user_meta_provider = null) {
        $this->cache = $cache ?: new WP_Object_Cache();
        $this->user_meta_provider = $user_meta_provider ?: new User_Meta_Provider();
    }
    
    public function get_author_data($user_id) {
        $cache_key = "author_data_{$user_id}";
        
        $data = $this->cache->get($cache_key);
        if ($data === false) {
            $data = $this->fetch_author_data($user_id);
            $this->cache->set($cache_key, $data, HOUR_IN_SECONDS);
        }
        
        return $data;
    }
}
```

### Block Architecture

All blocks extend a common base class following the Template Method pattern:

```php
abstract class Author_Block_Base implements Registerable {
    protected $block_name;
    protected $block_title;
    protected $service;
    
    public function __construct(Author_Profile_Service $service) {
        $this->service = $service;
    }
    
    public function register() {
        register_block_type($this->block_name, array(
            'editor_script' => 'author-profile-blocks-editor',
            'editor_style' => 'author-profile-blocks-editor',
            'style' => 'author-profile-blocks-frontend',
            'render_callback' => array($this, 'render'),
            'attributes' => $this->get_attributes(),
        ));
        
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
    }
    
    abstract protected function get_attributes();
    abstract protected function render($attributes, $content);
    abstract protected function enqueue_editor_assets();
}
```

### Dependency Injection

The plugin uses constructor injection for testability and loose coupling:

```php
class Plugin {
    private $service;
    private $admin;
    private $blocks = array();
    
    public function __construct(
        Author_Profile_Service $service = null,
        Admin $admin = null
    ) {
        $this->service = $service ?: new Author_Profile_Service();
        $this->admin = $admin ?: new Admin();
        
        $this->init_blocks();
    }
    
    private function init_blocks() {
        $this->blocks[] = new Author_Profile_Block($this->service);
        $this->blocks[] = new Author_Grid_Block($this->service);
        $this->blocks[] = new Author_Carousel_Block($this->service);
        $this->blocks[] = new Author_List_Block($this->service);
    }
}
```

## Frontend Architecture

### JavaScript Module Structure

The frontend uses ES6 modules with webpack bundling:

```javascript
// src/js/index.js
import AuthorPicker from './components/AuthorPicker';
import useAuthors from './hooks/useAuthors';
import api from './services/api';

export { AuthorPicker, useAuthors, api };
```

### React Components

Blocks use functional components with hooks:

```javascript
function AuthorProfileBlock({ attributes, setAttributes }) {
    const { authors, loading } = useAuthors({
        include: attributes.authorIds
    });
    
    if (loading) {
        return <AuthorBlockPlaceholder />;
    }
    
    return (
        <div className="wp-block-author-profile-blocks-author-profile">
            {authors.map(author => (
                <AuthorCard key={author.id} author={author} />
            ))}
        </div>
    );
}
```

### SCSS Architecture

Styles follow a modular, component-based approach:

```scss
// src/scss/common/_variables.scss
$primary-color: #3498db;
$border-radius: 4px;
$spacing-unit: 1rem;

// src/scss/blocks/author-profile.scss
@import '../common/variables';

.wp-block-author-profile-blocks-author-profile {
    .author-card {
        background: white;
        border-radius: $border-radius;
        padding: $spacing-unit;
        
        &__image {
            border-radius: 50%;
            width: 80px;
            height: 80px;
        }
        
        &__name {
            color: $primary-color;
            font-weight: 600;
        }
    }
}
```

## Template System

### PHP Templates

The plugin uses PHP templates for server-side rendering:

```php
// templates/blocks/author-profile/card.php
<div class="author-profile-card">
    <?php if ($show_image && !empty($author['image'])): ?>
        <img src="<?php echo esc_url($author['image']); ?>" 
             alt="<?php echo esc_attr($author['title']); ?>"
             class="author-image">
    <?php endif; ?>
    
    <h3 class="author-name"><?php echo esc_html($author['title']); ?></h3>
    
    <?php if ($show_position && !empty($author['position'])): ?>
        <p class="author-position"><?php echo esc_html($author['position']); ?></p>
    <?php endif; ?>
</div>
```

### Template Loading

Templates are loaded using WordPress conventions:

```php
public function render($attributes, $content) {
    $author = $this->service->get_author_data($attributes['authorId']);
    
    ob_start();
    author_profile_blocks_get_template('blocks/author-profile/card.php', array(
        'author' => $author,
        'attributes' => $attributes
    ));
    return ob_get_clean();
}
```

## Caching Strategy

### Multi-Level Caching

The plugin implements multiple caching layers:

1. **Object Cache**: WordPress object cache for author data
2. **Transient Cache**: Database transients for expensive queries
3. **Browser Cache**: HTTP caching headers for assets

```php
class Cache_Manager {
    public function get($key, $group = '') {
        $value = wp_cache_get($key, $group);
        if ($value === false) {
            $value = get_transient($key);
            if ($value !== false) {
                wp_cache_set($key, $value, $group);
            }
        }
        return $value;
    }
    
    public function set($key, $value, $expiration = 0, $group = '') {
        wp_cache_set($key, $value, $group, $expiration);
        set_transient($key, $value, $expiration);
    }
}
```

## Security Implementation

### Input Sanitization

All user inputs are properly sanitized:

```php
public function save_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }
    
    if (isset($_POST['author_position'])) {
        update_user_meta(
            $user_id, 
            'author_position', 
            sanitize_text_field($_POST['author_position'])
        );
    }
    
    if (isset($_POST['author_description'])) {
        update_user_meta(
            $user_id, 
            'author_description', 
            wp_kses_post($_POST['author_description'])
        );
    }
}
```

### Output Escaping

All outputs are properly escaped:

```php
public function render_social_links($author) {
    if (empty($author['social'])) {
        return '';
    }
    
    $output = '<div class="social-links">';
    foreach ($author['social'] as $platform => $url) {
        $output .= sprintf(
            '<a href="%s" target="_blank" rel="noopener noreferrer" class="social-link social-link--%s">%s</a>',
            esc_url($url),
            esc_attr($platform),
            esc_html(ucfirst($platform))
        );
    }
    $output .= '</div>';
    
    return $output;
}
```

### Nonce Verification

All form submissions include nonce verification:

```php
public function save_profile_fields($user_id) {
    if (!isset($_POST['author_profile_blocks_nonce']) || 
        !wp_verify_nonce($_POST['author_profile_blocks_nonce'], 'save_profile_fields')) {
        return;
    }
    
    // Process form data...
}
```

## Testing Architecture

### PHPUnit for Unit Tests

```php
class Author_Profile_Service_Test extends WP_UnitTestCase {
    public function test_get_author_data_returns_expected_structure() {
        $service = new Author_Profile_Service();
        $user_id = $this->factory->user->create();
        
        $data = $service->get_author_data($user_id);
        
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('email', $data);
    }
}
```

### Playwright for E2E Tests

```typescript
test('author profile block displays correctly', async ({ page }) => {
    await page.goto('/wp-admin/post-new.php');
    
    // Insert block
    await page.click('.block-editor-inserter__toggle');
    await page.fill('.block-editor-inserter__search input', 'Author Profile');
    await page.click('button:has-text("Author Profile")');
    
    // Configure block
    await page.click('.author-picker');
    await page.click('.author-option:first-child');
    
    // Publish and view
    await page.click('.editor-post-publish-button');
    await page.goto(await page.locator('.post-publish-panel__postpublish-header a').getAttribute('href'));
    
    // Verify display
    await expect(page.locator('.wp-block-author-profile-blocks-author-profile')).toBeVisible();
});
```

## Performance Optimizations

### Asset Optimization

- **Code Splitting**: Separate editor and frontend bundles
- **Minification**: UglifyJS for JavaScript, CSSNano for styles
- **Lazy Loading**: Images loaded only when needed
- **Font Loading**: Optimized web font loading

### Database Optimization

- **Efficient Queries**: Single query for multiple authors
- **Meta Query Optimization**: Proper indexing for meta fields
- **Caching**: Multi-layer caching strategy

### Runtime Optimization

- **Deferred Loading**: Non-critical assets loaded after page load
- **DOM Optimization**: Minimal DOM manipulation
- **Memory Management**: Proper cleanup of event listeners

## Extensibility Points

### Filter Hooks

The plugin provides extensive filter hooks for customization:

```php
// Modify author data
add_filter('author_profile_blocks_author_data', 'custom_author_data_modifier', 10, 2);

// Modify block rendering
add_filter('author_profile_blocks_rendered_block', 'custom_block_renderer', 10, 3);

// Modify query arguments
add_filter('author_profile_blocks_author_query_args', 'custom_query_modifier', 10);
```

### Action Hooks

Action hooks for extending functionality:

```php
// After block registration
add_action('author_profile_blocks_block_registered', 'custom_block_setup', 10, 2);

// Before/after rendering
add_action('author_profile_blocks_before_render_block', 'pre_render_logic', 10, 2);
add_action('author_profile_blocks_after_render_block', 'post_render_logic', 10, 3);
```

### Template Overrides

Themes can override plugin templates:

```php
// Copy templates to theme/author-profile-blocks/
// Modify as needed
```

## Internationalization

### Text Domain Setup

```php
// Load text domain
add_action('plugins_loaded', function() {
    load_plugin_textdomain(
        'author-profile-blocks',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
});
```

### Translation Functions

All user-facing strings use translation functions:

```php
__('Author Profile', 'author-profile-blocks')
__('Select authors to display', 'author-profile-blocks')
```

### JavaScript Translation

JavaScript strings are also translatable:

```javascript
wp.i18n.__('No authors found', 'author-profile-blocks')
```

## Error Handling

### Graceful Degradation

The plugin handles errors gracefully:

```php
public function render($attributes, $content) {
    try {
        $author = $this->service->get_author_data($attributes['authorId']);
        if (!$author) {
            return $this->render_error('Author not found');
        }
        return $this->render_author($author, $attributes);
    } catch (Exception $e) {
        error_log('Author Profile Blocks error: ' . $e->getMessage());
        return $this->render_error('Unable to load author data');
    }
}
```

### Logging

Comprehensive logging for debugging:

```php
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Author Profile Blocks: ' . $message);
}
```

## Future Considerations

### Scalability

- **Microservices Architecture**: Potential split into separate services
- **CDN Integration**: Support for asset delivery networks
- **Database Sharding**: For high-traffic sites

### Modern Web Standards

- **Web Components**: Potential migration to web components
- **CSS Grid/Flexbox**: Enhanced layout systems
- **Progressive Enhancement**: Better no-JS fallbacks

### API Evolution

- **GraphQL Support**: Alternative to REST API
- **Webhook Integration**: Real-time data synchronization
- **OAuth Integration**: Third-party service connections

This architecture provides a solid foundation for maintainable, extensible, and performant WordPress plugin development.