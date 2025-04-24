# Unified Block Placeholder System

This document explains the unified placeholder system created for author blocks (list, grid, carousel) and how to use it effectively.

## Overview

We've created a set of reusable components to standardize empty states, loading states, and layout selection across block types. These include:

1. `UnifiedBlockPlaceholder` - A base component for creating highly customizable placeholders
2. `AuthorBlockPlaceholder` - An author-specific placeholder (extends the unified one)
3. `BlockLayoutSelector` - A standardized layout selection component
4. `BlockStateDisplay` - A component for showing loading, error, and empty states

## Using the Components

### Unified Block Placeholder

This is the most flexible placeholder component and can be used as a foundation for any block:

```jsx
import { UnifiedBlockPlaceholder } from '../../js/components';
import { grid } from '@wordpress/icons';

// In your edit function:
<UnifiedBlockPlaceholder
    icon={grid}
    title={__('My Block Title', 'text-domain')}
    instructions={__('Instructions for the user', 'text-domain')}
    className="my-custom-class"
>
    {/* Your content here */}
    <p>This content appears in the placeholder</p>
    
    {/* You can add any components here */}
    <Button variant="primary">Click Me</Button>
</UnifiedBlockPlaceholder>
```

### Author Block Placeholder

This extends the unified placeholder with author-specific functionality:

```jsx
import { AuthorBlockPlaceholder } from '../../components';
import { list } from '@wordpress/icons';

// In your edit function:
<AuthorBlockPlaceholder
    icon={list}
    title={__('Author List', 'author-profile-blocks')}
    instructions={__('Select authors to display', 'author-profile-blocks')}
    selectedAuthorIds={authorIds}
    onChange={handleAuthorIdsChange}
    buttonLabel={__('Select Authors', 'author-profile-blocks')}
    layoutSelector={<YourCustomLayoutSelector />}
    additionalControls={<YourAdditionalControls />}
    className="my-custom-class"
/>
```

### Block Layout Selector

This component standardizes layout selection across blocks:

```jsx
import { BlockLayoutSelector } from '../../js/components';

// Define your layouts
const layouts = [
    { value: 'grid', label: 'Grid', icon: gridIcon },
    { value: 'list', label: 'List', icon: listIcon },
    { value: 'carousel', label: 'Carousel', icon: carouselIcon },
];

// In your component:
<BlockLayoutSelector
    layouts={layouts}
    selectedLayout={currentLayout}
    onSelectLayout={handleLayoutChange}
    className="my-custom-class"
/>
```

### Block State Display

Use this component to show loading, error, or empty states:

```jsx
import { BlockStateDisplay } from '../../js/components';
import { grid } from '@wordpress/icons';

// In your component:
<BlockStateDisplay
    isLoading={isDataLoading}
    error={errorMessage}
    isEmpty={!items.length}
    emptyMessage={__('No authors found', 'author-profile-blocks')}
    loadingMessage={__('Loading authors...', 'author-profile-blocks')}
    className="my-custom-class"
    icon={grid}
/>
```

## Styling

All components include sensible default styling in `src/scss/common/_placeholder.scss`. You can customize these styles or add block-specific styling in your block's SCSS files.

## Best Practices

1. **Consistent Icons**: Use WordPress icons from `@wordpress/icons` to maintain consistency
2. **Meaningful Instructions**: Provide clear instructions in the placeholder
3. **Layout Consistency**: Use similar layout options across block types when appropriate
4. **Loading States**: Always provide loading states and error messages for async operations
5. **i18n Support**: Ensure all text is wrapped with `__()` for translation support

## Example Implementations

Each block type (list, grid, carousel) now has a standardized placeholder implementation.

### Author Grid Block
```jsx
<AuthorBlockPlaceholder
    icon={grid}
    title={__('Author Grid', 'author-profile-blocks')}
    instructions={__('Select authors to display in a grid layout.', 'author-profile-blocks')}
    selectedAuthorIds={authorIds}
    onChange={handleAuthorIdsChange}
    buttonLabel={__('Add Author to Grid', 'author-profile-blocks')}
    layoutSelector={
        <GridLayoutSelector
            selectedLayout={layout}
            onSelectLayout={handleSelectLayout}
        />
    }
/>
```

### Author List Block
```jsx
<AuthorBlockPlaceholder
    icon={list}
    title={__('Author List', 'author-profile-blocks')}
    instructions={__('Select authors to display in a list format.', 'author-profile-blocks')}
    selectedAuthorIds={authorIds}
    onChange={handleAuthorIdsChange}
    buttonLabel={__('Add Author to List', 'author-profile-blocks')}
    layoutSelector={
        <ListLayoutSelector
            selectedLayout={listStyle}
            onSelectLayout={(style) => setAttributes({ listStyle: style })}
        />
    }
/>
```

### Author Carousel Block
```jsx
<AuthorBlockPlaceholder
    icon={slideshow}
    title={__('Author Carousel', 'author-profile-blocks')}
    instructions={__('Select authors to display in a carousel.', 'author-profile-blocks')}
    selectedAuthorIds={authorIds}
    onChange={handleAuthorIdsChange}
    buttonLabel={__('Add Author to Carousel', 'author-profile-blocks')}
    layoutSelector={
        <CarouselLayoutSelector
            selectedLayout={layout}
            onSelectLayout={handleSelectLayout}
        />
    }
/>
```
