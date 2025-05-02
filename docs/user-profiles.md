---
layout: default
title: User Profiles
nav_order: 4
permalink: /user-profiles/
---

# User Profiles
{: .no_toc }

This guide explains how to set up and manage author profiles for use with the Author Profile Blocks plugin.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Enhanced User Profiles

The Author Profile Blocks plugin extends WordPress user profiles with additional fields to provide richer author information. These enhanced profiles are used by all the plugin's blocks.

## Accessing User Profiles

1. Go to **Users > All Users** in your WordPress dashboard.
2. Click on a user to edit their profile.
3. Scroll down to the "Author Profile Information" section.

![Author Profile Fields](../assets/images/author-profile-fields.png)

## Available Profile Fields

### Position/Title

This field allows you to specify the author's role, job title, or position within your organization. For example:
- Senior Editor
- Lead Developer
- Content Manager
- Marketing Specialist

![Position Field](../assets/images/position-field.png)

### Author Description

While WordPress has a built-in biographical info field, this separate description field allows you to create content specifically for the Author Profile Blocks. This can include:
- Professional background
- Areas of expertise
- Educational background
- Personal interests
- Writing focus

The field includes a simple WYSIWYG editor for basic formatting.

![Description Field](../assets/images/description-field.png)

### Member Since Label

This field allows you to customize how the author's registration date is displayed. By default, it shows "Member since" followed by the date, but you can change it to:
- "Joined our team on"
- "Writing for us since"
- "Contributing since"
- "With us since"
- Any other phrasing that fits your site's tone

![Member Since Label Field](../assets/images/member-since-label-field.png)

### Social Media Profiles

The plugin supports linking to the following social platforms:
- Facebook
- Twitter
- LinkedIn
- Instagram
- Personal Website

Enter the full URL for each platform (e.g., `https://twitter.com/username`).

![Social Media Fields](../assets/images/social-media-fields.png)

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

## Filtering Author Data

Developers can filter author data using the `author_profile_blocks_author_data` filter:

```php
/**
 * Customize author data before it's returned
 *
 * @param array   $author_data The author data.
 * @param WP_User $user        The user object.
 * @return array  Modified author data.
 */
function my_custom_author_data( $author_data, $user ) {
    // Add custom data
    $author_data['custom_field'] = get_user_meta( $user->ID, 'my_custom_field', true );
    
    // Modify existing data
    $author_data['title'] = 'Dr. ' . $author_data['title'];
    
    return $author_data;
}
add_filter( 'author_profile_blocks_author_data', 'my_custom_author_data', 10, 2 );
```

## Adding Custom Profile Fields

Developers can add custom fields to the author profile section using the `author_profile_blocks_profile_fields` action:

```php
/**
 * Add custom fields to the author profile section
 *
 * @param WP_User $user The user object.
 */
function my_custom_profile_fields( $user ) {
    // Get current value
    $custom_value = get_user_meta( $user->ID, 'my_custom_field', true );
    ?>
    <tr class="apb-meta-field">
        <th><label for="my_custom_field">Custom Field</label></th>
        <td>
            <input type="text" name="my_custom_field" id="my_custom_field" value="<?php echo esc_attr( $custom_value ); ?>" class="regular-text"/>
            <p class="description">This is my custom field description</p>
        </td>
    </tr>
    <?php
}
add_action( 'author_profile_blocks_profile_fields', 'my_custom_profile_fields' );
```

You'll also need to save the custom field values:

```php
/**
 * Save custom profile fields
 *
 * @param int $user_id The user ID.
 */
function my_save_custom_profile_fields( $user_id ) {
    if ( isset( $_POST['my_custom_field'] ) ) {
        update_user_meta( $user_id, 'my_custom_field', sanitize_text_field( $_POST['my_custom_field'] ) );
    }
}
add_action( 'author_profile_blocks_save_profile_fields', 'my_save_custom_profile_fields' );
```