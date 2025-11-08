---
layout: default
title: Advanced Examples
nav_order: 11
permalink: /advanced-examples/
---

# Advanced Examples & Integrations

{: .no_toc }

Advanced usage examples, integrations, and customization techniques for the Author Profile Blocks plugin.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Custom Author Data Sources

### Integrating with ACF (Advanced Custom Fields)

Add custom fields from ACF to author profiles:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('get_field')) {
        // Add ACF image field
        $custom_image = get_field('profile_image', 'user_' . $user->ID);
        if ($custom_image) {
            $author_data['image'] = $custom_image['sizes']['medium'];
        }

        // Add ACF text fields
        $author_data['department'] = get_field('department', 'user_' . $user->ID);
        $author_data['office_location'] = get_field('office_location', 'user_' . $user->ID);

        // Add ACF repeater field for additional social links
        $additional_social = get_field('additional_social_links', 'user_' . $user->ID);
        if ($additional_social) {
            foreach ($additional_social as $social) {
                $author_data['social'][$social['platform']] = $social['url'];
            }
        }
    }

    return $author_data;
}, 10, 2);
```

### BuddyPress Integration

Integrate with BuddyPress extended profiles:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('bp_get_profile_field_data')) {
        // Add BuddyPress profile fields
        $author_data['phone'] = bp_get_profile_field_data(array(
            'field' => 'Phone',
            'user_id' => $user->ID
        ));

        $author_data['location'] = bp_get_profile_field_data(array(
            'field' => 'Location',
            'user_id' => $user->ID
        ));

        // Add BuddyPress avatar if available
        if (function_exists('bp_core_fetch_avatar')) {
            $author_data['image'] = bp_core_fetch_avatar(array(
                'item_id' => $user->ID,
                'type' => 'full',
                'html' => false
            ));
        }
    }

    return $author_data;
}, 10, 2);
```

### WooCommerce Integration

Show customer order counts and total spent:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (class_exists('WooCommerce')) {
        // Get customer data
        $customer = new WC_Customer($user->ID);

        $author_data['orders_count'] = $customer->get_order_count();
        $author_data['total_spent'] = wc_price($customer->get_total_spent());

        // Add customer since date
        $author_data['customer_since'] = $customer->get_date_created()
            ? $customer->get_date_created()->date('F Y')
            : '';
    }

    return $author_data;
}, 10, 2);
```

## Custom Block Templates

### Creating a Custom Layout

Create a completely custom block layout:

```php
add_filter('author_profile_blocks_rendered_author_profile', function($content, $block) {
    // Only apply to specific layout
    if ($block['layout'] !== 'custom') {
        return $content;
    }

    $author_id = $block['authorId'];
    $author = AuthorProfileBlocks\Plugin::get_instance()
        ->get_service()
        ->get_author_data($author_id);

    // Custom HTML structure
    ob_start();
    ?>
    <div class="custom-author-layout">
        <div class="author-header">
            <div class="author-avatar">
                <img src="<?php echo esc_url($author['image']); ?>" alt="">
            </div>
            <div class="author-meta">
                <h2><?php echo esc_html($author['title']); ?></h2>
                <p class="position"><?php echo esc_html($author['position']); ?></p>
            </div>
        </div>
        <div class="author-content">
            <div class="author-description">
                <?php echo wp_kses_post($author['description']); ?>
            </div>
            <div class="author-contact">
                <a href="mailto:<?php echo esc_attr($author['email']); ?>">
                    <?php echo esc_html($author['email']); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}, 10, 2);
