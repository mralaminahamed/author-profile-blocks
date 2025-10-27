/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl } from '@wordpress/components';

/**
 * AdvancedPanel component for advanced settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Advanced panel component
 */
const AdvancedPanel = ({ attributes, setAttributes }) => {
	const { customCssClass, customVar1, customVar2 } = attributes;

	return (
		<>
			<PanelBody
				title={__('Additional CSS Class', 'author-profile-blocks')}
			>
				<TextControl
					label={__('Custom CSS Class', 'author-profile-blocks')}
					value={customCssClass || ''}
					onChange={(value) =>
						setAttributes({ customCssClass: value })
					}
					help={__(
						'Add custom CSS class(es) to the block for additional styling.',
						'author-profile-blocks'
					)}
				/>
			</PanelBody>

			<PanelBody
				title={__('Custom CSS Variables', 'author-profile-blocks')}
				initialOpen={false}
			>
				<p>
					{__(
						'These advanced settings generate CSS variables that can be used for custom styling with CSS.',
						'author-profile-blocks'
					)}
				</p>

				<TextControl
					label={__(
						'--author-profile-custom-var-1',
						'author-profile-blocks'
					)}
					value={customVar1 || ''}
					onChange={(value) => setAttributes({ customVar1: value })}
				/>

				<TextControl
					label={__(
						'--author-profile-custom-var-2',
						'author-profile-blocks'
					)}
					value={customVar2 || ''}
					onChange={(value) => setAttributes({ customVar2: value })}
				/>
			</PanelBody>
		</>
	);
};

export default AdvancedPanel;
