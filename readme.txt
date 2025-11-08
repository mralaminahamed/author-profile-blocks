=== Author Profile Blocks ===
Contributors:      mralaminahamed
Tags:              block, author, profile, gutenberg, team, user, biography, team-members, carousel, grid, list
Tested up to:      6.8
Stable tag:        1.1.0
Requires at least: 6.0
Requires PHP:      7.4
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Modern Gutenberg blocks for showcasing WordPress author profiles and team members. Display users in beautiful, customizable layouts without creating duplicate content.

== Description ==

**Author Profile Blocks** is a modern, lightweight WordPress plugin that provides powerful Gutenberg blocks for displaying author profiles and team members. Built with modern PHP 7.4+, React/TypeScript, and WordPress 6.0+ standards.

Unlike other plugins that create custom post types, Author Profile Blocks leverages your existing WordPress users - preventing content duplication and utilizing the built-in user management system. Perfect for showcasing team members, contributors, authors, or any WordPress users on your website.

### 🎯 Block Collection

**Author Profile Block**
Display a single author with comprehensive information including avatar, bio, social links, and customizable styling.

**Author Grid Block**
Arrange multiple authors in responsive grid layouts with advanced filtering and sorting options.

**Author Carousel Block**
Present authors in interactive sliders with smooth transitions, autoplay, and touch/swipe support.

**Author List Block**
Show authors in clean, organized list formats with filtering capabilities and compact layouts.

### 🎨 Layout Options

* **Card Layout**: Professional card-based presentation with shadows and rounded corners
* **Compact Layout**: Space-efficient horizontal arrangements
* **Centered Layout**: Symmetrical, balanced design
* **Detailed Layout**: Expanded information display for comprehensive profiles

### 👤 Rich Author Information

* **Profile Images**: Avatar support with customizable sizing and fallback options
* **Personal Details**: Name, position/title, and extended biographies
* **Contact Information**: Email addresses with privacy controls
* **Registration Data**: Customizable "Member since" dates
* **Social Integration**: Facebook, Twitter, LinkedIn, Instagram, and personal website links
* **Custom Content**: Additional user-defined information fields

### ⚙️ Advanced Customization

* **Visual Controls**: Background colors, borders, shadows, and rounded corners
* **Typography**: Text alignment, spacing, and responsive sizing
* **Interactive Effects**: Hover animations and smooth transitions
* **Responsive Design**: Mobile-first approach with breakpoint controls
* **Accessibility**: WCAG compliant with proper focus management and ARIA labels

### 🔧 Technical Excellence

* **Modern Architecture**: PSR-4 autoloading with clean, maintainable code
* **Performance Optimized**: Efficient caching and lazy loading for fast rendering
* **Developer Friendly**: Comprehensive hooks, filters, and template system
* **Translation Ready**: Complete internationalization with WordPress standards
* **Security First**: Nonce verification, input sanitization, and output escaping
* **TypeScript/React**: Modern frontend with type safety and component architecture

== Installation ==

### Automatic Installation (Recommended)

1. Go to **Plugins → Add New** in your WordPress admin
2. Search for "Author Profile Blocks"
3. Click **Install Now** and then **Activate**

### Manual Installation

1. Download the plugin ZIP file from WordPress.org
2. Upload to `/wp-content/plugins/author-profile-blocks/` directory
3. Activate through **Plugins → Installed Plugins**

### Setup & Configuration

1. **Activate the Plugin**: Go to **Plugins → Installed Plugins** and activate "Author Profile Blocks"

2. **Enhance User Profiles** (Optional but recommended):
   - Navigate to **Users → All Users**
   - Edit any user profile
   - Add position/title, extended bio, and social media links

3. **Add Blocks to Content**:
   - Create/edit any page or post
   - Click the **+** (Add Block) button
   - Search for "Author" blocks
   - Configure and customize as needed

### Requirements

* **WordPress**: 6.0 or higher
* **PHP**: 7.4 or higher
* **Gutenberg**: Block editor (included in WordPress 5.0+)
* **Modern Browser**: For block editor interface

== Frequently Asked Questions ==

= Does this plugin create duplicate content or custom post types? =

**No duplicate content!** Unlike other plugins, Author Profile Blocks leverages your existing WordPress users instead of creating a separate custom post type. This prevents content duplication and uses the built-in user management system, making it more efficient and SEO-friendly.

= How do I add author information to user profiles? =

The plugin enhances standard WordPress user profiles with additional fields:

1. Go to **Users → All Users** in your WordPress admin
2. Click **Edit** next to any user
3. Scroll down to find the new "Author Profile" section
4. Add position/title, extended bio, and social media links
5. Save the profile

= What customization options are available? =

**Extensive customization options!** Each block includes comprehensive styling controls:

**Layout Options:**
* Card, compact, centered, and detailed layouts
* Responsive grid columns (1-6 columns)
* Carousel settings (autoplay, speed, navigation)

**Visual Styling:**
* Background colors and gradients
* Border options (color, width, radius)
* Shadow effects and hover animations
* Typography controls (alignment, spacing, sizing)

**Content Control:**
* Show/hide specific information fields
* Custom field labels and formatting
* Social media icon styling

= Can I filter which authors are displayed? =

**Flexible filtering system:**

* **Specific Authors**: Select individual users to display
* **Role-Based**: Filter by WordPress user roles (Administrator, Editor, Author, etc.)
* **Quantity Limits**: Set maximum number of displayed authors
* **Dynamic Selection**: Real-time author selection in block editor
* **Exclude Users**: Hide specific users from display

= How do social media profiles work? =

**Seamless social integration:**