```

### Adding Custom Fields to Templates

Extend templates with custom data:

```php
// Add custom data to template variables
add_filter('author_profile_blocks_template_vars', function($vars, $author, $attributes) {
    $vars['custom_greeting'] = get_user_meta($author['id'], 'custom_greeting', true);
    $vars['recent_posts'] = get_posts(array(
        'author' => $author['id'],
        'posts_per_page' => 3,
        'post_status' => 'publish'
    ));

    return $vars;
}, 10, 3);
```

## Dynamic Author Filtering

### Role-Based Filtering with Custom Logic

```php
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    // Only show authors who have published posts in the last 6 months
    $six_months_ago = strtotime('-6 months');

    foreach ($authors as $key => $author) {
        $recent_posts = get_posts(array(
            'author' => $author['id'],
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'date_query' => array(
                array('after' => date('Y-m-d', $six_months_ago))
            )
        ));

        if (empty($recent_posts)) {
            unset($authors[$key]);
        }
    }

    return array_values($authors);
}, 10, 2);
```

### Location-Based Filtering

```php
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    $current_location = get_query_var('location'); // From URL parameter

    if (!$current_location) {
        return $authors;
    }

    foreach ($authors as $key => $author) {
        $author_location = get_user_meta($author['id'], 'location', true);

        if (stripos($author_location, $current_location) === false) {
            unset($authors[$key]);
        }
    }

    return array_values($authors);
}, 10, 2);
```

## Advanced Styling Techniques

### Theme Integration

Create theme-specific styling:

```php
add_action('wp_enqueue_scripts', function() {
    $theme = wp_get_theme();

    if ($theme->name === 'Twenty Twenty-One') {
        wp_enqueue_style(
            'apb-twenty-twenty-one',
            plugin_dir_url(__FILE__) . 'css/twenty-twenty-one.css',
            array(),
            '1.0.0'
        );
    }
});
```

### Dynamic CSS Generation

Generate CSS based on block settings:

```php
add_action('wp_head', function() {
    global $post;

    if (!$post || !has_blocks($post->post_content)) {
        return;
    }

    $blocks = parse_blocks($post->post_content);
    $css = '';

    foreach ($blocks as $block) {
        if ($block['blockName'] === 'author-profile-blocks/author-profile') {
            $id = $block['attrs']['blockId'] ?? '';
            $bg_color = $block['attrs']['backgroundColor'] ?? '';

            if ($id && $bg_color) {
                $css .= ".wp-block-author-profile-blocks-author-profile[data-block-id=\"{$id}\"] { background-color: {$bg_color}; }";
            }
        }
    }

    if ($css) {
        echo "<style>{$css}</style>";
    }
});
```

## REST API Extensions

### Custom Author Endpoint

Add custom REST API endpoints:

```php
add_action('rest_api_init', function() {
    register_rest_route('author-profile-blocks/v1', '/authors/(?P<id>\d+)/stats', array(
        'methods' => 'GET',
        'callback' => 'get_author_stats',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            )
        )
    ));
});

function get_author_stats($request) {
    $author_id = $request->get_param('id');

    // Get author stats
    $post_count = count_user_posts($author_id);
    $comment_count = get_comments(array(
        'user_id' => $author_id,
        'count' => true
    ));

    return array(
        'posts' => $post_count,
        'comments' => $comment_count,
        'member_since' => get_userdata($author_id)->user_registered
    );
}
```

## Performance Optimizations

### Advanced Caching Strategies

Implement advanced caching:

```php
class Advanced_Cache_Manager {
    private $redis;

    public function __construct() {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }

    public function get_author_data($user_id) {
        $cache_key = "apb_author_{$user_id}";

        // Try Redis first
        $data = $this->redis->get($cache_key);
        if ($data) {
            return json_decode($data, true);
        }

        // Fallback to WordPress cache
        $data = wp_cache_get($cache_key, 'author_profile_blocks');
        if ($data === false) {
            $data = $this->fetch_author_data($user_id);

            // Store in both caches
            $this->redis->setex($cache_key, 3600, json_encode($data));
            wp_cache_set($cache_key, $data, 'author_profile_blocks', 3600);
        }

        return $data;
    }
}
```

### Lazy Loading with Intersection Observer

Implement advanced lazy loading:

```javascript
class LazyAuthorLoader {
	constructor(selector) {
		this.observer = new IntersectionObserver(this.loadAuthor.bind(this), {
			rootMargin: "50px",
		});

		document.querySelectorAll(selector).forEach((el) => {
			this.observer.observe(el);
		});
	}

	async loadAuthor(entries) {
		for (let entry of entries) {
			if (entry.isIntersecting) {
				const authorId = entry.target.dataset.authorId;
				const data = await this.fetchAuthorData(authorId);

				entry.target.innerHTML = this.renderAuthor(data);
				this.observer.unobserve(entry.target);
			}
		}
	}

