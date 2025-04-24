# Author List Block

The Author List Block allows you to display multiple authors in a customizable list format, perfect for team directories, contributor listings, or any situation where a vertical arrangement is preferred.

![Author List Block](../images/author-list-block.png)

## Features

- Display multiple authors in a clean, organized list
- Choose between compact or detailed display styles
- Select unordered (bulleted) or ordered (numbered) list formats
- Apply dividers between items for improved readability
- Enable hover effects for interactive elements
- Customize spacing, padding, and visual appearance
- Filter authors by role and limit the maximum displayed

## Block Settings

### Author Selection

- **Author Selection**: Choose specific authors to include in your list
- **Filter by Role**: Optionally filter authors by their WordPress role (All, Administrator, Editor, Author, Contributor)
- **Maximum Authors**: Limit the number of authors displayed (0 = show all selected)

### List Settings

- **Display Style**:
  - **Compact**: Space-efficient layout showing essential information
  - **Detailed**: Expanded layout with more author details
- **List Style**:
  - **Unordered List**: Standard bulleted list format
  - **Ordered List**: Numbered list format
- **Show Dividers**: Add separators between list items
- **Divider Color**: Customize the color of dividers
- **Rounded Corners**: Add rounded corners to list items
- **Hover Effect**: Add interactive hover animation
- **Space Between Items**: Adjust vertical spacing between authors (0-50px)
- **Block Padding**: Adjust padding around the entire block (0-50px)
- **Item Padding**: Adjust padding within each list item (0-50px)

### Colors

- **Block Background**: Set a background color for the entire list block
- **Item Background**: Set a background color for individual list items

### Display Elements

- **Show Author Image**: Toggle display of author profile pictures
- **Show Position/Role**: Toggle display of author title/position
- **Show Email**: Toggle display of author email address
- **Show Description**: Toggle display of author biographical information
- **Show Social Links**: Toggle display of author social media profiles

## Display Styles

The Author List block offers two distinct display styles to suit different needs:

### Compact Style

The Compact style presents each author in a streamlined format:
- Author image on the left
- Name and position in the middle
- Social links on the right (optional)

This style is ideal for creating concise lists with many authors, minimizing vertical space while providing essential information.

### Detailed Style

The Detailed style provides a more comprehensive presentation:
- Author image on the left
- Name, position, email, and description to the right
- Social links at the bottom

This style is perfect for more in-depth team directories where you want to showcase more information about each author.

## List Style Options

Choose between two list formats:

- **Unordered List**: Traditional bulleted list format (•)
- **Ordered List**: Sequential numbered list format (1, 2, 3...)

## Responsive Behavior

The Author List block automatically adapts to different screen sizes:

- **Desktop**: Displays both compact and detailed layouts as configured
- **Mobile**: 
  - Detailed layout switches to a stacked format with the image centered
  - Compact layout maintains its horizontal arrangement with adjusted spacing

## Usage Examples

### Team Directory (Detailed Style)

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5],
  "displayStyle": "detailed",
  "listStyle": "ul",
  "enableDividers": true,
  "enableRounded": true,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showPosition": true,
  "showSocial": true,
  "itemSpacing": 20,
  "itemPadding": 15
} /-->
```

### Contributors List (Compact Style)

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3, 4, 5, 6, 7, 8],
  "displayStyle": "compact",
  "listStyle": "ul",
  "enableDividers": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showPosition": true,
  "showSocial": true,
  "itemSpacing": 10
} /-->
```

### Numbered Author List

```
<!-- wp:author-profile-blocks/author-list {
  "authorIds": [1, 2, 3],
  "displayStyle": "detailed",
  "listStyle": "ol",
  "enableDividers": false,
  "enableRounded": true,
  "enableHoverEffect": true,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showPosition": true,
  "showSocial": true,
  "itemBackgroundColor": "#f7f7f7",
  "itemSpacing": 15,
  "itemPadding": 20
} /-->
```

## Tips and Best Practices

- Use consistent image sizes for all authors to maintain visual harmony
- For long lists, the compact style works best
- Enable dividers for improved readability, especially with detailed items
- Consider using the numbered list style for "top contributors" or ranking presentations
- Keep descriptions brief when using the detailed style to maintain consistent item heights
- Use hover effects to add interactivity for better user engagement
- For alternating background colors, add a custom CSS class and use `:nth-child(odd/even)` selectors

## Related Blocks

- [Author Profile Block](./author-profile.md) - For displaying a single author in detail
- [Author Grid Block](./author-grid.md) - For displaying authors in a responsive grid layout
- [Author Carousel Block](./author-carousel.md) - For showcasing authors in an interactive carousel
