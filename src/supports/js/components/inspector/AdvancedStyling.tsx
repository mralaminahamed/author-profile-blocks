import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	ToggleControl,
	BaseControl,
	ColorPalette,
	SelectControl,
	RangeControl,
} from '@wordpress/components';

interface AdvancedStylingProps {
	gradientBackground: boolean;
	gradientStartColor: string;
	gradientEndColor: string;
	gradientDirection: string;
	transformScale: number;
	transformRotate: number;
	filterBrightness: number;
	filterContrast: number;
	filterSaturate: number;
	onChange: ( attrs: Record< string, unknown > ) => void;
}

const GRADIENT_DIRECTIONS = [
	{ value: 'to bottom',       label: __( 'Top → Bottom',  'author-profile-blocks' ) },
	{ value: 'to right',        label: __( 'Left → Right',  'author-profile-blocks' ) },
	{ value: 'to bottom right', label: __( 'Diagonal ↘',    'author-profile-blocks' ) },
	{ value: 'to bottom left',  label: __( 'Diagonal ↙',    'author-profile-blocks' ) },
	{ value: '45deg',           label: __( '45°',            'author-profile-blocks' ) },
	{ value: '135deg',          label: __( '135°',           'author-profile-blocks' ) },
];

export function AdvancedStyling( {
	gradientBackground,
	gradientStartColor,
	gradientEndColor,
	gradientDirection,
	transformScale,
	transformRotate,
	filterBrightness,
	filterContrast,
	filterSaturate,
	onChange,
}: AdvancedStylingProps ) {
	return (
		<PanelBody title={ __( 'Advanced Styling', 'author-profile-blocks' ) } initialOpen={ false }>
			<ToggleControl
				label={ __( 'Gradient Background', 'author-profile-blocks' ) }
				checked={ gradientBackground }
				onChange={ ( value ) => onChange( { gradientBackground: value } ) }
			/>

			{ gradientBackground && (
				<>
					<BaseControl label={ __( 'Start Color', 'author-profile-blocks' ) }>
						<ColorPalette
							value={ gradientStartColor }
							onChange={ ( value ) => onChange( { gradientStartColor: value } ) }
							clearable={ false }
						/>
					</BaseControl>

					<BaseControl label={ __( 'End Color', 'author-profile-blocks' ) }>
						<ColorPalette
							value={ gradientEndColor }
							onChange={ ( value ) => onChange( { gradientEndColor: value } ) }
							clearable={ false }
						/>
					</BaseControl>

					<SelectControl
						label={ __( 'Direction', 'author-profile-blocks' ) }
						value={ gradientDirection }
						options={ GRADIENT_DIRECTIONS }
						onChange={ ( value ) => onChange( { gradientDirection: value } ) }
					/>
				</>
			) }

			<div className="apbl-inspector-section">
				<p className="apbl-inspector-section__title">
					{ __( 'Transform', 'author-profile-blocks' ) }
				</p>

				<RangeControl
					label={ __( 'Scale', 'author-profile-blocks' ) }
					value={ transformScale }
					onChange={ ( value ) => onChange( { transformScale: value } ) }
					min={ 0.5 }
					max={ 2 }
					step={ 0.1 }
					help={ __( '1 = normal size', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Rotation (deg)', 'author-profile-blocks' ) }
					value={ transformRotate }
					onChange={ ( value ) => onChange( { transformRotate: value } ) }
					min={ -180 }
					max={ 180 }
					step={ 5 }
				/>
			</div>

			<div className="apbl-inspector-section">
				<p className="apbl-inspector-section__title">
					{ __( 'CSS Filters', 'author-profile-blocks' ) }
				</p>

				<RangeControl
					label={ __( 'Brightness (%)', 'author-profile-blocks' ) }
					value={ filterBrightness }
					onChange={ ( value ) => onChange( { filterBrightness: value } ) }
					min={ 0 }
					max={ 200 }
					step={ 5 }
					help={ __( '100 = normal', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Contrast (%)', 'author-profile-blocks' ) }
					value={ filterContrast }
					onChange={ ( value ) => onChange( { filterContrast: value } ) }
					min={ 0 }
					max={ 200 }
					step={ 5 }
					help={ __( '100 = normal', 'author-profile-blocks' ) }
				/>

				<RangeControl
					label={ __( 'Saturation (%)', 'author-profile-blocks' ) }
					value={ filterSaturate }
					onChange={ ( value ) => onChange( { filterSaturate: value } ) }
					min={ 0 }
					max={ 200 }
					step={ 5 }
					help={ __( '100 = normal', 'author-profile-blocks' ) }
				/>
			</div>
		</PanelBody>
	);
}
