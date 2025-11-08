/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";
import { list as icon } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import Edit from "./edit";
import metadata from "./block.json";
import "./style.scss";
import type { AuthorListAttributes } from "../../types/blocks";

/**
 * View script
 */
import "./view";

/**
 * Register the block.
 */
registerBlockType(metadata.name, {
	...metadata,
	icon,
	example: {
		attributes: {
			displayStyle: "compact",
			enableDividers: true,
			showImage: true,
			showPosition: true,
			showSocial: true,
		},
	},
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * Save function is handled on the PHP side, so we return null here.
	 */
	save: () => null,
} as any);
