import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup, Flex, FlexItem } from '@wordpress/components';
import { columns, tablet } from '@wordpress/icons';
import { Minus } from 'lucide-react';

const STYLES = [
	{ name: 'compact',  label: __( 'Compact',  'author-profile-blocks' ), icon: tablet   },
	{ name: 'detailed', label: __( 'Detailed', 'author-profile-blocks' ), icon: columns  },
	{ name: 'minimal',  label: __( 'Minimal',  'author-profile-blocks' ), icon: null     },
];

interface Props {
	value: string;
	onChange: ( value: string ) => void;
	[ key: string ]: unknown;
}

export default function DisplayStyleSelector( { value, onChange, ...props }: Props ) {
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
						{ STYLES.map( ( style ) => (
							<Button
								key={ style.name }
								icon={ style.icon ?? undefined }
								label={ style.label }
								isPressed={ value === style.name }
								onClick={ () => onChange( style.name ) }
								showTooltip
							>
								{ ! style.icon && (
									<Minus size={ 14 } strokeWidth={ 1.75 } style={ { marginRight: '2px' } } />
								) }
							</Button>
						) ) }
					</ButtonGroup>
				</FlexItem>
			</Flex>
		</div>
	);
}
