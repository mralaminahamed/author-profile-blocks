import type { ListInspectorProps } from '../../types';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	AdvancedTypography,
	AnimationControls,
} from '../../../../supports/js/components/inspector';

/**
 * AdvancedPanel component for advanced settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Advanced panel component
 */
const AdvancedPanel = ( { attributes, setAttributes }: ListInspectorProps ) => {
	const {
		customCssClass,
		customVar1,
		customVar2,
		// Animation attributes
		animationType,
		animationDuration,
		hoverEffect,
		// Typography attributes
		googleFont,
		fontSizeUnit,
	} = attributes;

	return (
		<>
			<AnimationControls
				animationType={ animationType }
				animationDuration={ animationDuration }
				hoverEffect={ hoverEffect }
				onChange={ setAttributes }
				itemLabel={ __( 'list items', 'author-profile-blocks' ) }
			/>

			<AdvancedTypography
				googleFont={ googleFont }
				fontSizeUnit={ fontSizeUnit }
				onChange={ setAttributes }
				title={ __( 'Advanced Typography', 'author-profile-blocks' ) }
				googleFontHelp={ __(
					'Choose from popular Google Fonts to enhance list typography',
					'author-profile-blocks',
				) }
				fontNotice={ __(
					'Google Font loaded. The selected font will be applied to author names in the list.',
					'author-profile-blocks',
				) }
				fontSizeUnitHelp={ __(
					'Choose the unit for font sizes (px for fixed, em/rem for responsive)',
					'author-profile-blocks',
				) }
				tips={
					<div
						style={ {
							marginTop: '16px',
							padding: '12px',
							backgroundColor: '#f8f9fa',
							borderRadius: '4px',
						} }
					>
						<h4
							style={ {
								margin: '0 0 8px 0',
								fontSize: '14px',
								fontWeight: '600',
							} }
						>
							{ __( 'Typography Tips', 'author-profile-blocks' ) }
						</h4>
						<ul
							style={ {
								margin: '0',
								paddingLeft: '16px',
								fontSize: '13px',
								color: '#666',
							} }
						>
							<li>
								{ __(
									'Use em/rem units for responsive list design',
									'author-profile-blocks',
								) }
							</li>
							<li>
								{ __(
									'Google Fonts enhance visual appeal of author names',
									'author-profile-blocks',
								) }
							</li>
							<li>
								{ __(
									'Test readability across different screen sizes',
									'author-profile-blocks',
								) }
							</li>
						</ul>
					</div>
				}
			/>

			<PanelBody
				title={ __( 'Additional CSS Class', 'author-profile-blocks' ) }
			>
				<TextControl
					label={ __( 'Custom CSS Class', 'author-profile-blocks' ) }
					value={ customCssClass || '' }
					onChange={ ( value ) =>
						setAttributes( { customCssClass: value } )
					}
					help={ __(
						'Add custom CSS class(es) to the block for additional styling.',
						'author-profile-blocks',
					) }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Custom CSS Variables', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<p>
					{ __(
						'These advanced settings generate CSS variables that can be used for custom styling with CSS.',
						'author-profile-blocks',
					) }
				</p>

				<TextControl
					label={ __(
						'--author-list-custom-var-1',
						'author-profile-blocks',
					) }
					value={ customVar1 || '' }
					onChange={ ( value ) => setAttributes( { customVar1: value } ) }
				/>

				<TextControl
					label={ __(
						'--author-list-custom-var-2',
						'author-profile-blocks',
					) }
					value={ customVar2 || '' }
					onChange={ ( value ) => setAttributes( { customVar2: value } ) }
				/>
			</PanelBody>
		</>
	);
};

export default AdvancedPanel;
