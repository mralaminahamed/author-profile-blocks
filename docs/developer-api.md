---
layout: default
title: Developer API
parent: Blocks
nav_order: 10
permalink: /developer-api/
---

# Developer API Reference

{: .no_toc }

Comprehensive reference for developers working with the Author Profile Blocks plugin.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Plugin Architecture

Author Profile Blocks follows a modern WordPress plugin architecture with PSR-4 autoloading, dependency injection, and clean separation of concerns.

### Core Classes

#### Main Plugin Class

**File**: `class-author-profile-blocks.php`  
**Namespace**: `AuthorProfileBlocks`

The main plugin class handles initialization, activation, deactivation, and serves as the central entry point.

```php
class AuthorProfileBlocks {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('init', array($this, 'init'));
    }
}
```

#### Service Classes

**Author_Profile_Service** (`includes/Services/Author_Profile_Service.php`)  
Handles author data retrieval, caching, and processing.

```php
class Author_Profile_Service {
    public function get_author_data($user_id) {
        // Retrieves and processes author data
    }

    public function get_authors($args = array()) {
        // Queries and returns multiple authors
    }
}
```

**User_Meta_Provider** (`includes/Core/User_Meta_Provider.php`)  
Manages user meta fields for enhanced author profiles.

```php
class User_Meta_Provider {
    public function get_meta_fields() {
        // Returns available meta fields
    }

    public function save_meta_fields($user_id, $data) {
        // Saves user meta data
    }
}
```

#### Block Classes

All block classes extend `Author_Block_Base` and follow the same structure:

```php
abstract class Author_Block_Base {
    protected $block_name;
    protected $block_title;

    public function register_block() {
        register_block_type($this->block_name, array(
            'editor_script' => 'author-profile-blocks-editor',
            'editor_style' => 'author-profile-blocks-editor',
            'style' => 'author-profile-blocks-frontend',
            'render_callback' => array($this, 'render_block'),
        ));
    }

    abstract public function render_block($attributes, $content);
}
```

## Hooks and Filters

### Block Registration Filters

#### `author_profile_blocks_block_args`

Modify block registration arguments before a block is registered.

```php
add_filter('author_profile_blocks_block_args', function($args, $block_name) {
    if ($block_name === 'author-profile') {
        // Modify author profile block args
        $args['supports']['align'] = true;
    }
    return $args;
}, 10, 2);
```

**Parameters:**

- `$args` (array): Block registration arguments
- `$block_name` (string): Block name (author-profile, author-grid, etc.)

**Returns:** Modified block arguments array

#### `author_profile_blocks_block_registration`

Filter all block registrations at once.

```php
add_filter('author_profile_blocks_block_registration', function($blocks) {
    // Add custom block or modify existing ones
    $blocks['custom-block'] = array(
        'name' => 'author-profile-blocks/custom-block',
        'args' => array(...)
    );
    return $blocks;
});
```

### Author Data Filters

#### `author_profile_blocks_author_data`

Modify individual author data before it's returned.

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    // Add custom field
    $author_data['custom_field'] = get_user_meta($user->ID, 'custom_field', true);

    // Modify existing data
    $author_data['title'] = 'Dr. ' . $author_data['title'];

    return $author_data;
}, 10, 2);
```

**Parameters:**

- `$author_data` (array): Processed author data
- `$user` (WP_User): WordPress user object

**Returns:** Modified author data array

#### `author_profile_blocks_authors`

Filter the complete authors array after querying.

```php
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    // Sort authors alphabetically
    usort($authors, function($a, $b) {
        return strcmp($a['title'], $b['title']);
    });

    return $authors;
}, 10, 2);
```

**Parameters:**

- `$authors` (array): Array of author data
- `$query_args` (array): Original query arguments

**Returns:** Modified authors array

#### `author_profile_blocks_author_query_args`

Modify the WP_User_Query arguments before querying authors.

```php
add_filter('author_profile_blocks_author_query_args', function($query_args) {
    // Only include authors with published posts
    $query_args['has_published_posts'] = true;

    // Limit to specific roles
    $query_args['role__in'] = array('author', 'editor');

    return $query_args;
});
```

**Parameters:**

- `$query_args` (array): WP_User_Query arguments

**Returns:** Modified query arguments

### Rendering Filters

#### `author_profile_blocks_rendered_block`

Filter the final rendered HTML for any block.

```php
add_filter('author_profile_blocks_rendered_block', function($content, $block, $block_name) {
    // Add wrapper div to all blocks
    return '<div class="custom-wrapper">' . $content . '</div>';
}, 10, 3);
```

**Parameters:**

- `$content` (string): Rendered block HTML
- `$block` (array): Block attributes and data
- `$block_name` (string): Block name

**Returns:** Modified HTML content

#### Block-Specific Rendering Filters

```php
// Author Profile block
add_filter('author_profile_blocks_rendered_author_profile', function($content, $block) {
    // Custom modifications for author profile blocks
    return $content;
}, 10, 2);

