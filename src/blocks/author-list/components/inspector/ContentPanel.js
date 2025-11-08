/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	ToggleControl,
	RangeControl,
	SelectControl,
} from '@wordpress/components';

/**
 * ContentPanel component for content-related settings in the InspectorControls
 *
 * @param {Object}   props               Component props
 * @param {Object}   props.attributes    Block attributes
 * @param {Function} props.setAttributes Function to set block attributes
 * @return {JSX.Element} Content panel component
 */
const ContentPanel = ( { attributes, setAttributes } ) => {
	const {
		authorRole,
		maxAuthors,
		showImage,
		showPosition,
		showEmail,
		showDescription,
		showSocial,
		lazyLoad,
		contentTabs,
	} = attributes;

	return (
		<>
			<PanelBody
				title={ __( 'Author Selection', 'author-profile-blocks' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Filter by Role', 'author-profile-blocks' ) }
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

				<RangeControl
					label={ __(
						'Maximum Authors to Display',
						'author-profile-blocks',
					) }
					value={ maxAuthors }
					onChange={ ( value ) =>
						setAttributes( { maxAuthors: value } )
					}
					min={ 0 }
					max={ 100 }
					step={ 1 }
					help={ __(
						'Set to 0 to show all selected authors',
						'author-profile-blocks',
					) }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Display Elements', 'author-profile-blocks' ) }
				initialOpen={ true }
			>
				<ToggleControl
					label={ __( 'Show Author Image', 'author-profile-blocks' ) }
					checked={ showImage }
					onChange={ () =>
						setAttributes( { showImage: ! showImage } )
					}
				/>

				<ToggleControl
					label={ __( 'Show Position/Role', 'author-profile-blocks' ) }
					checked={ showPosition }
					onChange={ () =>
						setAttributes( { showPosition: ! showPosition } )
					}
				/>

				<ToggleControl
					label={ __( 'Show Email', 'author-profile-blocks' ) }
					checked={ showEmail }
					onChange={ () =>
						setAttributes( { showEmail: ! showEmail } )
					}
				/>

				<ToggleControl
					label={ __( 'Show Description', 'author-profile-blocks' ) }
					checked={ showDescription }
					onChange={ () =>
						setAttributes( { showDescription: ! showDescription } )
					}
				/>

				<ToggleControl
					label={ __( 'Show Social Links', 'author-profile-blocks' ) }
					checked={ showSocial }
					onChange={ () =>
						setAttributes( { showSocial: ! showSocial } )
					}
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Performance', 'author-profile-blocks' ) }
				initialOpen={ false }
			>
				<ToggleControl
					label={ __( 'Lazy Load Images', 'author-profile-blocks' ) }
					checked={ lazyLoad }
					onChange={ () =>
						setAttributes( { lazyLoad: ! lazyLoad } )
					}
					help={ __(
						'Load images only when they come into view to improve performance',
						'author-profile-blocks',
					) }
				/>

				<ToggleControl
					label={ __( 'Content Tabs', 'author-profile-blocks' ) }
					checked={ contentTabs }
					onChange={ () =>
						setAttributes( { contentTabs: ! contentTabs } )
					}
					help={ __(
						'Display author information in organized tabs',
						'author-profile-blocks',
					) }
				/>
			</PanelBody>
		</>
	);
};

export default ContentPanel;
