---
layout: default
title: Performance Guide
nav_order: 12
permalink: /performance-guide/
---

# Performance Optimization Guide

{: .no_toc }

Optimize the Author Profile Blocks plugin for maximum performance and speed.
{: .fs-6 .fw-300 }

## Table of contents

{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Caching Strategies

### Built-in Caching

The plugin includes multiple caching layers:

1. **Object Cache**: WordPress object cache for author data
2. **Transient Cache**: Database transients for expensive queries
3. **Browser Cache**: HTTP headers for static assets

### Cache Configuration

```php
// Adjust cache expiration (default: 1 hour)
add_filter('author_profile_blocks_cache_expiration', function() {
    return HOUR_IN_SECONDS * 2; // 2 hours
});

// Disable caching for development
add_filter('author_profile_blocks_enable_caching', '__return_false');
```

### Advanced Caching with Redis

For high-traffic sites, implement Redis caching:

```php
class Redis_Cache_Manager {
    private $redis;

    public function __construct() {
        if (class_exists('Redis')) {
            $this->redis = new Redis();
            $this->redis->connect('127.0.0.1', 6379);
        }
    }

    public function get_author_data($user_id) {
        if (!$this->redis) {
            return false;
        }

        $cache_key = "apb_author_{$user_id}";
        $data = $this->redis->get($cache_key);

        return $data ? json_decode($data, true) : false;
    }

    public function set_author_data($user_id, $data) {
        if (!$this->redis) {
            return;
        }

        $cache_key = "apb_author_{$user_id}";
        $this->redis->setex($cache_key, 3600, json_encode($data));
    }
}
```

## Database Optimization

### Query Optimization

Optimize author queries for better performance:

```php
// Use specific fields to reduce memory usage
add_filter('author_profile_blocks_author_query_args', function($query_args) {
    $query_args['fields'] = array('ID', 'user_login', 'display_name', 'user_email');
    return $query_args;
});

// Limit author selection
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    // Only return first 20 authors for performance
    return array_slice($authors, 0, 20);
}, 10, 2);
```

### Meta Query Optimization

Optimize user meta queries:

```php
// Add database indexes for frequently queried meta keys
register_activation_hook(__FILE__, function() {
    global $wpdb;

    $table = $wpdb->usermeta;
    $index_name = 'author_position_idx';

    // Check if index exists
    $indexes = $wpdb->get_results("SHOW INDEX FROM {$table} WHERE Key_name = '{$index_name}'");

    if (empty($indexes)) {
        $wpdb->query("ALTER TABLE {$table} ADD INDEX {$index_name} (meta_key(50), meta_value(100))");
    }
});
```

## Asset Optimization

### Code Splitting

The plugin automatically splits assets:

- **Editor Assets**: Loaded only in the block editor
- **Frontend Assets**: Loaded only on frontend pages with blocks
- **Vendor Libraries**: Separated for better caching

### Minification and Compression

Assets are automatically minified in production builds:

```javascript
// webpack.config.js optimization
optimization: {
    minimize: true,
    splitChunks: {
        chunks: 'all',
        cacheGroups: {
            vendor: {
                test: /[\\/]node_modules[\\/]/,
                name: 'vendors',
                chunks: 'all',
            },
        },
    },
}
```

### Lazy Loading

Images are lazy loaded by default:

```php
// Customize lazy loading
add_filter('author_profile_blocks_image_attributes', function($attributes, $author) {
    $attributes['loading'] = 'lazy';
    $attributes['decoding'] = 'async';
    return $attributes;
}, 10, 2);
```

## Block Editor Performance

### Reduce Editor Overhead

Optimize block editor performance:

```php
// Limit authors shown in editor
add_filter('author_profile_blocks_author_query_args', function($query_args) {
    if (is_admin()) {
        $query_args['number'] = 50; // Limit in editor
    }
    return $query_args;
});
```

### Debounced Updates

The plugin uses debounced updates to prevent excessive re-renders:

```javascript
// Debounce author selection changes
const debouncedUpdate = _.debounce((authors) => {
	setAttributes({ selectedAuthors: authors });
}, 300);

onAuthorChange(debouncedUpdate);
```

## Frontend Performance

### Selective Loading

Assets are loaded only when needed:

```php
// Only load carousel scripts when carousel blocks are present
add_action('wp_enqueue_scripts', function() {
    if (has_block('author-profile-blocks/author-carousel')) {
        wp_enqueue_script('slick-carousel');
        wp_enqueue_style('slick-carousel-css');
    }
});
```

### Intersection Observer

Use intersection observer for performance:

```javascript
class LazyBlockLoader {
	constructor() {
		this.observer = new IntersectionObserver(this.loadBlock.bind(this), {
			rootMargin: "50px",
		});

		document.querySelectorAll(".lazy-author-block").forEach((block) => {
			this.observer.observe(block);
		});
	}

	loadBlock(entries) {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				// Load block content
				this.loadAuthorData(entry.target);
				this.observer.unobserve(entry.target);
			}
		});
	}
}
```

## Server-Side Optimization

### Object Caching

Implement persistent object caching:

```php
// Use Redis or Memcached for object caching
add_filter('author_profile_blocks_cache_backend', function() {
    if (class_exists('Redis')) {
        return new Redis_Cache_Backend();
    }
    return new WP_Object_Cache();
});
```

### Database Connection Optimization

Optimize database connections:

```php
// Use persistent connections for better performance
add_filter('author_profile_blocks_db_connection', function($connection) {
    if (extension_loaded('mysqli')) {
        return new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME,
            null,
            null,
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
        );
    }
    return $connection;
});
```

## CDN Integration

### Static Asset Delivery

Serve assets via CDN:

```php
add_filter('author_profile_blocks_asset_url', function($url) {
    $cdn_url = 'https://cdn.example.com';
    return str_replace(content_url(), $cdn_url, $url);
});
```

### Image Optimization

Optimize author images:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    // Use optimized image sizes
    $author_data['image'] = get_avatar_url($user->ID, array(
        'size' => 150,
        'default' => 'mystery'
    ));

    return $author_data;
}, 10, 2);
```

## Monitoring and Profiling

### Performance Monitoring

Monitor plugin performance:

```php
class Performance_Monitor {
    private $timings = array();