// Author Grid block
add_filter('author_profile_blocks_rendered_author_grid', function($content, $block) {
    // Custom modifications for author grid blocks
    return $content;
}, 10, 2);

// Author Carousel block
add_filter('author_profile_blocks_rendered_author_carousel', function($content, $block) {
    // Custom modifications for author carousel blocks
    return $content;
}, 10, 2);

// Author List block
add_filter('author_profile_blocks_rendered_author_list', function($content, $block) {
    // Custom modifications for author list blocks
    return $content;
}, 10, 2);
```

### User Profile Filters

#### `author_profile_blocks_profile_fields`

Add custom fields to the author profile section.

```php
add_filter('author_profile_blocks_profile_fields', function($fields) {
    $fields['custom_field'] = array(
        'label' => 'Custom Field',
        'type' => 'text',
        'description' => 'Description of custom field'
    );
    return $fields;
});
```

#### `author_profile_blocks_save_profile_fields`

Handle saving of custom profile fields.

```php
add_action('author_profile_blocks_save_profile_fields', function($user_id) {
    if (isset($_POST['custom_field'])) {
        update_user_meta($user_id, 'custom_field', sanitize_text_field($_POST['custom_field']));
    }
});
```

### Caching Filters

#### `author_profile_blocks_enable_caching`

Control whether caching is enabled.

```php
add_filter('author_profile_blocks_enable_caching', '__return_false'); // Disable caching
```

#### `author_profile_blocks_cache_expiration`

Set cache expiration time (default: HOUR_IN_SECONDS).

```php
add_filter('author_profile_blocks_cache_expiration', function() {
    return DAY_IN_SECONDS; // Cache for 24 hours
});
```

### Development Filters

#### `author_profile_blocks_development_mode`

Enable development mode for debugging.

```php
add_filter('author_profile_blocks_development_mode', '__return_true');
```

## Actions

### Block Actions

#### `author_profile_blocks_block_registered`

Fired after a block is registered.

```php
add_action('author_profile_blocks_block_registered', function($block_name, $args) {
    // Custom logic after block registration
}, 10, 2);
```

#### `author_profile_blocks_before_render_block`

Fired before a block is rendered.

```php
add_action('author_profile_blocks_before_render_block', function($block, $block_name) {
    // Custom logic before block rendering
}, 10, 2);
```

#### `author_profile_blocks_after_render_block`

Fired after a block is rendered.

```php
add_action('author_profile_blocks_after_render_block', function($content, $block, $block_name) {
    // Custom logic after block rendering
}, 10, 3);
```

### User Profile Actions

#### `author_profile_blocks_profile_fields_saved`

Fired after profile fields are saved.

```php
add_action('author_profile_blocks_profile_fields_saved', function($user_id, $data) {
    // Custom logic after profile save
}, 10, 2);
```

## JavaScript API

### Block Editor Components

The plugin provides several reusable React components for the block editor:

#### AuthorPicker

Component for selecting authors in the block editor.

```javascript
import { AuthorPicker } from "@author-profile-blocks/components";

function MyBlockEdit(props) {
	const { attributes, setAttributes } = props;

	return (
		<AuthorPicker
			selectedAuthors={attributes.authors}
			onChange={(authors) => setAttributes({ authors })}
			maxAuthors={10}
			placeholder="Search authors..."
			help="Select the authors you want to display"
		/>
	);
}
```

#### AuthorBlockPlaceholder

Placeholder component shown when no authors are selected.

```javascript
import { AuthorBlockPlaceholder } from "@author-profile-blocks/components";

