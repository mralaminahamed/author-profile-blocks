import type { CarouselInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl } from '@wordpress/components';
import { AnimationControls } from '../../../../supports/js/components/inspector';
import { AdvancedTypography } from '../AdvancedTypography';

export default function AdvancedPanel( { attributes, setAttributes }: CarouselInspectorProps ) {
	const {
		animationType,
		animationDuration,
		hoverEffect,
		googleFont,
		fontSizeUnit,
		customCssClass,
		customVar1,
		customVar2,
	} = attributes;

	return (
		<>
			<AnimationControls
				animationType={ animationType }
				animationDuration={ animationDuration }
				hoverEffect={ hoverEffect }
				onChange={ setAttributes }
				itemLabel={ __( 'slides', 'author-profile-blocks' ) }
			/>

			<AdvancedTypography
				googleFont={ googleFont }
				fontSizeUnit={ fontSizeUnit }
				onChange={ setAttributes }
			/>

			<PanelBody title={ __( 'CSS Class', 'author-profile-blocks' ) } initialOpen={ false }>
				<TextControl
					label={ __( 'Additional CSS Class', 'author-profile-blocks' ) }
					value={ customCssClass || '' }
					onChange={ ( value ) => setAttributes( { customCssClass: value } ) }
					help={ __( 'Space-separated class names for custom styling', 'author-profile-blocks' ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'CSS Variables', 'author-profile-blocks' ) } initialOpen={ false }>
				<TextControl
					label="--author-carousel-custom-var-1"
					value={ customVar1 || '' }
					onChange={ ( value ) => setAttributes( { customVar1: value } ) }
				/>

				<TextControl
					label="--author-carousel-custom-var-2"
					value={ customVar2 || '' }
					onChange={ ( value ) => setAttributes( { customVar2: value } ) }
				/>
			</PanelBody>
		</>
	);
}
