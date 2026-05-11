import type { CarouselInspectorProps } from '../../types';
import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, RangeControl, SelectControl } from '@wordpress/components';

const ROLE_OPTIONS = [
	{ label: __( 'All Roles', 'author-profile-blocks' ), value: '' },
	{ label: __( 'Administrator', 'author-profile-blocks' ), value: 'administrator' },
	{ label: __( 'Editor', 'author-profile-blocks' ), value: 'editor' },
	{ label: __( 'Author', 'author-profile-blocks' ), value: 'author' },
	{ label: __( 'Contributor', 'author-profile-blocks' ), value: 'contributor' },
	{ label: __( 'Subscriber', 'author-profile-blocks' ), value: 'subscriber' },
];

export default function ContentPanel( { attributes, setAttributes }: CarouselInspectorProps ) {
	const {
		authorRole,
		maxAuthors,
		showImage,
		showEmail,
		showDescription,
		showPosition,
		showSocial,
		showRegisteredDate,
		slidesToShow,
		autoplay,
		autoplaySpeed,
		showDots,
		showArrows,
		infinite,
		lazyLoad,
		contentTabs,
	} = attributes;

	return (
		<>
			<PanelBody title={ __( 'Author Filter', 'author-profile-blocks' ) } initialOpen={ true }>
				<SelectControl
					label={ __( 'Filter by Role', 'author-profile-blocks' ) }
					value={ authorRole }
					options={ ROLE_OPTIONS }
					onChange={ ( value ) => setAttributes( { authorRole: value } ) }
				/>

				<RangeControl
					label={ __( 'Max Authors', 'author-profile-blocks' ) }
					value={ maxAuthors }
					onChange={ ( value ) => setAttributes( { maxAuthors: value } ) }
					min={ 1 }
					max={ 50 }
					help={ __( 'Maximum number of authors to show', 'author-profile-blocks' ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Display Elements', 'author-profile-blocks' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Show Avatar', 'author-profile-blocks' ) }
					checked={ showImage }
					onChange={ () => setAttributes( { showImage: ! showImage } ) }
				/>

				<ToggleControl
					label={ __( 'Show Position / Role', 'author-profile-blocks' ) }
					checked={ showPosition }
					onChange={ () => setAttributes( { showPosition: ! showPosition } ) }
				/>

				<ToggleControl
					label={ __( 'Show Email', 'author-profile-blocks' ) }
					checked={ showEmail }
					onChange={ () => setAttributes( { showEmail: ! showEmail } ) }
				/>

				<ToggleControl
					label={ __( 'Show Description', 'author-profile-blocks' ) }
					checked={ showDescription }
					onChange={ () => setAttributes( { showDescription: ! showDescription } ) }
				/>

				<ToggleControl
					label={ __( 'Show Social Links', 'author-profile-blocks' ) }
					checked={ showSocial }
					onChange={ () => setAttributes( { showSocial: ! showSocial } ) }
				/>

				<ToggleControl
					label={ __( 'Show Registration Date', 'author-profile-blocks' ) }
					checked={ showRegisteredDate }
					onChange={ () => setAttributes( { showRegisteredDate: ! showRegisteredDate } ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Carousel Settings', 'author-profile-blocks' ) } initialOpen={ false }>
				<RangeControl
					label={ __( 'Slides to Show', 'author-profile-blocks' ) }
					value={ slidesToShow }
					onChange={ ( value ) => setAttributes( { slidesToShow: value } ) }
					min={ 1 }
					max={ 6 }
				/>

				<ToggleControl
					label={ __( 'Show Navigation Dots', 'author-profile-blocks' ) }
					checked={ showDots }
					onChange={ () => setAttributes( { showDots: ! showDots } ) }
				/>

				<ToggleControl
					label={ __( 'Show Navigation Arrows', 'author-profile-blocks' ) }
					checked={ showArrows }
					onChange={ () => setAttributes( { showArrows: ! showArrows } ) }
				/>

				<ToggleControl
					label={ __( 'Infinite Loop', 'author-profile-blocks' ) }
					checked={ infinite }
					onChange={ () => setAttributes( { infinite: ! infinite } ) }
				/>

				<ToggleControl
					label={ __( 'Autoplay', 'author-profile-blocks' ) }
					checked={ autoplay }
					onChange={ () => setAttributes( { autoplay: ! autoplay } ) }
				/>

				{ autoplay && (
					<RangeControl
						label={ __( 'Autoplay Speed (ms)', 'author-profile-blocks' ) }
						value={ autoplaySpeed }
						onChange={ ( value ) => setAttributes( { autoplaySpeed: value } ) }
						min={ 1000 }
						max={ 10000 }
						step={ 500 }
					/>
				) }
			</PanelBody>

			<PanelBody title={ __( 'Performance', 'author-profile-blocks' ) } initialOpen={ false }>
				<ToggleControl
					label={ __( 'Lazy Load Images', 'author-profile-blocks' ) }
					checked={ lazyLoad }
					onChange={ () => setAttributes( { lazyLoad: ! lazyLoad } ) }
					help={ __( 'Load images only when scrolled into view', 'author-profile-blocks' ) }
				/>

				<ToggleControl
					label={ __( 'Content Tabs', 'author-profile-blocks' ) }
					checked={ contentTabs }
					onChange={ () => setAttributes( { contentTabs: ! contentTabs } ) }
					help={ __( 'Organize author info in tabbed sections', 'author-profile-blocks' ) }
				/>
			</PanelBody>
		</>
	);
}