function MyBlockEdit(props) {
	const { attributes } = props;

	if (!attributes.authors || attributes.authors.length === 0) {
		return (
			<AuthorBlockPlaceholder
				icon="admin-users"
				label="Select Authors"
				instructions="Choose authors to display in this block"
				actions={[
					{
						label: "Browse Authors",
						onClick: () =>
							setAttributes({ showAuthorPicker: true }),
					},
				]}
			/>
		);
	}

	return <div>Block content</div>;
}
```

#### AuthorCard

Reusable component for displaying author information in the editor.

```javascript
import { AuthorCard } from "@author-profile-blocks/components";

function AuthorPreview({ author, showImage = true, showPosition = true }) {
	return (
		<AuthorCard
			author={author}
			showImage={showImage}
			showPosition={showPosition}
			showDescription={false}
			className="editor-author-preview"
		/>
	);
}
```

### Hooks

#### useAuthors

Custom hook for fetching and managing author data.

```javascript
import { useAuthors } from "@author-profile-blocks/hooks";

function AuthorGrid({ attributes }) {
	const { authors, loading, error, refetch } = useAuthors({
		include: attributes.selectedAuthors,
		role: attributes.filterRole,
		number: attributes.maxAuthors,
		orderby: attributes.orderBy,
		order: attributes.order,
	});

	if (loading) return <div>Loading authors...</div>;
	if (error) return <div>Error loading authors: {error.message}</div>;

	return (
		<div className="author-grid">
			{authors.map((author) => (
				<AuthorCard key={author.id} author={author} />
			))}
		</div>
	);
}
```

#### useAuthorMeta

Hook for managing author metadata in the editor.

```javascript
import { useAuthorMeta } from "@author-profile-blocks/hooks";

function AuthorMetaEditor({ userId }) {
	const { meta, updateMeta, loading } = useAuthorMeta(userId);

	const handlePositionChange = (position) => {
		updateMeta("author_position", position);
	};

	return (
		<div>
			<input
				type="text"
				value={meta.author_position || ""}
				onChange={(e) => handlePositionChange(e.target.value)}
				placeholder="Author position"
				disabled={loading}
			/>
		</div>
	);
}
```

#### useBlockSettings

Hook for managing block-specific settings.

```javascript
import { useBlockSettings } from "@author-profile-blocks/hooks";

function AuthorBlockControls({ attributes, setAttributes }) {
	const { settings, updateSetting } = useBlockSettings(
		attributes,
		setAttributes,
	);

	return (
		<PanelBody title="Author Display Settings">
			<ToggleControl
				label="Show author images"
				checked={settings.showImage}
				onChange={(value) => updateSetting("showImage", value)}
			/>
			<ToggleControl
				label="Show author positions"
				checked={settings.showPosition}
				onChange={(value) => updateSetting("showPosition", value)}
			/>
		</PanelBody>
	);
}
```

## REST API Endpoints

### Authors Endpoint

**GET** `/wp-json/author-profile-blocks/v1/authors`

Retrieve authors with optional filtering.

**Parameters:**

- `role` (string): Filter by user role
- `include` (array): Specific user IDs to include
- `exclude` (array): User IDs to exclude
- `number` (int): Maximum number of authors to return
- `orderby` (string): Sort field (ID, name, registered, etc.)
- `order` (string): Sort order (ASC, DESC)

**Example Request:**

```bash
GET /wp-json/author-profile-blocks/v1/authors?role=author&number=5
```

**Example Response:**

```json
{
	"authors": [
		{
			"id": 1,
			"title": "John Doe",
			"email": "john@example.com",
			"description": "Senior Developer",
			"position": "Lead Developer",
			"social": {
				"twitter": "https://twitter.com/johndoe",
				"linkedin": "https://linkedin.com/in/johndoe"
			},
			"image": "https://example.com/avatar.jpg",
			"registered_date": "January 15, 2020",
			"member_since_label": "Member since",
			"role": "administrator"
		}
	],
	"total": 1,
	"pages": 1
}
```

### Author Data Endpoint

**GET** `/wp-json/author-profile-blocks/v1/authors/{id}`

Retrieve data for a specific author.

**Parameters:**

- `id` (int): User ID (required)

**Example Request:**

```bash
GET /wp-json/author-profile-blocks/v1/authors/1
```

## Template Functions

### `author_profile_blocks_get_author_data()`

Get processed author data for a user.

```php
$author_data = author_profile_blocks_get_author_data($user_id);
```

**Parameters:**

- `$user_id` (int): WordPress user ID

**Returns:** Array of author data or false on failure

### `author_profile_blocks_get_authors()`

Get multiple authors with filtering options.

```php
$authors = author_profile_blocks_get_authors(array(
    'role' => 'author',
    'number' => 10,
    'orderby' => 'name'
));
```

**Parameters:**

- `$args` (array): Query arguments

**Returns:** Array of author data

### `author_profile_blocks_render_block()`

Render a block programmatically.

```php
$content = author_profile_blocks_render_block('author-profile', array(
    'authorId' => 1,
    'showImage' => true,
    'showDescription' => true
));
```

**Parameters:**

- `$block_name` (string): Block name
- `$attributes` (array): Block attributes

**Returns:** Rendered HTML content

## Practical Development Examples

### Creating a Custom Author Field

Add a custom field to author profiles:

```php
// 1. Add the field to user profiles
add_action('author_profile_blocks_profile_fields', function($fields) {
    $fields['linkedin_profile'] = array(
        'label' => 'LinkedIn Profile',
        'type' => 'url',
        'description' => 'Enter your LinkedIn profile URL'
    );
    return $fields;
});

