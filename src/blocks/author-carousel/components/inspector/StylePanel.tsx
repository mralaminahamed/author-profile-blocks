import type { CarouselInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	ToggleControl,
	RangeControl,
	BaseControl,
	ColorPalette,
	SelectControl,
} from '@wordpress/components';

const GRADIENT_DIRECTIONS = [
	{ label: __( 'Top → Bottom', 'author-profile-blocks' ), value: 'to bottom' },
	{ label: __( 'Bottom → Top', 'author-profile-blocks' ), value: 'to top' },
	{ label: __( 'Left → Right', 'author-profile-blocks' ), value: 'to right' },
	{ label: __( 'Right → Left', 'author-profile-blocks' ), value: 'to left' },
	{ label: __( 'Diagonal ↘', 'author-profile-blocks' ), value: 'to bottom right' },
	{ label: __( 'Diagonal ↗', 'author-profile-blocks' ), value: 'to top right' },
];

export default function StylePanel( { attributes, setAttributes }: CarouselInspectorProps ) {
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
		<>
			<PanelBody title={ __( 'Background', 'author-profile-blocks' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Gradient Background', 'author-profile-blocks' ) }
					checked={ gradientBackground }
					onChange={ () => setAttributes( { gradientBackground: ! gradientBackground } ) }
				/>

				{ gradientBackground ? (
					<>
						<SelectControl
							label={ __( 'Direction', 'author-profile-blocks' ) }
							value={ gradientDirection }
							options={ GRADIENT_DIRECTIONS }
							onChange={ ( value ) => setAttributes( { gradientDirection: value } ) }
						/>

						<BaseControl label={ __( 'Start Color', 'author-profile-blocks' ) }>
							<ColorPalette
								value={ gradientStartColor }
								onChange={ ( value ) => setAttributes( { gradientStartColor: value } ) }
							/>
						</BaseControl>

						<BaseControl label={ __( 'End Color', 'author-profile-blocks' ) }>
							<ColorPalette
								value={ gradientEndColor }
								onChange={ ( value ) => setAttributes( { gradientEndColor: value } ) }
							/>
						</BaseControl>
					</>
				) : (
					<BaseControl label={ __( 'Background Color', 'author-profile-blocks' ) }>
						<ColorPalette
							value={ backgroundColor }
							onChange={ ( value ) => setAttributes( { backgroundColor: value } ) }
						/>
					</BaseControl>
				) }
			</PanelBody>

			<PanelBody title={ __( 'Box Shadow', 'author-profile-blocks' ) } initialOpen={ false }>
				<ToggleControl
					label={ __( 'Enable Box Shadow', 'author-profile-blocks' ) }
					checked={ boxShadow }
					onChange={ () => setAttributes( { boxShadow: ! boxShadow } ) }
				/>

				{ boxShadow && (
					<>
						<BaseControl label={ __( 'Shadow Color', 'author-profile-blocks' ) }>
							<ColorPalette
								value={ boxShadowColor }
								onChange={ ( value ) => setAttributes( { boxShadowColor: value } ) }
							/>
						</BaseControl>

						<RangeControl
							label={ __( 'Horizontal Offset', 'author-profile-blocks' ) }
							value={ boxShadowHorizontal }
							onChange={ ( value ) => setAttributes( { boxShadowHorizontal: value } ) }
							min={ -25 }
							max={ 25 }
						/>

						<RangeControl
							label={ __( 'Vertical Offset', 'author-profile-blocks' ) }
							value={ boxShadowVertical }
							onChange={ ( value ) => setAttributes( { boxShadowVertical: value } ) }
							min={ -25 }
							max={ 25 }
						/>

						<RangeControl
							label={ __( 'Blur Radius', 'author-profile-blocks' ) }
							value={ boxShadowBlur }
							onChange={ ( value ) => setAttributes( { boxShadowBlur: value } ) }
							min={ 0 }
							max={ 50 }
						/>

						<RangeControl
							label={ __( 'Spread', 'author-profile-blocks' ) }
							value={ boxShadowSpread }
							onChange={ ( value ) => setAttributes( { boxShadowSpread: value } ) }
							min={ -25 }
							max={ 25 }
						/>
					</>
				) }
			</PanelBody>

			<PanelBody title={ __( 'Transforms & Filters', 'author-profile-blocks' ) } initialOpen={ false }>
				<RangeControl
					label={ __( 'Scale', 'author-profile-blocks' ) }
					value={ transformScale }
					onChange={ ( value ) => setAttributes( { transformScale: value } ) }
					min={ 0.5 }
					max={ 2 }
					step={ 0.05 }
				/>

				<RangeControl
					label={ __( 'Rotate (deg)', 'author-profile-blocks' ) }
					value={ transformRotate }
					onChange={ ( value ) => setAttributes( { transformRotate: value } ) }
					min={ -180 }
					max={ 180 }
				/>

				<RangeControl
					label={ __( 'Brightness (%)', 'author-profile-blocks' ) }
					value={ filterBrightness }
					onChange={ ( value ) => setAttributes( { filterBrightness: value } ) }
					min={ 0 }
					max={ 200 }
					help={ __( '100 = normal', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Contrast (%)', 'author-profile-blocks' ) }
					value={ filterContrast }
					onChange={ ( value ) => setAttributes( { filterContrast: value } ) }
					min={ 0 }
					max={ 200 }
					help={ __( '100 = normal', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Saturation (%)', 'author-profile-blocks' ) }
					value={ filterSaturate }
					onChange={ ( value ) => setAttributes( { filterSaturate: value } ) }
					min={ 0 }
					max={ 200 }
					help={ __( '100 = normal', 'author-profile-blocks' ) }
				/>
			</PanelBody>
		</>
	);
}
