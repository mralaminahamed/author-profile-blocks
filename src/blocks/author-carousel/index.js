/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Slick carousel styles
 */
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

/**
 * Internal dependencies
 */
import './style.scss';
import './editor.scss';
import Edit from './edit';
import metadata from './block.json';

/**
 * Register the block
 */
registerBlockType( metadata.name, {
	edit: Edit,
	save: () => null,
} );
