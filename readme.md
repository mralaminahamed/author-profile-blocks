# Author Profile Blocks

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](http://www.gnu.org/licenses/gpl-2.0.txt)
[![Gutenberg Compatible](https://img.shields.io/badge/Gutenberg-Ready-00A0D2.svg)](https://wordpress.org/gutenberg/)
[![Production Ready](https://img.shields.io/badge/Status-Production%20Ready-brightgreen.svg)](#)

> **Modern Gutenberg blocks for showcasing author profiles and team members. Display WordPress users in beautiful, customizable layouts without creating duplicate content.**

## ✨ Features

### 🎯 **Block Collection**

- **Author Profile Block**: Single author display with comprehensive information
- **Author Grid Block**: Responsive grid layout for multiple authors
- **Author Carousel Block**: Interactive slider with smooth transitions
- **Author List Block**: Clean, organized list format with filtering

### 🎨 **Layout Options**

- **Card Layout**: Professional card-based presentation
- **Compact Layout**: Space-efficient horizontal arrangements
- **Centered Layout**: Symmetrical, balanced design
- **Detailed Layout**: Expanded information display

### 👤 **Rich Author Information**

- **Profile Images**: Avatar support with customizable sizing
- **Personal Details**: Name, position, title, and bio
- **Contact Information**: Email addresses and social profiles
- **Registration Data**: "Member since" dates
- **Social Integration**: Facebook, Twitter, LinkedIn, Instagram, website links
- **Custom Content**: Additional user-defined information

### ⚙️ **Advanced Customization**

- **Visual Controls**: Background colors, borders, shadows, rounded corners
- **Typography**: Text alignment, spacing, and styling options
- **Interactive Effects**: Hover animations and transitions
- **Responsive Design**: Mobile-first approach with breakpoint controls
- **Accessibility**: WCAG compliant with proper focus management

### 🔧 **Technical Excellence**

- **Modern PHP**: PHP 7.4+ with type hints and WordPress standards
- **Gutenberg Native**: Block.json configuration with modern APIs
- **Performance Optimized**: Efficient caching and lazy loading
- **Translation Ready**: Complete internationalization support
- **Developer Friendly**: Comprehensive hooks and filters
- **Security First**: Nonce verification and data sanitization

### 📱 **User Experience**

- **Responsive Design**: Perfect display across all devices
- **Touch Friendly**: Mobile-optimized interactions
- **Keyboard Navigation**: Full accessibility compliance
- **Loading States**: Smooth animations and transitions
- **Error Handling**: Graceful fallbacks and user feedback

---

## 🚀 Quick Start

### Requirements

- WordPress 6.0+ (tested up to 6.8)
- PHP 7.4+ (tested up to PHP 8.3)
- Gutenberg Editor (built-in with WordPress 5.0+)

### Installation

1. **Upload & Activate**

    ```bash
    cd wp-content/plugins/
    # Upload plugin files to author-profile-blocks/
    # Activate via WordPress admin → Plugins
    ```

2. **User Profile Enhancement**
    - Navigate to **Users → All Users**
    - Edit any user profile
    - Add position/title, extended bio, and social media links

3. **Block Usage**
    - Create/edit any page or post
    - Click **+** to add a block
    - Search for "Author" blocks
    - Configure and customize as needed

---

## 📖 How It Works

1. **User Integration**: Leverages existing WordPress users instead of custom post types
2. **Profile Enhancement**: Adds custom fields for position, bio, and social links
3. **Block Rendering**: Gutenberg blocks display user data in various layouts
4. **Customization**: Extensive block settings for visual and functional control
5. **Performance**: Optimized queries with caching for fast loading

### Usage Examples

**Basic Author Profile:**

```php
<!-- wp:author-profile-blocks/author-profile {"authorId":1,"layout":"card"} /-->
```

**Team Grid Display:**

```php
<!-- wp:author-profile-blocks/author-grid {"layout":"card","columns":3,"showRole":true} /-->
```

**Social Media Integration:**

- Facebook, Twitter, LinkedIn profiles
- Instagram and personal website links
- Automatic icon generation and linking

---

## 📚 Documentation

### For Users & Administrators

- **[Getting Started](./docs/getting-started.md)** - Installation and basic usage
- **[Block Guide](./docs/blocks/)** - Detailed block documentation
- **[Customization](./docs/customization.md)** - Advanced styling and configuration
- **[FAQ](./docs/faq.md)** - Common questions and troubleshooting

### For Developers & Technical Teams

- **[Developer API](./docs/developer-api.md)** - Hooks, filters, and customization
- **[Block Architecture](./docs/plugin-architecture.md)** - Technical implementation details
- **[Performance Guide](./docs/performance-guide.md)** - Optimization and caching
- **[Contributing](./docs/contributing.md)** - Development guidelines

---

## 🛠️ Development

**Asset Compilation:**

```bash
yarn install          # Install dependencies
yarn start            # Development build with watch mode
yarn build            # Production build
yarn lint             # Code quality checks
```

**PHP Quality:**

```bash
composer install      # Install PHP dependencies
composer phpcs        # Run PHP CodeSniffer
composer phpstan      # Static analysis
composer test         # Run test suite
```

**Package Creation:**

```bash
yarn plugin-zip       # Create distribution ZIP file
```

## 🔧 Developer API

### Hook System

**Action Hooks:**

```php
// Block rendering
do_action('apbl_before_author_render', $author_id, $block_attributes);
do_action('apbl_after_author_render', $author_id, $block_attributes);

// User profile
do_action('apbl_user_profile_fields_saved', $user_id, $fields);
```

**Filter Hooks:**

```php
// Modify author data
$author_data = apply_filters('apbl_author_data', $author_data, $author_id);

// Modify block attributes
$attributes = apply_filters('apbl_block_attributes', $attributes, $block_type);

// Modify social links
$social_links = apply_filters('apbl_social_links', $social_links, $author_id);

// Modify available layouts
$layouts = apply_filters('apbl_available_layouts', $layouts, $block_type);
```

### Template System

```php
// Template loading pattern
apbl_get_template('author-card.php', $args);

// Override in your theme
yourtheme/author-profile-blocks/author-card.php
```

### Data Architecture

**User Meta Storage:**

```php
// Position/Title
update_user_meta($user_id, 'apbl_position', $position);

// Extended Description
update_user_meta($user_id, 'apbl_description', $description);

// Social Profiles
update_user_meta($user_id, 'apbl_social_links', $social_data);
```

**Block Configuration:**

```json
{
	"name": "author-profile-blocks/author-profile",
	"attributes": {
		"authorId": { "type": "number", "default": 0 },
		"layout": { "type": "string", "default": "card" },
		"showEmail": { "type": "boolean", "default": true }
	}
}
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes following WordPress coding standards
4. Test thoroughly (PHP, JavaScript, and manual testing)
5. Submit a pull request with detailed description

### Development Guidelines

- Follow WordPress Coding Standards (PHPCS)
- Write comprehensive tests for new features
- Update documentation for API changes
- Maintain backward compatibility
- Test across multiple WordPress versions

## 📝 Version

**Current Version:** v1.0.0 - Production Ready

See [CHANGELOG.md](./CHANGELOG.md) for complete version history and updates.

## 📞 Support

For support, feature requests, or bug reports:

- **WordPress.org**: [Plugin Support Forum](https://wordpress.org/support/plugin/author-profile-blocks/)
- **GitHub Issues**: [Report Bugs](https://github.com/mralaminahamed/author-profile-blocks/issues)
- **Documentation**: [User Guide](./docs/)

## 📜 License

GPL v2 or later - See [LICENSE](./LICENSE) for details.

## 👨‍💻 Credits

Developed by Al Amin Ahamed for the WordPress community.

**Contributors:** [mralaminahamed](https://profiles.wordpress.org/mralaminahamed/)
**Tags:** block, author, profile, gutenberg, team, user, biography, team-members
**Tested up to:** 6.8
**Stable tag:** 1.0.0
**Requires at least:** 6.0
**Requires PHP:** 7.4

---

## 📸 Screenshots

1. **Author Profile Block** - Single author display with comprehensive information
2. **Author Grid Block** - Responsive team member grid layout
3. **Author Carousel Block** - Interactive slider with smooth transitions
4. **Author List Block** - Clean, organized list format
5. **Block Customization** - Extensive styling and configuration options
6. **User Profile Enhancement** - Extended author information fields

---

## ❓ Frequently Asked Questions

### 🔍 **Does this plugin create duplicate content?**

**No duplicate content!** Unlike other plugins, Author Profile Blocks leverages your existing WordPress users instead of creating a separate custom post type. This prevents content duplication and uses the built-in user management system.

### 👤 **How do I add author information?**

The plugin enhances standard WordPress user profiles with additional fields:

- **Position/Title**: Professional role or job title
- **Extended Bio**: Rich text description beyond the standard bio
- **Social Media**: Facebook, Twitter, LinkedIn, Instagram, and website links
- **Member Since**: Customizable registration date display

### 🎨 **Can I customize the appearance?**

**Extensive customization options!** Each block includes comprehensive styling controls:

- **Layout Options**: Card, compact, centered, and detailed layouts
- **Visual Elements**: Background colors, borders, shadows, rounded corners
- **Content Control**: Show/hide specific information fields
- **Typography**: Text alignment, spacing, and sizing
- **Interactive Effects**: Hover animations and transitions

### 🔍 **Can I filter displayed authors?**

**Flexible filtering system:**

- **Specific Authors**: Select individual users to display
- **Role-Based**: Filter by WordPress user roles (Administrator, Editor, Author, etc.)
- **Quantity Limits**: Set maximum number of displayed authors
- **Dynamic Selection**: Real-time author selection in block editor

### 🌐 **How do social media profiles work?**

**Seamless social integration:**

- **Profile Fields**: Adds social media URL fields to user profiles
- **Automatic Icons**: Generates appropriate social media icons
- **Click-to-Visit**: Direct links to author social profiles
- **Supported Platforms**: Facebook, Twitter, LinkedIn, Instagram, personal websites

### 📱 **Is the plugin mobile-friendly?**

**Fully responsive design:**

- **Mobile-First**: Optimized for mobile devices first
- **Breakpoint Adaptation**: Automatic layout adjustments
- **Touch-Friendly**: Proper touch targets and interactions
- **Cross-Device**: Consistent experience across all screen sizes

### 🎨 **Theme Compatibility**

**Universal compatibility:**

- **Gutenberg Required**: Works with any Gutenberg-enabled theme
- **Self-Contained**: Block styling doesn't conflict with themes
- **CSS Isolation**: Uses unique class names and CSS custom properties
- **Fallback Support**: Graceful degradation for older themes

### 🌍 **Translation & Internationalization**

**Complete i18n support:**

- **Translation Ready**: All strings wrapped in translation functions
- **POT File**: Generated translation template included
- **WordPress Standards**: Follows WordPress i18n best practices
- **RTL Support**: Right-to-left language compatible

---

## 📋 Changelog

### v1.0.0 - Production Ready

- ✨ **Initial Release**: Complete Author Profile Blocks plugin
- 🎯 **Four Block Types**: Author Profile, Grid, Carousel, and List blocks
- 🎨 **Multiple Layouts**: Card, compact, centered, and detailed layouts
- 👤 **User Profile Enhancement**: Extended user fields and social media integration
- ⚙️ **Advanced Customization**: Comprehensive block styling options
- 📱 **Responsive Design**: Mobile-first approach with breakpoint controls
- ♿ **Accessibility**: WCAG compliant with proper focus management
- 🔧 **Developer API**: Complete hooks and filters system
- 📚 **Documentation**: Comprehensive user and developer guides
- 🧪 **Testing**: PHPUnit and Playwright test suites
- 🚀 **Performance**: Optimized caching and asset loading

---

## ⚡ Upgrade Notice

### v1.0.0

**Initial production release** of Author Profile Blocks. Install and start showcasing your team members and authors with beautiful, customizable Gutenberg blocks!

---

## 🆘 Support & Resources

### 📞 **Getting Help**

- **📖 Documentation**: [Complete User Guide](./docs/)
- **💬 Community Support**: [WordPress.org Forums](https://wordpress.org/support/plugin/author-profile-blocks/)
- **🐛 Bug Reports**: [GitHub Issues](https://github.com/mralaminahamed/author-profile-blocks/issues)
- **💡 Feature Requests**: [GitHub Discussions](https://github.com/mralaminahamed/author-profile-blocks/discussions)

### 📚 **Additional Resources**

- **🔗 Plugin Homepage**: [WordPress.org Plugin Page](https://wordpress.org/plugins/author-profile-blocks/)
- **📦 Repository**: [GitHub Repository](https://github.com/mralaminahamed/author-profile-blocks/)
- **📧 Developer Contact**: [Profile](https://profiles.wordpress.org/mralaminahamed/)

### 🤝 **Contributing**

We welcome contributions! Please see our [Contributing Guide](./docs/contributing.md) for development guidelines and coding standards.

---

## 📜 License & Credits

**License:** GPL v2 or later - See [LICENSE](./LICENSE) file for complete license text.

**Credits:**

- **Developer**: Al Amin Ahamed
- **WordPress.org Profile**: [@mralaminahamed](https://profiles.wordpress.org/mralaminahamed/)
- **Repository**: [GitHub](https://github.com/mralaminahamed/author-profile-blocks/)

---

_Made with ❤️ for the WordPress community. Empowering websites with beautiful author showcases since 2024._
