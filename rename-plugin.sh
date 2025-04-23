#!/bin/bash

# Script to rename "WP Author Showcase" to "Author Profile Showcase"
# This script should be run from the plugin root directory

echo "Starting plugin rename process..."

# Make a backup of all files before modifying
echo "Creating backup..."
BACKUP_DIR="../author-profile-showcase-backup-$(date +%Y%m%d%H%M%S)"
mkdir -p "$BACKUP_DIR"
cp -r ./* "$BACKUP_DIR/"
echo "Backup created at $BACKUP_DIR"

# Update namespace references
echo "Updating namespace references..."
find ./includes -name "*.php" -type f -exec sed -i 's/namespace AuthorProfileShowcase/namespace AuthorProfileShowcase/g' {} \;
find ./includes -name "*.php" -type f -exec sed -i 's/use AuthorProfileShowcase/use AuthorProfileShowcase/g' {} \;

# Update text domain references
echo "Updating text domain references..."
find . -name "*.php" -type f -exec sed -i 's/wp-author-showcase/author-profile-showcase/g' {} \;
find ./src -name "*.js" -type f -exec sed -i 's/wp-author-showcase/author-profile-showcase/g' {} \;

# Update constant references
echo "Updating constant references..."
find . -name "*.php" -type f -exec sed -i 's/WPAS_/APS_/g' {} \;

# Update function name prefixes
echo "Updating function prefixes..."
find . -name "*.php" -type f -exec sed -i 's/function wpas_/function aps_/g' {} \;
find . -name "*.php" -type f -exec sed -i 's/wpas_/aps_/g' {} \;

# Update JavaScript variable references
echo "Updating JavaScript references..."
find ./src -name "*.js" -type f -exec sed -i 's/AuthorProfileShowcase/authorProfileShowcase/g' {} \;
find ./src -name "*.js" -type f -exec sed -i 's/wpas-/aps-/g' {} \;

# Update CSS class names
echo "Updating CSS class names..."
find ./src -name "*.scss" -type f -exec sed -i 's/wpas-/aps-/g' {} \;
find ./src -name "*.scss" -type f -exec sed -i 's/wp-block-wp-author-showcase/wp-block-author-profile-showcase/g' {} \;

# Update block references
echo "Updating block references..."
find ./src -name "*.js" -type f -exec sed -i 's/wp-author-showcase\/author-profile/author-profile-showcase\/author-profile/g' {} \;

# Clean build files to ensure all compiled assets use the new naming
echo "Cleaning build files..."
rm -rf ./build

# Regenerate language files
echo "Regenerating language files..."
if command -v wp > /dev/null; then
  wp i18n make-pot ./ ./languages/author-profile-showcase.pot --domain=author-profile-showcase --package-name="Author Profile Showcase"
else
  echo "Warning: WP-CLI not found. Please regenerate language files manually."
fi

echo "Rename process completed!"
echo ""
echo "IMPORTANT: Please review the files manually for any missed references."
echo "Suggested steps:"
echo "1. Run 'yarn build' to rebuild the assets"
echo "2. Test the plugin thoroughly"
echo "3. Remove the old wp-author-showcase.php file after verifying everything works"
echo ""
