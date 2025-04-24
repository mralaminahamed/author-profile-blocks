# Author Grid Block

The Author Grid Block is a new addition to the Author Profile Blocks plugin that allows you to display multiple authors in a responsive grid layout with various customization options.

## Features

- **Multiple Author Selection**: Choose any number of authors to display in the grid
- **Responsive Design**: Automatically adjusts for different screen sizes
- **Layout Options**: Choose from three distinct layouts:
  - Card: Authors displayed in card-style blocks
  - Compact: Space-efficient layout with author image on the left
  - Centered: Centered layout with author image at the top
- **Display Options**: Toggle visibility of author image, position/title, email, description, and social links
- **Style Customization**: Control colors, spacing, borders, shadows, and rounded corners
- **Role Filtering**: Optionally filter authors by role (administrator, editor, author, contributor)
- **Maximum Authors**: Limit the number of authors displayed

## How to Use

1. Add the "Author Grid" block to your post or page using the block editor
2. Select authors to display in the grid
3. Customize the layout and styling options in the block sidebar
4. Preview your changes in real-time

## Implementation Details

The Author Grid block was implemented using the following components:

### Frontend

- **Grid Layout**: CSS Grid layout that adjusts columns based on screen size
- **Author Cards**: Each author is displayed in a styled card with consistent formatting
- **Social Icons**: Displays social profiles with recognizable icons

### Backend

- **Block Registration**: Uses WordPress block API to register the block
- **Server-Side Rendering**: Renders the block on the server for SEO and consistency
- **Caching**: Implements caching to optimize performance for multiple authors

### Customization Options

Through the sidebar controls, users can customize:

- Number of columns (1-4)
- Spacing between items
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
   - GridLayoutSelector for layout options
   - AuthorGridPreview for displaying the preview

2. **PHP rendering**:
   - Server-side rendering class
   - Layout templates for different display options
   - Cache management for performance

## Example Usage

Add the Author Grid block to showcase your team, contributors, or featured authors on your WordPress site. Perfect for:

- Team pages
- About Us sections
- Magazine contributor listings
- Guest author showcases
- Conference speaker pages

## Compatibility

The Author Grid block is fully compatible with:
- WordPress 6.0+
- All modern browsers
- Mobile devices
- Theme customization
