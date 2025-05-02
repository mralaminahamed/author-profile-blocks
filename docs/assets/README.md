# Assets Directory

This directory contains assets for the documentation site, including images, CSS, and JavaScript files.

## Directory Structure

```
assets/
├── images/                 # Images used in documentation
│   ├── author-profile-block.png
│   ├── author-grid-block.png
│   ├── author-carousel-block.png
│   ├── author-list-block.png
│   ├── author-profile-fields.png
│   ├── position-field.png
│   ├── description-field.png
│   ├── member-since-label-field.png
│   ├── social-media-fields.png
│   ├── author-picker.png
│   ├── style-options.png
│   ├── theme-colors.png
│   └── [other block-specific images]
├── css/                    # Custom CSS files (if needed)
│   └── custom.css
└── js/                     # JavaScript files (if needed)
    └── custom.js
```

## Adding Images

When adding images to the documentation:

1. Place the image file in the `assets/images/` directory
2. Use relative paths in Markdown files: `![Alt Text](../assets/images/image-name.png)`
3. Optimize images for web (compress and resize as needed)
4. Use descriptive filenames that match the content they represent

## Image Conventions

- **Screenshots**: Use 1280×800 pixels with browser chrome visible when appropriate
- **UI Elements**: Crop to show only the relevant part of the interface
- **Diagrams**: Use SVG format when possible for better scaling
- **Maximum Width**: Keep images under 1200px wide for better display on all devices

## CSS Customization

If you need to add custom styling to the documentation site, add your CSS to `assets/css/custom.css`.

## JavaScript Customization

If you need to add custom JavaScript functionality to the documentation site, add your JavaScript to `assets/js/custom.js`.