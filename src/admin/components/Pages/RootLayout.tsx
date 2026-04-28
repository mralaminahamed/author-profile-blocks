import { Link, Outlet, useLocation } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import { Settings, Puzzle } from 'lucide-react';
import { cn } from '@/lib/utils';

const NAV_LINKS = [
	{ to: '/', label: 'Settings', icon: Settings },
	{ to: '/plugins', label: 'Our Plugins', icon: Puzzle },
] as const;

export default function RootLayout() {
	const { pathname } = useLocation();

	return (
		<div className="apbl:min-h-screen apbl:bg-background">
			<header className="apbl:sticky apbl:top-8 apbl:z-20 apbl:h-12 apbl:bg-card apbl:border-b apbl:border-border apbl:flex apbl:items-center apbl:px-6 apbl:gap-6">
				<span className="apbl:text-sm apbl:font-bold apbl:text-card-foreground apbl:mr-2">
					{ __( 'Author Profile Blocks', 'author-profile-blocks' ) }
				</span>
				{ NAV_LINKS.map( ( { to, label, icon: Icon } ) => {
					const active = to === '/' ? pathname === '/' : pathname.startsWith( to );
					return (
						<Link
							key={ to }
							to={ to }
							className={ cn(
								'apbl:flex apbl:items-center apbl:gap-1.5 apbl:text-sm apbl:transition-colors',
								active
									? 'apbl:text-blue-600 apbl:dark:text-blue-400 apbl:font-medium'
									: 'apbl:text-muted-foreground apbl:hover:text-foreground'
							) }
						>
							<Icon className="apbl:w-4 apbl:h-4" />
							{ __( label, 'author-profile-blocks' ) }
						</Link>
					);
				} ) }
			</header>
			<Outlet />
		</div>
	);
}
