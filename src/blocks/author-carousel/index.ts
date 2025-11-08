/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

/**
 * Slick carousel styles
 */
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

/**
 * Internal dependencies
 */
import "./style.scss";
import "./editor.scss";
import Edit from "./edit";
import metadata from "./block.json";
import type { AuthorCarouselAttributes } from "../../types/blocks";

/**
 * Register the block
 */
registerBlockType(metadata.name, {
	...metadata,
	edit: Edit,
	save: () => null,
} as any);
