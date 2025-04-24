# Author Carousel Block

The Author Carousel Block is a dynamic addition to the Author Profile Blocks plugin that allows you to showcase multiple authors in an interactive carousel format with various customization options.

## Features

- **Multiple Author Selection**: Choose any number of authors to display in the carousel
- **Interactive Carousel**: Smooth, responsive slider for showing authors
- **Layout Options**: Choose from three distinct layouts:
  - Card: Authors displayed in card-style blocks
  - Compact: Space-efficient layout with author image on the left
  - Centered: Centered layout with author image at the top
- **Display Options**: Toggle visibility of author image, position/title, email, description, and social links
- **Carousel Controls**: Configure autoplay, dots, arrows, and other carousel behavior
- **Style Customization**: Control colors, spacing, borders, shadows, and rounded corners
- **Role Filtering**: Optionally filter authors by role (administrator, editor, author, contributor)
- **Maximum Authors**: Limit the number of authors displayed
- **Responsive Design**: Automatically adjusts for different screen sizes

## How to Use

1. Add the "Author Carousel" block to your post or page using the block editor
2. Select authors to display in the carousel
3. Customize the layout and carousel settings in the block sidebar
4. Preview your changes in real-time

## Implementation Details

The Author Carousel block was implemented using the following components:

### Frontend

- **Slick Carousel**: Leverages the popular Slick Carousel jQuery plugin for smooth sliding functionality
- **Author Cards**: Each author is displayed in a styled card with consistent formatting
- **Social Icons**: Displays social profiles with recognizable icons
- **Responsive Design**: Automatically adjusts the number of visible slides based on screen size

### Backend

- **Block Registration**: Uses WordPress block API to register the block
- **Server-Side Rendering**: Renders the block on the server for SEO and consistency
- **Caching**: Implements caching to optimize performance for multiple authors

### Customization Options

Through the sidebar controls, users can customize:

- Number of slides to show (1-5)
- Autoplay settings (on/off, speed)
- Navigation options (dots, arrows)
- Spacing between slides
- Item padding
- Background color
- Show/hide various author information
- Border colors and width
- Enable/disable shadow effects
- Enable/disable rounded corners

## Code Structure

The block implementation consists of:

1. **JavaScript (React) components**:
   - Main edit component
   - AuthorsSelector for choosing authors
   - CarouselLayoutSelector for layout options
   - AuthorCarouselPreview for displaying the preview

2. **PHP rendering**:
   - Server-side rendering class
   - Layout templates for different display options
   - Cache management for performance

3. **Frontend JavaScript**:
   - Integration with Slick Carousel
   - Responsive settings for different screen sizes

## Example Usage

Add the Author Carousel block to showcase your team, contributors, or featured authors on your WordPress site. Perfect for:

- Team pages with a limited space
- About Us sections where you want to showcase many team members
- Magazine contributor listings
- Guest author showcases
- Conference speaker pages

## Compatibility

The Author Carousel block is fully compatible with:
- WordPress 6.0+
- All modern browsers
- Mobile devices
- Theme customization

## Technical Notes

- The carousel functionality requires jQuery (already included in WordPress)
- Slick Carousel is loaded only when needed to minimize impact on page load
- Settings are stored as JSON data attributes for easy configuration
- Responsive breakpoints automatically adjust the number of visible slides