// 2. Save the field data
add_action('author_profile_blocks_save_profile_fields', function($user_id) {
    if (isset($_POST['linkedin_profile'])) {
        update_user_meta($user_id, 'linkedin_profile', esc_url_raw($_POST['linkedin_profile']));
    }
});

// 3. Include in author data
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    $author_data['social']['linkedin'] = get_user_meta($user->ID, 'linkedin_profile', true);
    return $author_data;
}, 10, 2);
```

### Custom Author Query with Meta Filtering

Query authors based on custom meta fields:

```php
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    // Only show authors from a specific department
    $department = isset($_GET['department']) ? sanitize_text_field($_GET['department']) : '';

    if ($department) {
        foreach ($authors as $key => $author) {
            $author_dept = get_user_meta($author['id'], 'department', true);
            if ($author_dept !== $department) {
                unset($authors[$key]);
            }
        }
        $authors = array_values($authors);
    }

    return $authors;
}, 10, 2);
```

### Programmatic Block Creation

Create blocks programmatically in templates:

```php
// Create an author grid for a specific department
$department_authors = get_users(array(
    'meta_key' => 'department',
    'meta_value' => 'engineering',
    'number' => 6
));

$author_ids = wp_list_pluck($department_authors, 'ID');

echo author_profile_blocks_render_block('author-grid', array(
    'selectedAuthors' => $author_ids,
    'columns' => 3,
    'showImage' => true,
    'showPosition' => true,
    'showDescription' => false,
    'layout' => 'card'
));
```

### Custom Block Template Override

Override block templates for custom layouts:

```php
add_filter('author_profile_blocks_rendered_author_profile', function($content, $block) {
    // Custom layout for specific use case
    if (isset($block['customLayout']) && $block['customLayout'] === 'bio-card') {
        $author_id = $block['authorId'];
        $author = author_profile_blocks_get_author_data($author_id);

        ob_start();
        ?>
        <div class="custom-bio-card">
            <div class="bio-header">
                <img src="<?php echo esc_url($author['image']); ?>" alt="" class="bio-avatar">
                <div class="bio-info">
                    <h3><?php echo esc_html($author['title']); ?></h3>
                    <p class="bio-position"><?php echo esc_html($author['position']); ?></p>
                </div>
            </div>
            <div class="bio-content">
                <p class="bio-description"><?php echo esc_html($author['description']); ?></p>
                <div class="bio-meta">
                    <span>Member since: <?php echo esc_html($author['registered_date']); ?></span>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    return $content;
}, 10, 2);
```

### AJAX Author Loading

Load authors dynamically with AJAX:

```php
// Enqueue script
wp_enqueue_script('my-author-loader', get_template_directory_uri() . '/js/author-loader.js', array('jquery'), '1.0', true);

// Localize script
wp_localize_script('my-author-loader', 'authorLoaderAjax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('load_authors_nonce')
));

