# Author Profile Block

The Author Profile Block allows you to showcase detailed information about a single author.

![Author Profile Block](../images/author-profile-block.png)

## Features

- Display comprehensive information about a single author
- Multiple layout options (Card, Compact, Centered)
- Customizable display options
- Optional "More Content" section for additional information
- Support for various styling options

## Block Settings

### Content Settings

- **Author Selection**: Choose any user from your WordPress site
- **Show Image**: Toggle the author's profile picture
- **Show Position**: Toggle the author's title/position
- **Show Email**: Toggle the author's email address
- **Show Description**: Toggle the author's biographical information
- **Show Member Since Date**: Toggle the author's registration date with customizable label
- **Show Social Links**: Toggle the author's social media profiles
- **Show More Section**: Toggle additional custom content
- **More Content**: Add custom content about the author (when "Show More Section" is enabled)

### Style Settings

- **Text Alignment**: Left, center, or right alignment
- **Background Color**: Custom color for the block
- **Padding**: Space around the content (0-50px)
- **Shadow**: Add subtle shadow effect
- **Border**: Add customizable border
- **Border Color**: Color for the border
- **Border Width**: Width of the border (1-10px)
- **Rounded Corners**: Add rounded corners

## Layout Options

### Card Layout

The Card layout presents the author information in a card format with:
- Author image at the top
- Name and position below
- Email, registration date, and description
- Social links at the bottom

Ideal for formal presentation of authors.

### Compact Layout

The Compact layout provides a more space-efficient presentation:
- Author image on the left
- Name, position, and email on the right
- Description below
- Social links at the bottom

Ideal for sidebar widgets or when space is limited.

### Centered Layout

The Centered layout focuses on symmetry and balance:
- Author image centered at the top
- Name, position, email, and social links centered below
- Description at the bottom

Ideal for author spotlights or featured authors.

## Usage Examples

### Author Bio

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 1,
  "showImage": true,
  "showEmail": true,
  "showDescription": true,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "textAlign": "left",
  "layout": "card"
} /-->
```

### Author Spotlight

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 1,
  "showImage": true,
  "showEmail": false,
  "showDescription": true,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "textAlign": "center",
  "layout": "centered",
  "enableShadow": true,
  "enableRounded": true
} /-->
```

### Compact Sidebar

```
<!-- wp:author-profile-blocks/author-profile {
  "authorId": 1,
  "showImage": true,
  "showEmail": false,
  "showDescription": false,
  "showRegisteredDate": true,
  "showPosition": true,
  "showSocial": true,
  "layout": "compact"
} /-->
```

## Tips and Best Practices

- Use high-quality profile pictures for better visual appeal
- Keep author descriptions concise for better readability
- For consistent styling, match the block's background color with your theme
- Use the same layout for all authors on the same page for visual consistency
- Add the "More Content" section for additional information like recent posts or achievements

## Related Blocks

- [Author Grid Block](./author-grid.md) - For displaying multiple authors in a grid
- [Author Carousel Block](./author-carousel.md) - For showcasing multiple authors in a carousel
