import type { ProfileInspectorProps } from '../../types';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	RangeControl,
	SelectControl,
	BaseControl,
	ColorPalette,
	ToggleControl,
	CardDivider,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { PresetStyles } from '../PresetStyles';
import { AdvancedStyling } from '../../../../supports/js/components/inspector';

/**
 * StylePanel component for styling settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Style panel component
 */
const StylePanel = ( { attributes, setAttributes }: ProfileInspectorProps ) => {
	const {
		backgroundColor,
		// Advanced styling attributes
		gradientBackground,
		gradientStartColor,
		gradientEndColor,
		gradientDirection,
		transformScale,
		transformRotate,
		filterBrightness,
		filterContrast,
		filterSaturate,
		// Avatar settings
		showImage,
		avatarSize,
		avatarShape,
		avatarBorderWidth,
		avatarBorderColor,
		avatarBorderRadius,
		avatarAlignment,
		avatarMargin,
		// Name settings
		nameColor,
		nameSize,
		nameWeight,
		nameTransform,
		nameAlignment,
		nameMargin,
		// Description settings
		showDescription,
		descriptionColor,
		descriptionSize,
		descriptionLineHeight,
		descriptionStyle,
		descriptionAlignment,
		descriptionMargin,
		// Meta settings
		showEmail,
		showRegisteredDate,
		metaColor,
		metaSize,
		metaStyle,
		metaBold,
		metaAlignment,
		metaMargin,
		emailLinkColor,
		emailHoverColor,
		// Social settings
		showSocialLinks,
		socialIconColor,
		socialIconHoverColor,
		socialIconBackground,
		socialIconBackgroundHover,
		socialIconSize,
		socialIconSpacing,
		socialIconAlignment,
		// More content settings
		showMoreContent,
		moreContentBorderColor,
		moreContentPadding,
	} = attributes;

	return (
		<>
			<PresetStyles
				onApplyPreset={ ( settings ) => {
					// Apply all preset settings at once
					setAttributes( settings );
				} }
			/>

			<PanelBody title={ __( 'Block Background', 'author-profile-blocks' ) }>
				<BaseControl
					label={ __( 'Background Color', 'author-profile-blocks' ) }
				>
					<ColorPalette
						value={ backgroundColor }
						onChange={ ( value ) =>
							setAttributes( { backgroundColor: value } )
						}
					/>
				</BaseControl>
			</PanelBody>

			<AdvancedStyling
				gradientBackground={ gradientBackground }
				gradientStartColor={ gradientStartColor }
				gradientEndColor={ gradientEndColor }
				gradientDirection={ gradientDirection }
				transformScale={ transformScale }
				transformRotate={ transformRotate }
				filterBrightness={ filterBrightness }
				filterContrast={ filterContrast }
				filterSaturate={ filterSaturate }
				onChange={ setAttributes }
			/>

			{ showImage && (
				<PanelBody
					title={ __( 'Avatar', 'author-profile-blocks' ) }
					initialOpen={ false }
				>
					<RangeControl
						label={ __( 'Size', 'author-profile-blocks' ) }
						value={ avatarSize }
						onChange={ ( value ) =>
							setAttributes( { avatarSize: value } )
						}
						min={ 30 }
						max={ 300 }
						initialPosition={ 80 }
					/>

					<SelectControl
						label={ __( 'Shape', 'author-profile-blocks' ) }
						value={ avatarShape || 'circle' }
						options={ [
							{
								label: __( 'Circle', 'author-profile-blocks' ),
								value: 'circle',
							},
							{
								label: __( 'Square', 'author-profile-blocks' ),
								value: 'square',
							},
							{
								label: __( 'Rounded', 'author-profile-blocks' ),
								value: 'rounded',
							},
							{
								label: __( 'Custom', 'author-profile-blocks' ),
								value: 'custom',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { avatarShape: value } )
						}
					/>

					{ avatarShape === 'custom' && (
						<RangeControl
							label={ __( 'Border Radius', 'author-profile-blocks' ) }
							value={ avatarBorderRadius }
							onChange={ ( value ) =>
								setAttributes( { avatarBorderRadius: value } )
							}
							min={ 0 }
							max={ 50 }
							initialPosition={ 10 }
						/>
					) }

					<RangeControl
						label={ __( 'Border Width', 'author-profile-blocks' ) }
						value={ avatarBorderWidth }
						onChange={ ( value ) =>
							setAttributes( { avatarBorderWidth: value } )
						}
						min={ 0 }
						max={ 10 }
						initialPosition={ 0 }
					/>

					{ avatarBorderWidth > 0 && (
						<BaseControl
							label={ __( 'Border Color', 'author-profile-blocks' ) }
						>
							<ColorPalette
								value={ avatarBorderColor }
								onChange={ ( value ) =>
									setAttributes( { avatarBorderColor: value } )
								}
							/>
						</BaseControl>
					) }

					<SelectControl
						label={ __( 'Alignment', 'author-profile-blocks' ) }
						value={ avatarAlignment }
						options={ [
							{
								label: __( 'Left', 'author-profile-blocks' ),
								value: 'left',
							},
							{
								label: __( 'Center', 'author-profile-blocks' ),
								value: 'center',
							},
							{
								label: __( 'Right', 'author-profile-blocks' ),
								value: 'right',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { avatarAlignment: value } )
						}
					/>

					<RangeControl
						label={ __( 'Margin', 'author-profile-blocks' ) }
						value={ avatarMargin }
						onChange={ ( value ) =>
							setAttributes( { avatarMargin: value } )
						}
						min={ 0 }
						max={ 50 }
					/>
				</PanelBody>
			) }

			<PanelBody
				title={ __( 'Author Name', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<BaseControl label={ __( 'Text Color', 'author-profile-blocks' ) }>
					<ColorPalette
						value={ nameColor }
						onChange={ ( value ) =>
							setAttributes( { nameColor: value } )
						}
					/>
				</BaseControl>

				<RangeControl
					label={ __( 'Font Size', 'author-profile-blocks' ) }
					value={ nameSize }
					onChange={ ( value ) => setAttributes( { nameSize: value } ) }
					min={ 12 }
					max={ 48 }
				/>

				<SelectControl
					label={ __( 'Font Weight', 'author-profile-blocks' ) }
					value={ nameWeight || 'normal' }
					options={ [
						{
							label: __( 'Normal', 'author-profile-blocks' ),
							value: 'normal',
						},
						{
							label: __( 'Bold', 'author-profile-blocks' ),
							value: 'bold',
						},
						{
							label: __( 'Light', 'author-profile-blocks' ),
							value: '300',
						},
						{
							label: __( 'Semi-Bold', 'author-profile-blocks' ),
							value: '600',
						},
						{
							label: __( 'Extra Bold', 'author-profile-blocks' ),
							value: '800',
						},
					] }
					onChange={ ( value ) => setAttributes( { nameWeight: value } ) }
				/>

				<SelectControl
					label={ __( 'Text Transform', 'author-profile-blocks' ) }
					value={ nameTransform || 'none' }
					options={ [
						{
							label: __( 'None', 'author-profile-blocks' ),
							value: 'none',
						},
						{
							label: __( 'Uppercase', 'author-profile-blocks' ),
							value: 'uppercase',
						},
						{
							label: __( 'Lowercase', 'author-profile-blocks' ),
							value: 'lowercase',
						},
						{
							label: __( 'Capitalize', 'author-profile-blocks' ),
							value: 'capitalize',
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { nameTransform: value } )
					}
				/>

				<SelectControl
					label={ __( 'Alignment', 'author-profile-blocks' ) }
					value={ nameAlignment }
					options={ [
						{
							label: __( 'Left', 'author-profile-blocks' ),
							value: 'left',
						},
						{
							label: __( 'Center', 'author-profile-blocks' ),
							value: 'center',
						},
						{
							label: __( 'Right', 'author-profile-blocks' ),
							value: 'right',
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { nameAlignment: value } )
					}
				/>

				<RangeControl
					label={ __( 'Margin', 'author-profile-blocks' ) }
					value={ nameMargin }
					onChange={ ( value ) => setAttributes( { nameMargin: value } ) }
					min={ 0 }
					max={ 30 }
				/>
			</PanelBody>

			{ showDescription && (
				<PanelBody
					title={ __( 'Description', 'author-profile-blocks' ) }
					initialOpen={ false }
				>
					<BaseControl
						label={ __( 'Text Color', 'author-profile-blocks' ) }
					>
						<ColorPalette
							value={ descriptionColor }
							onChange={ ( value ) =>
								setAttributes( { descriptionColor: value } )
							}
						/>
					</BaseControl>

					<RangeControl
						label={ __( 'Font Size', 'author-profile-blocks' ) }
						value={ descriptionSize }
						onChange={ ( value ) =>
							setAttributes( { descriptionSize: value } )
						}
						min={ 12 }
						max={ 24 }
					/>

					<RangeControl
						label={ __( 'Line Height', 'author-profile-blocks' ) }
						value={ descriptionLineHeight }
						onChange={ ( value ) =>
							setAttributes( { descriptionLineHeight: value } )
						}
						min={ 1 }
						max={ 2.5 }
						step={ 0.1 }
					/>

					<SelectControl
						label={ __( 'Font Style', 'author-profile-blocks' ) }
						value={ descriptionStyle }
						options={ [
							{
								label: __( 'Normal', 'author-profile-blocks' ),
								value: 'normal',
							},
							{
								label: __( 'Italic', 'author-profile-blocks' ),
								value: 'italic',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { descriptionStyle: value } )
						}
					/>

					<SelectControl
						label={ __( 'Alignment', 'author-profile-blocks' ) }
						value={ descriptionAlignment }
						options={ [
							{
								label: __( 'Left', 'author-profile-blocks' ),
								value: 'left',
							},
							{
								label: __( 'Center', 'author-profile-blocks' ),
								value: 'center',
							},
							{
								label: __( 'Right', 'author-profile-blocks' ),
								value: 'right',
							},
							{
								label: __( 'Justify', 'author-profile-blocks' ),
								value: 'justify',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { descriptionAlignment: value } )
						}
					/>

					<RangeControl
						label={ __( 'Margin', 'author-profile-blocks' ) }
						value={ descriptionMargin }
						onChange={ ( value ) =>
							setAttributes( { descriptionMargin: value } )
						}
						min={ 0 }
						max={ 30 }
					/>
				</PanelBody>
			) }

			{ ( showEmail || showRegisteredDate ) && (
				<PanelBody
					title={ __( 'Meta Information', 'author-profile-blocks' ) }
					initialOpen={ false }
				>
					<BaseControl
						label={ __( 'Text Color', 'author-profile-blocks' ) }
					>
						<ColorPalette
							value={ metaColor }
							onChange={ ( value ) =>
								setAttributes( { metaColor: value } )
							}
						/>
					</BaseControl>

					<RangeControl
						label={ __( 'Font Size', 'author-profile-blocks' ) }
						value={ metaSize }
						onChange={ ( value ) => setAttributes( { metaSize: value } ) }
						min={ 10 }
						max={ 20 }
					/>

					<SelectControl
						label={ __( 'Font Style', 'author-profile-blocks' ) }
						value={ metaStyle }
						options={ [
							{
								label: __( 'Normal', 'author-profile-blocks' ),
								value: 'normal',
							},
							{
								label: __( 'Italic', 'author-profile-blocks' ),
								value: 'italic',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { metaStyle: value } )
						}
					/>

					<ToggleControl
						label={ __( 'Bold Text', 'author-profile-blocks' ) }
						checked={ metaBold }
						onChange={ () => setAttributes( { metaBold: ! metaBold } ) }
					/>

					<SelectControl
						label={ __( 'Alignment', 'author-profile-blocks' ) }
						value={ metaAlignment }
						options={ [
							{
								label: __( 'Left', 'author-profile-blocks' ),
								value: 'left',
							},
							{
								label: __( 'Center', 'author-profile-blocks' ),
								value: 'center',
							},
							{
								label: __( 'Right', 'author-profile-blocks' ),
								value: 'right',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { metaAlignment: value } )
						}
					/>

					<RangeControl
						label={ __( 'Margin', 'author-profile-blocks' ) }
						value={ metaMargin }
						onChange={ ( value ) =>
							setAttributes( { metaMargin: value } )
						}
						min={ 0 }
						max={ 30 }
					/>

					{ showEmail && (
						<>
							<CardDivider />
							<BaseControl
								label={ __(
									'Email Link Color',
									'author-profile-blocks',
								) }
							>
								<ColorPalette
									value={ emailLinkColor }
									onChange={ ( value ) =>
										setAttributes( { emailLinkColor: value } )
									}
								/>
							</BaseControl>

							<BaseControl
								label={ __(
									'Email Link Hover Color',
									'author-profile-blocks',
								) }
							>
								<ColorPalette
									value={ emailHoverColor }
									onChange={ ( value ) =>
										setAttributes( {
											emailHoverColor: value,
										} )
									}
								/>
							</BaseControl>
						</>
					) }
				</PanelBody>
			) }

			{ showSocialLinks && (
				<PanelBody
					title={ __( 'Social Icons', 'author-profile-blocks' ) }
					initialOpen={ false }
				>
					<BaseControl
						label={ __( 'Icon Color', 'author-profile-blocks' ) }
					>
						<ColorPalette
							value={ socialIconColor }
							onChange={ ( value ) =>
								setAttributes( { socialIconColor: value } )
							}
						/>
					</BaseControl>

					<BaseControl
						label={ __( 'Icon Hover Color', 'author-profile-blocks' ) }
					>
						<ColorPalette
							value={ socialIconHoverColor }
							onChange={ ( value ) =>
								setAttributes( { socialIconHoverColor: value } )
							}
						/>
					</BaseControl>

					<BaseControl
						label={ __( 'Background Color', 'author-profile-blocks' ) }
					>
						<ColorPalette
							value={ socialIconBackground }
							onChange={ ( value ) =>
								setAttributes( { socialIconBackground: value } )
							}
						/>
					</BaseControl>

					<BaseControl
						label={ __(
							'Background Hover Color',
							'author-profile-blocks',
						) }
					>
						<ColorPalette
							value={ socialIconBackgroundHover }
							onChange={ ( value ) =>
								setAttributes( {
									socialIconBackgroundHover: value,
								} )
							}
						/>
					</BaseControl>

					<RangeControl
						label={ __( 'Icon Size', 'author-profile-blocks' ) }
						value={ socialIconSize }
						onChange={ ( value ) =>
							setAttributes( { socialIconSize: value } )
						}
						min={ 12 }
						max={ 36 }
					/>

					<RangeControl
						label={ __( 'Icon Spacing', 'author-profile-blocks' ) }
						value={ socialIconSpacing }
						onChange={ ( value ) =>
							setAttributes( { socialIconSpacing: value } )
						}
						min={ 0 }
						max={ 30 }
					/>

					<SelectControl
						label={ __( 'Alignment', 'author-profile-blocks' ) }
						value={ socialIconAlignment }
						options={ [
							{
								label: __( 'Left', 'author-profile-blocks' ),
								value: 'left',
							},
							{
								label: __( 'Center', 'author-profile-blocks' ),
								value: 'center',
							},
							{
								label: __( 'Right', 'author-profile-blocks' ),
								value: 'right',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { socialIconAlignment: value } )
						}
					/>
				</PanelBody>
			) }

			{ showMoreContent && (
				<PanelBody
					title={ __( 'More Content Section', 'author-profile-blocks' ) }
					initialOpen={ false }
				>
					<BaseControl
						label={ __( 'Border Color', 'author-profile-blocks' ) }
					>
						<ColorPalette
							value={ moreContentBorderColor }
							onChange={ ( value ) =>
								setAttributes( { moreContentBorderColor: value } )
							}
						/>
					</BaseControl>

					<RangeControl
						label={ __( 'Top Padding', 'author-profile-blocks' ) }
						value={ moreContentPadding }
						onChange={ ( value ) =>
							setAttributes( { moreContentPadding: value } )
						}
						min={ 0 }
						max={ 50 }
					/>
				</PanelBody>
			) }
		</>
	);
};

export default StylePanel;