// AJAX handler
add_action('wp_ajax_load_more_authors', 'load_more_authors_callback');
add_action('wp_ajax_nopriv_load_more_authors', 'load_more_authors_callback');

function load_more_authors_callback() {
    check_ajax_referer('load_authors_nonce', 'nonce');

    $offset = intval($_POST['offset']);
    $number = intval($_POST['number']);

    $authors = author_profile_blocks_get_authors(array(
        'number' => $number,
        'offset' => $offset
    ));

    wp_send_json_success($authors);
}
```

### Integration with Page Builders

Make blocks compatible with page builders:

```php
// Register blocks with page builders
add_action('init', function() {
    if (function_exists('vc_map')) {
        // Visual Composer integration
        vc_map(array(
            'name' => 'Author Profile',
            'base' => 'author_profile_vc',
            'category' => 'Author Blocks',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => 'Author',
                    'param_name' => 'author_id',
                    'value' => get_authors_dropdown()
                )
            )
        ));
    }
});

function get_authors_dropdown() {
    $users = get_users(array('role__in' => array('author', 'editor', 'administrator')));
    $dropdown = array('Select Author' => '');

    foreach ($users as $user) {
        $dropdown[$user->display_name] = $user->ID;
    }

    return $dropdown;
}
```

## Constants

### Core Constants

```php
define('AUTHOR_PROFILE_BLOCKS_VERSION', '1.1.0');
define('AUTHOR_PROFILE_BLOCKS_FILE', __FILE__);
define('AUTHOR_PROFILE_BLOCKS_PATH', plugin_dir_path(__FILE__));
define('AUTHOR_PROFILE_BLOCKS_URL', plugin_dir_url(__FILE__));
```

### Cache Constants

```php
define('AUTHOR_PROFILE_BLOCKS_CACHE_GROUP', 'author_profile_blocks');
define('AUTHOR_PROFILE_BLOCKS_CACHE_EXPIRATION', HOUR_IN_SECONDS);
```

## File Structure

```
author-profile-blocks/
├── author-profile-blocks.php          # Main plugin file
├── class-author-profile-blocks.php    # Main plugin class
├── index.php                          # Security file
├── uninstall.php                      # Uninstall routine
├── includes/                          # PHP classes
│   ├── Admin/                        # Admin functionality
│   ├── Blocks/                       # Block classes
│   ├── Core/                         # Core functionality
│   └── Services/                     # Service classes
├── src/                              # Frontend assets
│   ├── blocks/                       # Block implementations
│   ├── js/                           # JavaScript utilities
│   └── scss/                         # Stylesheets
├── templates/                        # Template files
├── languages/                        # Translation files
├── tests/                            # Test files
└── docs/                            # Documentation
```

## Development Guidelines

### Code Standards

- Follow WordPress Coding Standards
- Use PHP 7.4+ features
- Add comprehensive PHPDoc comments
- Use meaningful variable and function names
- Implement proper error handling

### Testing

- Write unit tests for new functionality
- Test blocks in various themes
- Verify responsive behavior
- Test with different user roles

### Security

- Sanitize all input data
- Escape all output data
- Use nonces for form submissions
- Validate user capabilities
- Follow WordPress security best practices

## Migration Guide

### Upgrading from Version 1.0.x to 1.1.x

1. **Database Changes**: No database changes required
2. **Template Changes**: Review custom templates for compatibility
3. **Hook Changes**: Check for deprecated hooks in your custom code
4. **CSS Changes**: Update custom CSS for new class names

### Breaking Changes

- None in version 1.1.x (backward compatible)

## Support and Resources

- **GitHub Repository**: [https://github.com/mralaminahamed/author-profile-blocks](https://github.com/mralaminahamed/author-profile-blocks)
- **WordPress.org Support**: [https://wordpress.org/support/plugin/author-profile-blocks/](https://wordpress.org/support/plugin/author-profile-blocks/)
- **Documentation**: [https://author-profile-blocks.github.io/](https://author-profile-blocks.github.io/)
- **Issue Tracker**: [https://github.com/mralaminahamed/author-profile-blocks/issues](https://github.com/mralaminahamed/author-profile-blocks/issues)
