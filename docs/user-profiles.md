---
layout: default
title: User Profiles
nav_order: 4
permalink: /user-profiles/
---

# User Profiles
{: .no_toc }

How to set up and manage author profiles used by the Author Profile Blocks plugin.
{: .fs-6 .fw-300 }

## Table of contents
{: .no_toc .text-delta }

1. TOC
   {:toc}

---

## Enhanced User Profiles

The plugin extends WordPress user profiles with additional fields stored as user meta. These fields are used across all four blocks.

| Field | User Meta Key | Description |
|---|---|---|
| Position / Title | `apbl_author_position` | Job title or role (e.g. "Lead Developer") |
| Author Description | `apbl_author_description` | Bio text shown in blocks (separate from WP bio field) |
| Social Profiles | `apbl_social_profiles` | Serialized array of social platform URLs |

---

## Accessing User Profiles

1. Go to **Users > All Users** in your WordPress dashboard.
2. Click on a user to edit their profile.
3. Scroll down to the **Author Profile Information** section.
4. Fill in the fields and click **Update User**.

---

## Available Profile Fields

### Position / Title

Stored in `apbl_author_position`. Displayed beneath the author's name as a small uppercase badge.

Examples:
- `Lead Developer`
- `Product Manager`
- `UI/UX Designer`
- `Marketing Director`

### Author Description

Stored in `apbl_author_description`. This is a plain-text field separate from WordPress's built-in "Biographical Info" field, so you can maintain different copy for block display vs. the author archive.

Keep it 2–3 sentences: professional background, expertise, what they work on.

### Social Media Profiles

Stored in `apbl_social_profiles` as a PHP serialized array. Supported platforms and their expected URL format:

| Platform | Example URL |
|---|---|
| Facebook | `https://facebook.com/username` |
| Twitter | `https://twitter.com/username` |
| LinkedIn | `https://linkedin.com/in/username` |
| Instagram | `https://instagram.com/username` |
| Website | `https://example.com` |

Enter the full URL including `https://`. Each link renders as an icon button in the social profiles row.

---

## Profile Images

Author avatars are pulled from [Gravatar](https://gravatar.com) using the user's registered email address.

To set or update an author's avatar:

1. Go to [Gravatar.com](https://gravatar.com) and sign in with the user's WordPress email.
2. Upload a square image — minimum **150 × 150 px**, ideally **300 × 300 px**.
3. Allow a few minutes for changes to propagate.

{: .note }
> Gravatar images are requested at the size set by the block's avatar size attribute (default 150 px). Uploading a larger source image ensures sharp rendering at all sizes.

### Swap Gravatar for a custom image

```php
add_filter( 'author_profile_blocks_author_data', function( $author_data, $user ) {
    $image_id = get_user_meta( $user->ID, 'custom_avatar_id', true );
    if ( $image_id ) {
        $author_data['image'] = wp_get_attachment_image_url( $image_id, 'medium' );
    }
    return $author_data;
}, 10, 2 );
```

---

## User Roles

All blocks support filtering displayed authors by WordPress role:

- Administrator
- Editor
- Author
- Contributor
- Subscriber (hidden by default)

Set this in each block's **Filter by Role** setting.

---

## Accessing Author Data in PHP

```php
// Get all data for a single user as used by blocks
$author = apply_filters(
    'author_profile_blocks_author_data',
    [
        'id'                 => $user->ID,
        'title'              => $user->display_name,
        'email'              => $user->user_email,
        'description'        => get_user_meta( $user->ID, 'apbl_author_description', true ),
        'position'           => get_user_meta( $user->ID, 'apbl_author_position', true ),
        'social'             => get_user_meta( $user->ID, 'apbl_social_profiles', true ),
        'image'              => get_avatar_url( $user->ID, [ 'size' => 150 ] ),
        'registered_date'    => $user->user_registered,
        'role'               => implode( ', ', $user->roles ),
    ],
    $user
);
```

### Returned array keys

| Key | Type | Description |
|---|---|---|
| `id` | `int` | WordPress user ID |
| `title` | `string` | Display name |
| `email` | `string` | Email address |
| `description` | `string` | Custom bio (`apbl_author_description`) |
| `position` | `string` | Job title (`apbl_author_position`) |
| `social` | `array\|string` | Social URLs (`apbl_social_profiles`) — always check `is_array()` |
| `image` | `string` | Gravatar URL |
| `registered_date` | `string` | Raw `user_registered` date |
| `role` | `string` | Comma-separated role names |

{: .warning }
> The `social` key may be a serialized string in older data. Always guard with `is_array( $author['social'] )` before iterating.

---

## Filtering Author Data

```php
add_filter( 'author_profile_blocks_author_data', function( $author_data, $user ) {
    // Prepend "Dr." to all names
    $author_data['title'] = 'Dr. ' . $author_data['title'];

    // Inject a custom field
    $author_data['department'] = get_user_meta( $user->ID, 'department', true );

    return $author_data;
}, 10, 2 );
```

---

## Best Practices

- **Consistency** — use the same tone and structure across all author bios
- **Brevity** — 2–3 sentences in `apbl_author_description` reads better than a paragraph
- **Active profiles only** — only link social accounts the author actively maintains
- **Square avatars** — upload square source images to Gravatar to avoid cropping artifacts
