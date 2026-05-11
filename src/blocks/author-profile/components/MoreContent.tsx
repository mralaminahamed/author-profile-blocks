import { __ } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';

interface Props {
	content: string;
	onContentChange: ( value: string ) => void;
}

const MoreContent = ( { content, onContentChange }: Props ) => (
	<div className="apbl-author-more-content">
		<p className="apbl-more-content-label">
			{ __( 'Additional Information', 'author-profile-blocks' ) }
		</p>
		<RichText
			tagName="div"
			value={ content }
			onChange={ onContentChange }
			placeholder={ __(
				'Add extra author info: biography, achievements, contact details…',
				'author-profile-blocks',
			) }
			className="apbl-more-content-editor"
			allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
		/>
	</div>
);

export default MoreContent;