	async fetchAuthorData(authorId) {
		const response = await fetch(
			`/wp-json/author-profile-blocks/v1/authors/${authorId}`,
		);
		return response.json();
	}

	renderAuthor(data) {
		return `
            <div class="author-card">
                <img src="${data.image}" alt="${data.title}">
                <h3>${data.title}</h3>
                <p>${data.position}</p>
            </div>
        `;
	}
}

// Initialize lazy loading
new LazyAuthorLoader(".lazy-author");
```

## Multi-Site Compatibility

### Network-Activated Plugin

Handle multi-site installations:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (is_multisite()) {
        // Add site-specific data
        $author_data['site_name'] = get_bloginfo('name');
        $author_data['site_url'] = get_bloginfo('url');

        // Check if user is member of current site
        $author_data['is_member'] = is_user_member_of_blog($user->ID, get_current_blog_id());
    }

    return $author_data;
}, 10, 2);
```

## Accessibility Enhancements

### Advanced ARIA Support

Add comprehensive ARIA attributes:

```php
add_filter('author_profile_blocks_rendered_block', function($content, $block, $block_name) {
    // Add ARIA labels and descriptions
    $content = preg_replace(
        '/<div class="author-card"/',
        '<div class="author-card" role="article" aria-labelledby="author-name-$1"',
        $content
    );

    // Add live regions for dynamic content
    if (strpos($block_name, 'carousel') !== false) {
        $content .= '<div aria-live="polite" aria-atomic="true" class="sr-only carousel-status"></div>';
    }

    return $content;
}, 10, 3);
```

### Keyboard Navigation

Enhance keyboard navigation for carousels:

```javascript
class AccessibleCarousel {
	constructor(element) {
		this.element = element;
		this.slides = element.querySelectorAll(".author-carousel-slide");
		this.currentIndex = 0;

		this.initKeyboardNavigation();
		this.updateAriaAttributes();
	}

	initKeyboardNavigation() {
		this.element.addEventListener("keydown", (e) => {
			switch (e.key) {
				case "ArrowLeft":
					e.preventDefault();
					this.previous();
					break;
				case "ArrowRight":
					e.preventDefault();
					this.next();
					break;
				case "Home":
					e.preventDefault();
					this.goToSlide(0);
					break;
				case "End":
					e.preventDefault();
					this.goToSlide(this.slides.length - 1);
					break;
			}
		});
	}

	updateAriaAttributes() {
		this.slides.forEach((slide, index) => {
			slide.setAttribute("aria-hidden", index !== this.currentIndex);
			slide.setAttribute(
				"tabindex",
				index === this.currentIndex ? "0" : "-1",
			);
		});
	}
}
```

## Integration with Popular Plugins

### Yoast SEO Integration

Add author schema markup:

```php
add_filter('wpseo_schema_graph_pieces', function($pieces, $context) {
    if (is_singular() && has_block('author-profile-blocks/author-profile')) {
        $blocks = parse_blocks(get_post()->post_content);

        foreach ($blocks as $block) {
            if ($block['blockName'] === 'author-profile-blocks/author-profile') {
                $author_id = $block['attrs']['authorId'];
                $author = get_userdata($author_id);

                $pieces[] = new WPSEO_Schema_Author($context, $author);
                break;
            }
        }
    }

    return $pieces;
}, 10, 2);
```

### Contact Form 7 Integration

Add author contact forms:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    // Add contact form shortcode
    $author_data['contact_form'] = do_shortcode(
        '[contact-form-7 id="123" author="' . $user->ID . '"]'
    );

    return $author_data;
}, 10, 2);
```

### WPML Integration

Handle multilingual author data:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    if (function_exists('icl_translate')) {
        // Translate custom fields
        $author_data['position'] = icl_translate(
            'author-profile-blocks',
            'position_' . $user->ID,
            $author_data['position']
        );

        $author_data['description'] = icl_translate(
            'author-profile-blocks',
            'description_' . $user->ID,
            $author_data['description']
        );
    }

    return $author_data;
}, 10, 2);
```

## Custom Admin Interfaces

### Enhanced Author Management

Create custom admin pages for author management:

