# WP Author Showcase

A WordPress plugin that showcases author profiles with a beautiful Gutenberg block. Display author information from a custom post type with customizable styles and layout options.

## Features

- Custom Post Type for author profiles (`author_profile`)
- Gutenberg block for displaying author information
- Customizable display options (background color, text alignment, padding)
- Optional "Show More" section for additional content
- Responsive design
- Follows WordPress coding standards

## Requirements

- WordPress 6.7+
- PHP 7.4+
- Node.js and Yarn for development

## Installation

### From ZIP File

1. Download the plugin ZIP file
2. Navigate to WordPress Admin → Plugins → Add New → Upload Plugin
3. Choose the ZIP file and click "Install Now"
4. Activate the plugin through the 'Plugins' screen in WordPress

### Manual Installation

1. Upload the plugin files to the `/wp-content/plugins/wp-author-showcase` directory
2. Activate the plugin through the 'Plugins' screen in WordPress

## Usage

### Creating Author Profiles

1. Navigate to "Author Profiles" in the WordPress admin menu
2. Click "Add New" to create a new author profile
3. Fill in the author details:
   - Name (title)
   - Email (custom field)
   - Image (featured image)
   - Description (custom field)
4. Publish the author profile

### Using the Author Profile Block

1. Create or edit a page/post
2. Add the "Author Profile" block
3. Select an author from the dropdown
4. Customize the display options in the block settings sidebar:
   - Toggle "Show More Section" to add additional content
   - Adjust background color
   - Change text alignment
   - Modify padding

## Development Setup

### Prerequisites

- Node.js and Yarn
- Composer
- WordPress development environment

### Setup

1. Clone the repository:
   ```
   git clone https://github.com/mralaminahamed/wp-author-showcase.git
   ```

2. Navigate to the plugin directory:
   ```
   cd wp-author-showcase
   ```

3. Install JavaScript dependencies:
   ```
   yarn install
   ```

4. Install PHP dependencies:
   ```
   composer install
   ```

### Development Commands

#### JavaScript/CSS Development

- Start development mode with watch:
  ```
  yarn start
  ```

- Build for production:
  ```
  yarn build
  ```

- Lint JavaScript:
  ```
  yarn lint:js
  ```

- Lint CSS:
  ```
  yarn lint:css
  ```

- Format code:
  ```
  yarn format
  ```

#### PHP Development

- Check PHP coding standards:
  ```
  composer phpcs
  ```

- Auto-fix PHP coding standards:
  ```
  composer phpcbf
  ```

- Run PHPStan static analysis:
  ```
  composer phpstan
  ```

- Generate .pot file for translations:
  ```
  composer wp:make-pot
  ```

### Creating a Distribution Package

To create a distribution package of the plugin:

```
yarn plugin-zip
```

This will create a ZIP file in the project root that can be used to install the plugin.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the GPL-3.0 License - see the LICENSE file for details. 