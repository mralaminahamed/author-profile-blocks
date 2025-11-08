/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup, Flex, FlexItem } from '@wordpress/components';
import { columns, tablet } from '@wordpress/icons';

/**
 * Display style selector component for the Author List block.
 *
 * @param          value.value
 * @param          value
 * @param          onChange
 * @param {Object} props          Component props.
 * @param          value.onChange
 * @return {JSX.Element} Element to render.
 */
export default function DisplayStyleSelector( { value, onChange, ...props } ) {
	const styles = [
		{
			name: 'compact',
			label: __( 'Compact', 'author-profile-blocks' ),
			icon: tablet,
		},
		{
			name: 'detailed',
			label: __( 'Detailed', 'author-profile-blocks' ),
			icon: columns,
		},
	];

	return (
		<div className="apbl-display-style-selector" { ...props }>
			<Flex gap={ 2 } direction="column">
				<FlexItem>
					<span className="components-base-control__label">
						{ __( 'Display Style', 'author-profile-blocks' ) }
					</span>
				</FlexItem>
				<FlexItem>
					<ButtonGroup>
						{ styles.map( ( style ) => (
							<Button
								key={ style.name }
								icon={ style.icon }
								label={ style.label }
								isPressed={ value === style.name }
								onClick={ () => onChange( style.name ) }
								showTooltip
							/>
						) ) }
					</ButtonGroup>
				</FlexItem>
			</Flex>
		</div>
	);
}
