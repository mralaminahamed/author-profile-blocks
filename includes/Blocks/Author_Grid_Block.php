<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Author Grid Block class
 *
 * @package AuthorProfileBlocks
 * @license GPL-3.0-only
 */

namespace AuthorProfileBlocks\Blocks;

use AuthorProfileBlocks\Blocks\Author_Block_Base;
use WP_Block;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the Author Grid block.
 */
class Author_Grid_Block extends Author_Block_Base {
	/**
	 * Get the block name.
	 *
	 * @return string Block name.
	 */
	public function get_block_name(): string {
		return 'author-grid';
	}

	/**
	 * Get render callback for the block.
	 *
	 * @return callable|null Block render callback.
	 */
	protected function get_render_callback(): ?callable {
		return array( $this, 'render_callback' );
	}

	/**
	 * Render callback for the block.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 *
	 * @return string Rendered block output.
	 */
	public function render_callback( array $attributes, string $content, $block ): string {
		$author_ids = $this->extract_author_ids( $attributes );

		if ( empty( $author_ids ) ) {
			return $this->render_error_message( sprintf( $this->get_no_authors_selected_message(), 'grid' ) );
		}

		// Check cache first.
		$cache_key      = $this->generate_cache_key( $author_ids, $attributes );
		$cached_content = $this->get_cached_render( $cache_key );
		if ( $cached_content ) {
			return $cached_content;
		}

		// Apply author role filter if specified.
		$roles = $this->extract_author_roles( $attributes );

		// Get authors data.
		$authors = $this->get_authors_data( $author_ids, $roles );

		// Apply maximum authors limit if specified.
		$max_authors = $this->extract_max_authors( $attributes );
		$authors     = $this->apply_author_limit( $authors, $max_authors );

		// If no authors found after filtering.
		if ( empty( $authors ) ) {
			return $this->render_error_message( $this->get_no_authors_found_message() );
		}

		// Generate styles for the block.
		$wrapper_styles  = $this->get_block_styles( $attributes );
		$style_attribute = '';

		if ( ! empty( $wrapper_styles ) ) {
			$style_attribute = $this->get_styles_string( $wrapper_styles );
		}

		// Classes for the block wrapper.
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => $this->get_block_classes( $attributes, 'grid' ),
				'style' => $style_attribute,
			)
		);

		// Build the HTML.
		ob_start();
		?>
		<div <?php echo $wrapper_attributes; ?>>
			<?php
			wc_get_template(
				'blocks/author-grid/grid.php',
				array(
					'authors'        => $authors,
					'attributes'     => $attributes,
					'block_instance' => $this,
				),
				'',
				plugin_dir_path( __FILE__ ) . '../../templates/'
			);
			?>
		</div>
		<?php
		$html = ob_get_clean();

		// Cache the result.
		$this->set_cached_render( $cache_key, $html );

		return $html;
	}

	/**
	 * Render an individual author item within the grid.
	 *
	 * @param array $author     Author data.
	 * @param array $attributes Block attributes.
	 *
	 * @return string Rendered HTML.
	 */
	public function render_author_item( array $author, array $attributes ): string {
		ob_start();
		wc_get_template(
			'blocks/author-grid/item.php',
			array(
				'author'         => $author,
				'attributes'     => $attributes,
				'block_instance' => $this,
			),
			'',
			plugin_dir_path( __FILE__ ) . '../../templates/'
		);
		return ob_get_clean();
	}
}
