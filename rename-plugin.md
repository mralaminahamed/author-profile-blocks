# Plugin Renaming Guide: From "WP Author Showcase" to "Author Profile Showcase"

This document outlines the necessary changes to rename the plugin from "WP Author Showcase" to "Author Profile Showcase" for WordPress.org compliance.

## ✅ Files Already Updated

1. **Main Plugin File**:
   - Created new `author-profile-showcase.php`
   - Updated plugin header information
   - Changed constants from `WPAS_` to `APS_`
   - Updated namespace references from `AuthorProfileShowcase` to `AuthorProfileShowcase`
   - Changed function `wpas()` to `aps()`

2. **README Files**:
   - `readme.txt` updated for WordPress.org submission
   - `readme.md` updated for GitHub repository

3. **Package Configuration**:
   - `composer.json` updated with new package name and autoload namespaces
   - `package.json` updated with new package name and path references
   - `phpcs.xml.dist` updated with new prefix information

4. **Block Configuration**:
   - `block.json` updated with new block name and text domain

## 🔄 Files Requiring Updates

### PHP Files with Namespace Changes
All PHP files in the `includes` directory need namespace changes from `AuthorProfileShowcase` to `AuthorProfileShowcase`:

```
find includes -name "*.php" -type f -exec sed -i 's/namespace AuthorProfileShowcase/namespace AuthorProfileShowcase/g' {} \;
```

### Text Domain Updates
All text domain references need to be updated:

```
find . -name "*.php" -type f -exec sed -i 's/author-profile-showcase/author-profile-showcase/g' {} \;
```

### Constant References
All constant references need to be updated:

```
find . -name "*.php" -type f -exec sed -i 's/WPAS_/APS_/g' {} \;
```

### Function Name Prefixes
Function prefixes should be updated:

```
find . -name "*.php" -type f -exec sed -i 's/wpas_/aps_/g' {} \;
```

### JavaScript/CSS Reference Updates
Update any references in JavaScript files:

```
find ./src -name "*.js" -type f -exec sed -i 's/author-profile-showcase/author-profile-showcase/g' {} \;
find ./src -name "*.js" -type f -exec sed -i 's/AuthorProfileShowcase/authorProfileShowcase/g' {} \;
```

### CSS Class Updates
Update CSS class names in SCSS files:

```
find ./src -name "*.scss" -type f -exec sed -i 's/wpas-/aps-/g' {} \;
```

## 📋 Manual Review Checklist

After running the automated changes, manually check these items:

1. **File Headers**: Check for any remaining references to the old plugin name in file headers
2. **Variable Names**: Look for variables that might include `wpas` in their names
3. **HTML/CSS Classes**: Ensure all CSS classes are updated for consistency
4. **Translation Function Calls**: Verify all translation function calls use the new text domain
5. **Documentation Comments**: Check for references to old plugin name in comments
6. **Build Directories**: Clean and rebuild the plugin to ensure all compiled assets use the new naming

## 🔄 Post-Rename Testing

After completing the rename process:

1. **Activation**: Test plugin activation on a fresh WordPress installation
2. **Features**: Verify all features still work correctly
3. **Blocks**: Ensure blocks render properly in the editor and frontend
4. **Settings**: Check any settings or admin pages
5. **Database**: Verify database interactions function correctly

## 📦 Final Release Steps

1. **Version Bump**: Consider incrementing the version number
2. **Changelog**: Update the changelog to mention the rename
3. **Language Files**: Regenerate .pot files with the new text domain
4. **SVN Submission**: Prepare assets for WordPress.org submission
5. **Repository**: Update GitHub repository name and URLs
