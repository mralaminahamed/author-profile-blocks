/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl, ToggleControl, RangeControl } from '@wordpress/components';

/**
 * Style Panel Component
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Set attributes function
 * @return {JSX.Element} Style panel
 */
export default function StylePanel( { attributes, setAttributes } ) {
	const {
		backgroundColor,
		enableShadow,
		enableBorder,
		enableRounded,
		boxShadow,
		boxShadowColor,
		boxShadowBlur,
		boxShadowSpread,
		boxShadowHorizontal,
		boxShadowVertical,
		gradientBackground,
		gradientStartColor,
		gradientEndColor,
		gradientDirection,
		transformScale,
		transformRotate,
		filterBrightness,
		filterContrast,
		filterSaturate,
	} = attributes;

	return (
		<PanelBody title={ __( 'Style', 'author-profile-blocks' ) }>
			<TextControl
				label={ __( 'Background Color', 'author-profile-blocks' ) }
				value={ backgroundColor }
				onChange={ ( value ) =>
					setAttributes( { backgroundColor: value } )
				}
				type="color"
			/>

			<ToggleControl
				label={ __( 'Enable Shadow', 'author-profile-blocks' ) }
				checked={ enableShadow }
				onChange={ ( value ) =>
					setAttributes( { enableShadow: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Enable Border', 'author-profile-blocks' ) }
				checked={ enableBorder }
				onChange={ ( value ) =>
					setAttributes( { enableBorder: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Rounded Corners', 'author-profile-blocks' ) }
				checked={ enableRounded }
				onChange={ ( value ) =>
					setAttributes( { enableRounded: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Custom Box Shadow', 'author-profile-blocks' ) }
				checked={ boxShadow }
				onChange={ ( value ) =>
					setAttributes( { boxShadow: value } )
				}
			/>

			{ boxShadow && (
				<>
					<TextControl
						label={ __( 'Shadow Color', 'author-profile-blocks' ) }
						value={ boxShadowColor }
						onChange={ ( value ) =>
							setAttributes( { boxShadowColor: value } )
						}
						type="color"
					/>

					<RangeControl
						label={ __( 'Shadow Blur', 'author-profile-blocks' ) }
						value={ boxShadowBlur }
						onChange={ ( value ) =>
							setAttributes( { boxShadowBlur: value } )
						}
						min={ 0 }
						max={ 50 }
					/>

					<RangeControl
						label={ __( 'Shadow Spread', 'author-profile-blocks' ) }
						value={ boxShadowSpread }
						onChange={ ( value ) =>
							setAttributes( { boxShadowSpread: value } )
						}
						min={ -25 }
						max={ 25 }
					/>

					<RangeControl
						label={ __( 'Shadow Horizontal', 'author-profile-blocks' ) }
						value={ boxShadowHorizontal }
						onChange={ ( value ) =>
							setAttributes( { boxShadowHorizontal: value } )
						}
						min={ -25 }
						max={ 25 }
					/>

					<RangeControl
						label={ __( 'Shadow Vertical', 'author-profile-blocks' ) }
						value={ boxShadowVertical }
						onChange={ ( value ) =>
							setAttributes( { boxShadowVertical: value } )
						}
						min={ -25 }
						max={ 25 }
					/>
				</>
			) }

			<ToggleControl
				label={ __( 'Gradient Background', 'author-profile-blocks' ) }
				checked={ gradientBackground }
				onChange={ ( value ) =>
					setAttributes( { gradientBackground: value } )
				}
			/>

			{ gradientBackground && (
				<>
					<TextControl
						label={ __( 'Gradient Start Color', 'author-profile-blocks' ) }
						value={ gradientStartColor }
						onChange={ ( value ) =>
							setAttributes( { gradientStartColor: value } )
						}
						type="color"
					/>

					<TextControl
						label={ __( 'Gradient End Color', 'author-profile-blocks' ) }
						value={ gradientEndColor }
						onChange={ ( value ) =>
							setAttributes( { gradientEndColor: value } )
						}
						type="color"
					/>

					<TextControl
						label={ __( 'Gradient Direction', 'author-profile-blocks' ) }
						value={ gradientDirection }
						onChange={ ( value ) =>
							setAttributes( { gradientDirection: value } )
						}
						placeholder={ __( 'to bottom, 45deg, etc.', 'author-profile-blocks' ) }
					/>
				</>
			) }

			<RangeControl
				label={ __( 'Scale Transform', 'author-profile-blocks' ) }
				value={ transformScale }
				onChange={ ( value ) =>
					setAttributes( { transformScale: value } )
				}
				min={ 0.5 }
				max={ 2 }
				step={ 0.1 }
			/>

			<RangeControl
				label={ __( 'Rotate Transform', 'author-profile-blocks' ) }
				value={ transformRotate }
				onChange={ ( value ) =>
					setAttributes( { transformRotate: value } )
				}
				min={ -180 }
				max={ 180 }
			/>

			<RangeControl
				label={ __( 'Brightness Filter', 'author-profile-blocks' ) }
				value={ filterBrightness }
				onChange={ ( value ) =>
					setAttributes( { filterBrightness: value } )
				}
				min={ 0 }
				max={ 200 }
			/>

			<RangeControl
				label={ __( 'Contrast Filter', 'author-profile-blocks' ) }
				value={ filterContrast }
				onChange={ ( value ) =>
					setAttributes( { filterContrast: value } )
				}
				min={ 0 }
				max={ 200 }
			/>

			<RangeControl
				label={ __( 'Saturation Filter', 'author-profile-blocks' ) }
				value={ filterSaturate }
				onChange={ ( value ) =>
					setAttributes( { filterSaturate: value } )
				}
				min={ 0 }
				max={ 200 }
			/>
		</PanelBody>
	);
}
