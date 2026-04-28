import type { CarouselInspectorProps } from '../../types';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, RangeControl, SelectControl } from '@wordpress/components';

/**
 * Content Panel Component
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Set attributes function
 * @return {JSX.Element} Content panel
 */
export default function ContentPanel( { attributes, setAttributes } ) {
	const {
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
		maxAuthors,
		authorRole,
		lazyLoad,
		contentTabs,
	} = attributes;

	return (
		<PanelBody title={ __( 'Content', 'author-profile-blocks' ) }>
			<ToggleControl
				label={ __( 'Show Avatar', 'author-profile-blocks' ) }
				checked={ showImage }
				onChange={ ( value ) =>
					setAttributes( { showImage: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Show Email', 'author-profile-blocks' ) }
				checked={ showEmail }
				onChange={ ( value ) =>
					setAttributes( { showEmail: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Show Description', 'author-profile-blocks' ) }
				checked={ showDescription }
				onChange={ ( value ) =>
					setAttributes( { showDescription: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Show Position', 'author-profile-blocks' ) }
				checked={ showPosition }
				onChange={ ( value ) =>
					setAttributes( { showPosition: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Show Social Links', 'author-profile-blocks' ) }
				checked={ showSocial }
				onChange={ ( value ) =>
					setAttributes( { showSocial: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Show Registration Date', 'author-profile-blocks' ) }
				checked={ showRegisteredDate }
				onChange={ ( value ) =>
					setAttributes( { showRegisteredDate: value } )
				}
			/>

			<RangeControl
				label={ __( 'Slides to Show', 'author-profile-blocks' ) }
				value={ slidesToShow }
				onChange={ ( value ) =>
					setAttributes( { slidesToShow: value } )
				}
				min={ 1 }
				max={ 6 }
			/>

			<RangeControl
				label={ __( 'Max Authors', 'author-profile-blocks' ) }
				value={ maxAuthors }
				onChange={ ( value ) =>
					setAttributes( { maxAuthors: value } )
				}
				min={ 1 }
				max={ 50 }
			/>

			<SelectControl
				label={ __( 'Author Role', 'author-profile-blocks' ) }
				value={ authorRole }
				options={ [
					{ label: __( 'All Roles', 'author-profile-blocks' ), value: '' },
					{ label: __( 'Administrator', 'author-profile-blocks' ), value: 'administrator' },
					{ label: __( 'Editor', 'author-profile-blocks' ), value: 'editor' },
					{ label: __( 'Author', 'author-profile-blocks' ), value: 'author' },
					{ label: __( 'Contributor', 'author-profile-blocks' ), value: 'contributor' },
					{ label: __( 'Subscriber', 'author-profile-blocks' ), value: 'subscriber' },
				] }
				onChange={ ( value ) =>
					setAttributes( { authorRole: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Autoplay', 'author-profile-blocks' ) }
				checked={ autoplay }
				onChange={ ( value ) =>
					setAttributes( { autoplay: value } )
				}
			/>

			{ autoplay && (
				<RangeControl
					label={ __( 'Autoplay Speed (ms)', 'author-profile-blocks' ) }
					value={ autoplaySpeed }
					onChange={ ( value ) =>
						setAttributes( { autoplaySpeed: value } )
					}
					min={ 1000 }
					max={ 10000 }
					step={ 500 }
				/>
			) }

			<ToggleControl
				label={ __( 'Show Dots', 'author-profile-blocks' ) }
				checked={ showDots }
				onChange={ ( value ) =>
					setAttributes( { showDots: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Show Arrows', 'author-profile-blocks' ) }
				checked={ showArrows }
				onChange={ ( value ) =>
					setAttributes( { showArrows: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Infinite Loop', 'author-profile-blocks' ) }
				checked={ infinite }
				onChange={ ( value ) =>
					setAttributes( { infinite: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Lazy Load Images', 'author-profile-blocks' ) }
				checked={ lazyLoad }
				onChange={ ( value ) =>
					setAttributes( { lazyLoad: value } )
				}
			/>

			<ToggleControl
				label={ __( 'Content Tabs', 'author-profile-blocks' ) }
				checked={ contentTabs }
				onChange={ ( value ) =>
					setAttributes( { contentTabs: value } )
				}
			/>
		</PanelBody>
	);
}