```php
add_action('admin_menu', function() {
    add_submenu_page(
        'users.php',
        'Author Profile Management',
        'Author Profiles',
        'manage_options',
        'author-profile-management',
        'render_author_management_page'
    );
});

function render_author_management_page() {
    $authors = get_users(array('role__in' => array('author', 'editor', 'administrator')));

    ?>
    <div class="wrap">
        <h1>Author Profile Management</h1>

        <div class="author-stats">
            <div class="stat-box">
                <h3>Total Authors</h3>
                <span class="stat-number"><?php echo count($authors); ?></span>
            </div>
            <div class="stat-box">
                <h3>Active This Month</h3>
                <span class="stat-number"><?php echo count_active_authors_this_month($authors); ?></span>
            </div>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Position</th>
                    <th>Social Links</th>
                    <th>Last Post</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($authors as $author): ?>
                    <tr>
                        <td>
                            <?php echo get_avatar($author->ID, 32); ?>
                            <?php echo esc_html($author->display_name); ?>
                        </td>
                        <td><?php echo esc_html(get_user_meta($author->ID, 'author_position', true)); ?></td>
                        <td><?php echo count(array_filter(get_user_meta($author->ID, 'author_social', true))); ?> links</td>
                        <td><?php echo get_last_post_date($author->ID); ?></td>
                        <td>
                            <a href="<?php echo get_edit_user_link($author->ID); ?>#author-profile-information">Edit Profile</a> |
                            <a href="<?php echo get_author_posts_url($author->ID); ?>" target="_blank">View Posts</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function count_active_authors_this_month($authors) {
    $count = 0;
    $month_ago = strtotime('-1 month');

    foreach ($authors as $author) {
        $recent_posts = get_posts(array(
            'author' => $author->ID,
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'date_query' => array(
                array('after' => date('Y-m-d', $month_ago))
            )
        ));

        if (!empty($recent_posts)) {
            $count++;
        }
    }

    return $count;
}

function get_last_post_date($author_id) {
    $last_post = get_posts(array(
        'author' => $author_id,
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    if (!empty($last_post)) {
        return get_the_date('', $last_post[0]->ID);
    }

    return 'No posts';
}
```

### Bulk Author Profile Editor

Create a bulk editor for managing multiple author profiles:

```php
add_action('admin_menu', function() {
    add_submenu_page(
        'users.php',
        'Bulk Author Editor',
        'Bulk Editor',
        'manage_options',
        'bulk-author-editor',
        'render_bulk_author_editor'
    );
});

function render_bulk_author_editor() {
    if (isset($_POST['bulk_update_authors'])) {
        handle_bulk_update();
    }

    $authors = get_users(array('role__in' => array('author', 'editor', 'administrator')));

    ?>
    <div class="wrap">
        <h1>Bulk Author Profile Editor</h1>

        <form method="post">
            <?php wp_nonce_field('bulk_author_update', 'bulk_author_nonce'); ?>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Author</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($authors as $author): ?>
                        <tr>
                            <td><input type="checkbox" name="authors[]" value="<?php echo $author->ID; ?>"></td>
                            <td><?php echo esc_html($author->display_name); ?></td>
                            <td>
                                <input type="text" name="position[<?php echo $author->ID; ?>]"
                                       value="<?php echo esc_attr(get_user_meta($author->ID, 'author_position', true)); ?>">
                            </td>
                            <td>
                                <input type="text" name="department[<?php echo $author->ID; ?>]"
                                       value="<?php echo esc_attr(get_user_meta($author->ID, 'department', true)); ?>">
                            </td>
                            <td>
                                <input type="tel" name="phone[<?php echo $author->ID; ?>]"
                                       value="<?php echo esc_attr(get_user_meta($author->ID, 'phone', true)); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>
                <input type="submit" name="bulk_update_authors" class="button button-primary" value="Update Selected Authors">
            </p>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#select-all').on('change', function() {
            $('input[name="authors[]"]').prop('checked', $(this).prop('checked'));
        });
    });
    </script>
    <?php
}

function handle_bulk_update() {
    if (!wp_verify_nonce($_POST['bulk_author_nonce'], 'bulk_author_update')) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    $authors = isset($_POST['authors']) ? array_map('intval', $_POST['authors']) : array();

    foreach ($authors as $author_id) {
        if (isset($_POST['position'][$author_id])) {
            update_user_meta($author_id, 'author_position', sanitize_text_field($_POST['position'][$author_id]));
        }
        if (isset($_POST['department'][$author_id])) {
            update_user_meta($author_id, 'department', sanitize_text_field($_POST['department'][$author_id]));
        }
        if (isset($_POST['phone'][$author_id])) {
            update_user_meta($author_id, 'phone', sanitize_text_field($_POST['phone'][$author_id]));
        }
    }

    echo '<div class="notice notice-success"><p>Author profiles updated successfully!</p></div>';
}
```