    public function start_timer($key) {
        $this->timings[$key] = microtime(true);
    }

    public function end_timer($key) {
        if (isset($this->timings[$key])) {
            $elapsed = microtime(true) - $this->timings[$key];
            error_log("APB Performance: {$key} took {$elapsed}s");

            unset($this->timings[$key]);
            return $elapsed;
        }
        return false;
    }
}

// Usage
$monitor = new Performance_Monitor();
$monitor->start_timer('author_query');
// ... code to monitor ...
$monitor->end_timer('author_query');
```

### Query Monitoring

Monitor database queries:

```php
add_action('author_profile_blocks_before_author_query', function($query_args) {
    global $wpdb;
    $wpdb->queries = array(); // Reset query log
});

add_action('author_profile_blocks_after_author_query', function($authors, $query_args) {
    global $wpdb;

    $query_count = count($wpdb->queries);
    $total_time = array_sum(array_column($wpdb->queries, 1));

    error_log("APB Query Stats: {$query_count} queries, {$total_time}s total time");
});
```

## Scalability Considerations

### Large Author Bases

Handle sites with many authors:

```php
// Implement pagination for large author lists
add_filter('author_profile_blocks_authors', function($authors, $query_args) {
    $page = isset($query_args['page']) ? $query_args['page'] : 1;
    $per_page = isset($query_args['per_page']) ? $query_args['per_page'] : 20;

    $offset = ($page - 1) * $per_page;
    return array_slice($authors, $offset, $per_page);
}, 10, 2);
```

### Memory Management

Optimize memory usage:

```php
// Process authors in batches
class Batch_Author_Processor {
    public function process_authors($all_authors, $batch_size = 10) {
        $results = array();

        foreach (array_chunk($all_authors, $batch_size) as $batch) {
            $batch_results = $this->process_batch($batch);
            $results = array_merge($results, $batch_results);

            // Allow other processes to run
            if (function_exists('wp_cache_flush')) {
                wp_cache_flush();
            }
        }

        return $results;
    }
}
```

## Content Delivery Optimization

### HTTP/2 Server Push

Implement HTTP/2 server push for critical assets:

```php
add_action('send_headers', function() {
    if (has_block('author-profile-blocks/author-profile')) {
        header('Link: </wp-content/plugins/author-profile-blocks/assets/css/frontend.css>; rel=preload; as=style', false);
        header('Link: </wp-content/plugins/author-profile-blocks/assets/js/frontend.js>; rel=preload; as=script', false);
    }
});
```

### Critical CSS

Inline critical CSS for above-the-fold content:

```php
add_action('wp_head', function() {
    if (has_block('author-profile-blocks/author-profile')) {
        echo '<style>' . file_get_contents(plugin_dir_path(__FILE__) . 'assets/css/critical.css') . '</style>';
    }
});
```

## Testing Performance

### Load Testing

Test performance under load:

```bash
# Use Apache Bench for load testing
ab -n 1000 -c 10 https://example.com/authors/

