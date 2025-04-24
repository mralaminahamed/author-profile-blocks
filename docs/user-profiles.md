# User Profiles

This guide explains how to set up and manage author profiles for use with the Author Profile Blocks plugin.

## Enhanced User Profiles

The Author Profile Blocks plugin extends WordPress user profiles with additional fields to provide richer author information. These enhanced profiles are used by all the plugin's blocks.

## Accessing User Profiles

1. Go to **Users > All Users** in your WordPress dashboard.
2. Click on a user to edit their profile.
3. Scroll down to the "Author Profile Information" section.

## Available Profile Fields

### Position/Title

This field allows you to specify the author's role, job title, or position within your organization. For example:
- Senior Editor
- Lead Developer
- Content Manager
- Marketing Specialist

### Author Description

While WordPress has a built-in biographical info field, this separate description field allows you to create content specifically for the Author Profile Blocks. This can include:
- Professional background
- Areas of expertise
- Educational background
- Personal interests
- Writing focus

The field includes a simple WYSIWYG editor for basic formatting.

### Member Since Label

This field allows you to customize how the author's registration date is displayed. By default, it shows "Member since" followed by the date, but you can change it to:
- "Joined our team on"
- "Writing for us since" 
- "Contributing since"
- "With us since"
- Any other phrasing that fits your site's tone

### Social Media Profiles

The plugin supports linking to the following social platforms:
- Facebook
- Twitter
- LinkedIn
- Instagram
- Personal Website

Enter the full URL for each platform (e.g., `https://twitter.com/username`).

## Best Practices

### Profile Images

Author profile pictures are pulled from WordPress Gravatar. To set or update an author's image:

1. Go to [Gravatar.com](https://gravatar.com)
2. Sign in with the same email used for the WordPress account
3. Upload or update the profile image
4. Wait a few minutes for the changes to propagate

For best results, use a square image with minimum dimensions of 300x300 pixels.

### Writing Effective Author Descriptions

- Keep descriptions concise (2-3 paragraphs maximum)
- Focus on relevant expertise and background
- Use a consistent tone and format across all authors
- Include a personal touch to make authors relatable
- Avoid overly promotional language

### Social Media Links

- Only include active, professional social profiles
- Ensure all URLs are correct and include the https:// prefix
- Keep social profiles updated

## User Roles and Permissions

By default, the Author Profile Blocks plugin can display any user with the following roles:
- Administrator
- Editor
- Author
- Contributor

You can filter which roles to display in the Grid and Carousel blocks.

## Bulk Updating Author Profiles

For sites with many authors, consider using the WordPress Import/Export tools along with a spreadsheet to bulk update user meta data.

## Using Author Data in Templates

Developers can access the enhanced author data programmatically using the following function:

```php
$author_data = AuthorProfileBlocks\Plugin::get_instance()->get_author_data($user_id);
```

This returns an array with all the author's information, including:
- `id` - The author's user ID
- `title` - The author's display name
- `email` - The author's email address
- `description` - The author's custom description
- `position` - The author's position/title
- `social` - Array of social media links
- `image` - URL to the author's profile image
- `registered_date` - The formatted registration date
- `member_since_label` - The custom label for the registration date
- `role` - The author's WordPress role