### Author Analytics Dashboard

Create an analytics dashboard for author performance:

```php
add_action('admin_menu', function() {
    add_submenu_page(
        'users.php',
        'Author Analytics',
        'Analytics',
        'manage_options',
        'author-analytics',
        'render_author_analytics'
    );
});

function render_author_analytics() {
    $authors = get_users(array('role__in' => array('author', 'editor', 'administrator')));
    $analytics = array();

    foreach ($authors as $author) {
        $analytics[$author->ID] = array(
            'name' => $author->display_name,
            'total_posts' => count_user_posts($author->ID),
            'comments_received' => get_author_comment_count($author->ID),
            'last_post_date' => get_author_last_post_date($author->ID),
            'avg_post_length' => get_author_avg_post_length($author->ID),
            'popular_categories' => get_author_popular_categories($author->ID)
        );
    }

    ?>
    <div class="wrap">
        <h1>Author Analytics Dashboard</h1>

        <div class="analytics-grid">
            <?php foreach ($analytics as $author_id => $data): ?>
                <div class="analytics-card">
                    <h3><?php echo esc_html($data['name']); ?></h3>
                    <div class="metrics">
                        <div class="metric">
                            <span class="metric-label">Total Posts</span>
                            <span class="metric-value"><?php echo $data['total_posts']; ?></span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Comments</span>
                            <span class="metric-value"><?php echo $data['comments_received']; ?></span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Last Post</span>
                            <span class="metric-value"><?php echo $data['last_post_date']; ?></span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Avg. Length</span>
                            <span class="metric-value"><?php echo $data['avg_post_length']; ?> words</span>
                        </div>
                    </div>
                    <div class="categories">
                        <strong>Popular Categories:</strong>
                        <?php echo implode(', ', $data['popular_categories']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .analytics-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .metrics {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin: 15px 0;
    }
    .metric {
        text-align: center;
    }
    .metric-label {
        display: block;
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    .metric-value {
        display: block;
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }
    </style>
    <?php
}

function get_author_comment_count($author_id) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) FROM {$wpdb->comments} c
        INNER JOIN {$wpdb->posts} p ON c.comment_post_ID = p.ID
        WHERE p.post_author = %d AND c.comment_approved = 1
    ", $author_id));
}

function get_author_last_post_date($author_id) {
    $last_post = get_posts(array(
        'author' => $author_id,
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    return !empty($last_post) ? get_the_date('', $last_post[0]->ID) : 'Never';
}

function get_author_avg_post_length($author_id) {
    $posts = get_posts(array(
        'author' => $author_id,
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));

    if (empty($posts)) return 0;

    $total_words = 0;
    foreach ($posts as $post) {
        $total_words += str_word_count(strip_tags($post->post_content));
    }

    return round($total_words / count($posts));
}

function get_author_popular_categories($author_id) {
    global $wpdb;

    $categories = $wpdb->get_results($wpdb->prepare("
        SELECT t.name, COUNT(*) as count
        FROM {$wpdb->terms} t
        INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
        INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
        INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
        WHERE p.post_author = %d AND tt.taxonomy = 'category' AND p.post_status = 'publish'
        GROUP BY t.term_id
        ORDER BY count DESC
        LIMIT 3
    ", $author_id));

    return wp_list_pluck($categories, 'name');
}
```

These advanced examples demonstrate the flexibility and extensibility of the Author Profile Blocks plugin, allowing developers to create sophisticated author display solutions tailored to specific needs.