* **Profile Fields**: Adds URL fields for Facebook, Twitter, LinkedIn, Instagram, and personal websites
* **Automatic Icons**: Generates appropriate social media icons
* **Click-to-Visit**: Direct links to author social profiles
* **Supported Platforms**: Facebook, Twitter, LinkedIn, Instagram, personal websites
* **Custom Icons**: Option to upload custom social media icons

= Is the plugin mobile-friendly and responsive? =

**Fully responsive design:**

* **Mobile-First**: Optimized for mobile devices first
* **Breakpoint Adaptation**: Automatic layout adjustments for tablets and desktop
* **Touch-Friendly**: Proper touch targets and swipe gestures for carousels
* **Cross-Device**: Consistent experience across all screen sizes
* **Performance**: Optimized loading for mobile networks

= Does this work with my theme? =

**Universal compatibility:**

* **Gutenberg Required**: Works with any Gutenberg-enabled theme
* **Self-Contained**: Block styling doesn't conflict with theme styles
* **CSS Isolation**: Uses unique class names and CSS custom properties
* **Fallback Support**: Graceful degradation for older themes
* **Theme Overrides**: Template system for theme customization

= Is the plugin accessible and translation-ready? =

**Accessibility & Internationalization:**

* **WCAG Compliant**: Full accessibility support with proper ARIA labels
* **Keyboard Navigation**: Complete keyboard accessibility
* **Screen Reader**: Optimized for screen reading software
* **RTL Support**: Right-to-left language compatible
* **Translation Ready**: All strings wrapped in translation functions
* **POT File**: Translation template included

= Can I use this for team member pages? =

**Perfect for team pages!** The plugin is ideal for:

* Company "About Us" pages
* Team member directories
* Contributor showcases
* Staff directories
* Author biography sections
* Speaker profiles for events

= What about performance and SEO? =

**Performance optimized:**

* **Efficient Queries**: Optimized database queries with caching
* **Lazy Loading**: Images load as needed to improve page speed
* **Minimal Assets**: Lightweight JavaScript and CSS
* **SEO Friendly**: Uses semantic HTML and proper heading structure
* **Schema.org**: Optional structured data support

= Can developers extend the plugin? =

**Developer-friendly architecture:**

* **Hook System**: Comprehensive action and filter hooks
* **Template Overrides**: Custom template system
* **API Access**: Programmatic block creation
* **Custom Fields**: Extensible user profile fields
* **Modern Code**: PSR-4 autoloading, clean architecture

== Screenshots ==

1. **Author Profile Block** - Single author display with comprehensive information, social links, and customizable card layout
2. **Author Grid Block** - Responsive team member grid showcasing multiple authors with hover effects and consistent styling
3. **Author Carousel Block** - Interactive slider with smooth transitions, navigation arrows, and autoplay functionality
4. **Author List Block** - Clean, organized list format with filtering options and compact author information
5. **Block Customization Panel** - Extensive styling controls including colors, spacing, typography, and layout options
6. **Enhanced User Profile** - Extended author information fields including position, bio, and social media profiles

== Changelog ==

= 1.1.0 =
* **Enhanced Performance**: Improved caching and query optimization
* **TypeScript Migration**: Complete frontend rewrite with TypeScript
* **Modern Build System**: Updated webpack configuration and dependencies
* **Code Quality**: Enhanced linting, testing, and code standards
* **Documentation**: Comprehensive developer documentation and guides
* **Bug Fixes**: Various stability improvements and edge case handling

= 1.0.0 =
* **Initial Release**: Production-ready Author Profile Blocks plugin
* **Four Block Types**: Author Profile, Grid, Carousel, and List blocks
* **Multiple Layouts**: Card, compact, centered, and detailed layouts
* **User Profile Enhancement**: Extended fields for position, bio, and social links
* **Advanced Customization**: Comprehensive block styling and configuration options
* **Responsive Design**: Mobile-first approach with breakpoint controls
* **Accessibility**: WCAG compliant with proper focus management and ARIA labels
* **Developer API**: Complete hooks and filters system for extensibility
* **Translation Ready**: Full internationalization support
* **Performance**: Optimized queries with caching for fast loading
* **Security**: Nonce verification and data sanitization throughout

== Upgrade Notice ==

= 1.1.0 =
**Important Update**: Enhanced performance, TypeScript migration, and improved code quality. Recommended for all users.

= 1.0.0 =
**Initial Production Release**: Complete Author Profile Blocks plugin with all core features. Install and start showcasing your team members!

== Support ==

### Getting Help

**Community Support:**
* [WordPress.org Support Forum](https://wordpress.org/support/plugin/author-profile-blocks/) - Community-driven support
* [GitHub Issues](https://github.com/mralaminahamed/author-profile-blocks/issues) - Bug reports and feature requests
* [GitHub Discussions](https://github.com/mralaminahamed/author-profile-blocks/discussions) - General discussions and Q&A

**Documentation:**
* [Complete User Guide](https://github.com/mralaminahamed/author-profile-blocks/tree/main/docs) - Comprehensive documentation
* [Developer API Reference](https://github.com/mralaminahamed/author-profile-blocks/blob/main/docs/developer-api.md) - Technical documentation

### Contributing

We welcome contributions! Please see our [Contributing Guide](https://github.com/mralaminahamed/author-profile-blocks/blob/main/docs/contributing.md) for:
* Development guidelines
* Coding standards
* Testing procedures
* Pull request process

### Credits

**Developed by:** [Al Amin Ahamed](https://profiles.wordpress.org/mralaminahamed/)
**License:** GPL v2 or later
**Repository:** [GitHub](https://github.com/mralaminahamed/author-profile-blocks/)

### Privacy & Security

This plugin:
* Does not collect any user data
* Does not communicate with external services
* Stores data only in your WordPress database
* Follows WordPress security best practices
* Includes comprehensive input validation and sanitization