# Use Siege for sustained load testing
siege -c 50 -t 60s https://example.com/authors/
```

### Performance Benchmarks

Establish performance baselines:

```php
class Performance_Benchmark {
    public function benchmark_author_query($user_count = 100) {
        $start_time = microtime(true);
        $start_memory = memory_get_usage();

        // Run benchmark
        $authors = get_users(array('number' => $user_count));

        foreach ($authors as $author) {
            $data = author_profile_blocks_get_author_data($author->ID);
        }

        $end_time = microtime(true);
        $end_memory = memory_get_usage();

        return array(
            'time' => $end_time - $start_time,
            'memory' => $end_memory - $start_memory,
            'users_per_second' => $user_count / ($end_time - $start_time)
        );
    }
}
```

## Specific Optimization Techniques

### Database Query Optimization

#### Efficient Author Queries

Optimize author queries for better performance:

```php
// Instead of multiple queries, use a single optimized query
add_filter('author_profile_blocks_author_query_args', function($query_args) {
    // Only fetch needed fields
    $query_args['fields'] = array('ID', 'user_login', 'display_name', 'user_email', 'user_registered');

    // Use meta_query for better performance
    if (isset($query_args['department'])) {
        $query_args['meta_query'] = array(
            array(
                'key' => 'department',
                'value' => $query_args['department'],
                'compare' => '='
            )
        );
        unset($query_args['department']);
    }

    return $query_args;
});
```

#### Meta Field Indexing

Add database indexes for frequently queried meta fields:

```php
register_activation_hook(__FILE__, function() {
    global $wpdb;

    $table = $wpdb->usermeta;

    // Add indexes for commonly queried fields
    $indexes = array(
        'author_position_idx' => 'meta_key = "author_position"',
        'department_idx' => 'meta_key = "department"',
        'author_social_idx' => 'meta_key = "author_social"'
    );

    foreach ($indexes as $index_name => $condition) {
        $exists = $wpdb->get_var("
            SELECT COUNT(1) FROM INFORMATION_SCHEMA.STATISTICS
            WHERE table_schema = DATABASE()
            AND table_name = '{$table}'
            AND index_name = '{$index_name}'
        ");

        if (!$exists) {
            $wpdb->query("ALTER TABLE {$table} ADD INDEX {$index_name} ({$condition})");
        }
    }
});
```

### Image Optimization Strategies

#### Responsive Image Loading

Implement responsive images for different screen sizes:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    // Generate multiple image sizes
    $image_id = get_user_meta($user->ID, 'profile_image_id', true);

    if ($image_id) {
        $author_data['image_srcset'] = wp_get_attachment_image_srcset($image_id, 'author-profile');
        $author_data['image_sizes'] = '(max-width: 768px) 150px, (max-width: 1024px) 200px, 300px';
    }

    return $author_data;
}, 10, 2);
```

#### WebP Image Support

Add WebP support for better compression:

```php
add_filter('author_profile_blocks_author_data', function($author_data, $user) {
    $image_url = $author_data['image'];

    // Check if WebP version exists
    $webp_url = str_replace(array('.jpg', '.jpeg', '.png'), '.webp', $image_url);

    // Use WebP if supported by browser
    $author_data['image'] = $webp_url;
    $author_data['image_fallback'] = $image_url;

    return $author_data;
}, 10, 2);
```

### Advanced Caching Patterns

#### Multi-Layer Caching Strategy

Implement sophisticated caching:

```php
class Advanced_Author_Cache {
    private $redis;
    private $memory_cache = array();

    public function __construct() {
        if (class_exists('Redis')) {
            $this->redis = new Redis();
            $this->redis->connect('127.0.0.1', 6379);
        }
    }

    public function get_author_data($user_id, $ttl = 3600) {
        $cache_key = "apb_author_{$user_id}";

        // Check memory cache first
        if (isset($this->memory_cache[$cache_key])) {
            return $this->memory_cache[$cache_key];
        }

        // Check Redis cache
        if ($this->redis) {
            $data = $this->redis->get($cache_key);
            if ($data !== false) {
                $data = json_decode($data, true);
                $this->memory_cache[$cache_key] = $data;
                return $data;
            }
        }

        // Check WordPress object cache
        $data = wp_cache_get($cache_key, 'author_profile_blocks');
        if ($data !== false) {
            if ($this->redis) {
                $this->redis->setex($cache_key, $ttl, json_encode($data));
            }
            $this->memory_cache[$cache_key] = $data;
            return $data;
        }

        // Generate data
        $data = $this->fetch_author_data($user_id);

        // Store in all cache layers
        wp_cache_set($cache_key, $data, 'author_profile_blocks', $ttl);
        if ($this->redis) {
            $this->redis->setex($cache_key, $ttl, json_encode($data));
        }
        $this->memory_cache[$cache_key] = $data;

        return $data;
    }

    public function invalidate_author_cache($user_id) {
        $cache_key = "apb_author_{$user_id}";

        unset($this->memory_cache[$cache_key]);
        wp_cache_delete($cache_key, 'author_profile_blocks');
        if ($this->redis) {
            $this->redis->del($cache_key);
        }
    }
}
```

#### Selective Cache Invalidation

Implement smart cache invalidation:

```php
add_action('profile_update', function($user_id) {
    // Clear author cache when profile is updated
    wp_cache_delete("apb_author_{$user_id}", 'author_profile_blocks');

    // Clear related caches
    wp_cache_delete('apb_all_authors', 'author_profile_blocks');
    wp_cache_delete('apb_authors_by_role', 'author_profile_blocks');
});

add_action('save_post', function($post_id) {
    // Clear author cache when author publishes a post
    $author_id = get_post_field('post_author', $post_id);
    wp_cache_delete("apb_author_{$author_id}", 'author_profile_blocks');
});
```

### JavaScript Performance Optimization

#### Debounced Author Search

Optimize author search in the block editor:

```javascript
class DebouncedAuthorSearch {
	constructor(searchInput, resultsContainer) {
		this.searchInput = searchInput;
		this.resultsContainer = resultsContainer;
		this.debounceTimer = null;
		this.cache = new Map();

		this.init();
	}

	init() {
		this.searchInput.addEventListener("input", (e) => {
			clearTimeout(this.debounceTimer);
			const query = e.target.value.trim();

			if (query.length < 2) {
				this.clearResults();
				return;
			}

			this.debounceTimer = setTimeout(() => {
				this.searchAuthors(query);
			}, 300);
		});
	}

	async searchAuthors(query) {
		// Check cache first
		if (this.cache.has(query)) {
			this.displayResults(this.cache.get(query));
			return;
		}

		try {
			const response = await fetch(
				`/wp-json/author-profile-blocks/v1/authors?search=${encodeURIComponent(query)}`,
			);
			const results = await response.json();

			// Cache results
			this.cache.set(query, results);
			this.displayResults(results);
		} catch (error) {
			console.error("Author search failed:", error);
		}
	}

	displayResults(results) {
		// Render results efficiently
		const html = results
			.map(
				(author) => `
            <div class="author-result" data-author-id="${author.id}">
                <img src="${author.image}" alt="" width="32" height="32">
                <span>${author.title}</span>
            </div>
        `,
			)
			.join("");

		this.resultsContainer.innerHTML = html;
	}

	clearResults() {
		this.resultsContainer.innerHTML = "";
	}
}
```

#### Virtual Scrolling for Large Author Lists

Implement virtual scrolling for performance:

```javascript
class VirtualAuthorList {
	constructor(container, authors) {
		this.container = container;
		this.authors = authors;
		this.itemHeight = 50;
		this.visibleItems = 10;
		this.scrollTop = 0;

		this.init();
	}

	init() {
		this.container.style.height = `${this.visibleItems * this.itemHeight}px`;
		this.container.style.overflow = "auto";

		this.renderVisibleItems();
		this.container.addEventListener("scroll", () => {
			this.scrollTop = this.container.scrollTop;
			this.renderVisibleItems();
		});
	}

	renderVisibleItems() {
		const startIndex = Math.floor(this.scrollTop / this.itemHeight);
		const endIndex = Math.min(
			startIndex + this.visibleItems,
			this.authors.length,
		);

		const visibleAuthors = this.authors.slice(startIndex, endIndex);
		const offsetY = startIndex * this.itemHeight;

		const html = visibleAuthors
			.map(
				(author, index) => `
            <div class="author-item" style="height: ${this.itemHeight}px; transform: translateY(${offsetY + index * this.itemHeight}px)">
                <img src="${author.image}" alt="" width="32" height="32">
                <span>${author.title}</span>
            </div>
        `,
			)
			.join("");

		this.container.innerHTML = html;
	}
}
```

### Server-Side Optimization

#### HTTP/2 Push for Critical Resources

Implement HTTP/2 server push:

```php
add_action('send_headers', function() {
    if (has_block('author-profile-blocks/author-profile')) {
        // Push critical CSS
        header('Link: </wp-content/plugins/author-profile-blocks/assets/css/critical.css>; rel=preload; as=style', false);

        // Push critical JavaScript
        header('Link: </wp-content/plugins/author-profile-blocks/assets/js/critical.js>; rel=preload; as=script', false);

        // Push author images for above-the-fold content
        if (is_singular()) {
            $blocks = parse_blocks(get_post()->post_content);
            foreach ($blocks as $block) {
                if ($block['blockName'] === 'author-profile-blocks/author-profile') {
                    $author_id = $block['attrs']['authorId'];
                    $author = author_profile_blocks_get_author_data($author_id);
                    if ($author && $author['image']) {
                        header("Link: <{$author['image']}>; rel=preload; as=image", false);
                    }
                    break;
                }
            }
        }
    }
});
```

#### Database Connection Pooling

Optimize database connections for high traffic:

```php
class Database_Connection_Pool {
    private $connections = array();
    private $max_connections = 5;

    public function get_connection() {
        // Return existing connection if available
        if (!empty($this->connections)) {
            return array_pop($this->connections);
        }

        // Create new connection
        return $this->create_connection();
    }

    public function release_connection($connection) {
        if (count($this->connections) < $this->max_connections) {
            $this->connections[] = $connection;
        } else {
            $connection->close();
        }
    }

    private function create_connection() {
        global $wpdb;

        // Create persistent connection
        $connection = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME,
            null,
            null,
            MYSQLI_CLIENT_COMPRESS
        );

        return $connection;
    }
}
```

## Best Practices Summary

### Quick Wins

1. **Enable Caching**: Use object caching (Redis/Memcached)
2. **Limit Authors**: Set reasonable limits on displayed authors
3. **Optimize Images**: Use appropriate image sizes and WebP format
4. **Enable Compression**: Use GZIP compression for assets
5. **Database Indexing**: Add indexes for frequently queried meta fields

### Advanced Optimizations

1. **CDN Integration**: Serve assets from CDN
2. **Multi-Layer Caching**: Implement Redis + memory + object cache
3. **Lazy Loading**: Use intersection observer for images
4. **Code Splitting**: Separate editor and frontend bundles
5. **HTTP/2 Push**: Push critical resources
6. **Virtual Scrolling**: For large author lists in admin

### Monitoring

1. **Performance Monitoring**: Track response times and resource usage
2. **Query Analysis**: Monitor database query performance
3. **Cache Hit Rates**: Track cache effectiveness
4. **User Experience**: Monitor Core Web Vitals
5. **Memory Usage**: Monitor PHP memory consumption

Following these optimization strategies will ensure the Author Profile Blocks plugin performs efficiently even on high-traffic sites with large author databases.
